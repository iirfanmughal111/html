<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use function count;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use function array_splice;
use function array_reverse;
use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\AbstractCollection;

trait CommentableTrait
{
    /**
     * @return string
     */
    abstract public function getCommentContentType();

    /**
     * @param null $error
     * @return bool
     */
    public function canComment(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        /** @var Group|null $group */
        $group = $this->Group;
        if ($group === null) {
            return false;
        }

        $member = $group->Member;

        return $member !== null && $member->isValidMember();
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function onCommentCreated(Comment $comment)
    {
        if ($comment->comment_id === $this->first_comment_id) {
            return;
        }

        $this->comment_count++;

        $latestCommentIds = $this->latest_comment_ids;
        $latestCommentIds[] = $comment->comment_id;

        $maxCommentIds = $this->getMaxLastCommentIds();
        if (count($latestCommentIds) > $maxCommentIds) {
            array_splice($latestCommentIds, 0, -1 * $maxCommentIds);
        }

        $this->latest_comment_ids = $latestCommentIds;
        $this->last_comment_date = $comment->comment_date;
    }

    /**
     * @param Comment $comment
     * @param Comment|null $lastComment
     * @return void
     */
    public function onCommentDeleted(Comment $comment, Comment $lastComment = null)
    {
        if ($comment->comment_id === $this->first_comment_id) {
            return;
        }

        $this->comment_count--;

        $latestCommentIds = $this->latest_comment_ids;
        $maxCommentIds = $this->getMaxLastCommentIds();
        $latestCommentIds = array_diff($latestCommentIds, [$comment->comment_id]);

        if ($lastComment !== null) {
            $latestCommentIds[] = $lastComment->comment_id;
        }
        if (count($latestCommentIds) > $maxCommentIds) {
            array_splice($latestCommentIds, 0, -1 * $maxCommentIds);
        }

        $this->last_comment_date = $lastComment !== null ? $lastComment->comment_date : 0;
        $this->latest_comment_ids = $latestCommentIds;
    }

    /**
     * @param string $contentType
     * @param int $contentId
     * @return void
     */
    public function deleteAllComments($contentType, $contentId)
    {
        /** @var \XF\Db\AbstractAdapter $db */
        $db = $this->db();

        $commentIds = $db->fetchAllColumn('
            SELECT `comment_id`
            FROM `xf_tl_group_comment`
            WHERE `content_type` = ? AND `content_id` = ?
        ', [$contentType, $contentId]);

        /** @var \XF\Repository\UserAlert $userAlertRepo */
        $userAlertRepo = $this->repository('XF:UserAlert');
        $userAlertRepo->fastDeleteAlertsForContent(App::CONTENT_TYPE_COMMENT, $commentIds);

        /** @var \XF\Repository\Attachment $attachRepo */
        $attachRepo = $this->repository('XF:Attachment');
        $attachRepo->fastDeleteContentAttachments(App::CONTENT_TYPE_COMMENT, $commentIds);

        $db = XF::db();
        $db->delete('xf_tl_group_comment', 'content_type = ? AND content_id = ?', [$contentType, $contentId]);
    }

    /**
     * @return void
     */
    public function rebuildFirstComment()
    {
        /** @var \XF\Db\AbstractAdapter $db */
        $db = $this->db();
        $contentId = $this->getEntityId();

        $this->first_comment_id = $db->fetchOne('
            SELECT comment_id
            FROM xf_tl_group_comment
            WHERE content_type = ?
                AND content_id = ?
                AND parent_id = ?
            ORDER BY comment_date
        ', [
            $this->getCommentContentType(),
            $contentId,
            0
        ]);
    }

    /**
     * @return void
     */
    public function rebuildCounters()
    {
        /** @var \XF\Db\AbstractAdapter $db */
        $db = $this->db();
        $contentId = $this->getEntityId();

        $latestCommentIds = $db->fetchAllColumn($db->limit('
            SELECT `comment_id`
            FROM `xf_tl_group_comment`
            WHERE `content_type` = ? 
                AND `content_id` = ? 
                AND `parent_id` = ?
                AND `message_state` = ? 
                AND `comment_id` <> ?
            ORDER BY `comment_date` DESC
        ', $this->getMaxLastCommentIds()), [
            $this->getCommentContentType(), $contentId, 0, 'visible', $this->first_comment_id
        ]);
        $latestCommentIds = array_reverse($latestCommentIds, false);
        $this->latest_comment_ids = $latestCommentIds;

        $this->comment_count = $db->fetchOne('
            SELECT COUNT(*)
            FROM `xf_tl_group_comment`
            WHERE `content_type` = ? 
                AND `content_id` = ? 
                AND `parent_id` = ? 
                AND `message_state` = ? 
                AND `comment_id` <> ?
        ', [
            $this->getCommentContentType(), $contentId, 0, 'visible', $this->first_comment_id
        ]);

        /** @var mixed $rebuildInternal */
        $rebuildInternal = [$this, 'rebuildCountersInternal'];
        if (is_callable($rebuildInternal)) {
            call_user_func($rebuildInternal);
        }
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUploadAndManageAttachments(& $error = null): bool
    {
        if ($this->Group === null) {
            return false;
        }

        /** @var Member|null $member */
        $member = $this->Group->Member;
        if ($member === null) {
            return false;
        }

        return $member->isValidMember()
            && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'uploadAttachment');
    }

    /**
     * @param AbstractCollection $latestComments
     * @return void
     */
    public function setLatestComments(AbstractCollection $latestComments)
    {
        $this->_getterCache['LatestComments'] = $latestComments;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getLatestComments()
    {
        if (isset($this->_getterCache['LatestComments'])) {
            return $this->_getterCache['LatestComments'];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasMoreComments()
    {
        return $this->comment_count > $this->getMaxLastCommentIds();
    }

    /**
     * @return int
     */
    protected function getMaxLastCommentIds()
    {
        return 3;
    }

    /**
     * @param Structure $structure
     * @return void
     */
    public static function addCommentStructureElements(Structure $structure)
    {
        $structure->columns += [
            'group_id' => ['type' => Entity::UINT, 'required' => true, 'api' => true, 'writeOnce' => true],
            'user_id' => ['type' => Entity::UINT, 'required' => true, 'api' => true],
            'username' => ['type' => Entity::STR, 'maxLength' => 50, 'required' => true, 'api' => true],

            'first_comment_id' => ['type' => Entity::UINT, 'default' => 0, 'api' => true],
            'latest_comment_ids' => ['type' => Entity::JSON_ARRAY, 'default' => []],

            'last_comment_date' => ['type' => Entity::UINT, 'default' => 0, 'api' => true],
            'comment_count' => ['type' => Entity::UINT, 'forced' => true, 'default' => 0, 'api' => true],
        ];

        $structure->getters['LatestComments'] = [
            'cache' => false,
            'getter' => true
        ];
        $structure->behaviors['Truonglv\Groups:Activity'] = [];

        $structure->relations += [
            'User' => [
                'type' => Entity::TO_ONE,
                'entity' => 'XF:User',
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Group' => [
                'type' => Entity::TO_ONE,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => 'group_id',
                'primary' => true
            ],
            'FirstComment' => [
                'type' => Entity::TO_ONE,
                'entity' => 'Truonglv\Groups:Comment',
                'conditions' => [
                    ['comment_id', '=', '$first_comment_id']
                ],
                'primary' => true
            ]
        ];
    }
}
