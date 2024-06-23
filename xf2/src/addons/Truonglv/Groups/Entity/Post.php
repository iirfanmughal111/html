<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\ArrayCollection;

/**
 * COLUMNS
 * @property int|null $post_id
 * @property int $post_date
 * @property bool $sticky
 * @property int $group_id
 * @property int $user_id
 * @property string $username
 * @property int $first_comment_id
 * @property array $latest_comment_ids
 * @property int $last_comment_date
 * @property int $comment_count
 *
 * GETTERS
 * @property ArrayCollection|null $LatestComments
 * @property string $message_preview
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \Truonglv\Groups\Entity\Group $Group
 * @property \Truonglv\Groups\Entity\Comment $FirstComment
 */
class Post extends Entity
{
    use CommentableTrait;

    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        /** @var Group|null $group */
        $group = $this->Group;
        if ($group === null) {
            return false;
        }

        return $group->canViewContent($error);
    }

    /**
     * @return bool
     */
    public function isIgnored()
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        return XF::visitor()->isIgnoring($this->user_id);
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canEdit(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if ($this->Group === null) {
            return false;
        }

        if (App::hasPermission('editCommentAny')) {
            return true;
        }

        if ($this->Group->Member === null) {
            return false;
        }

        if ($this->Group->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'editAny')) {
            return true;
        }

        return $visitor->user_id === $this->user_id
            && $this->Group->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'editOwn');
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canDelete(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if ($this->Group === null) {
            return false;
        }

        if (App::hasPermission('deleteCommentAny')) {
            return true;
        }

        if ($this->Group->Member === null) {
            return false;
        }

        if ($this->Group->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'deleteAny')) {
            return true;
        }

        return $visitor->user_id === $this->user_id
            && $this->Group->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'deleteOwn');
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canStickUnstick(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        $member = $this->Group !== null ? $this->Group->getMember() : null;

        return $member !== null && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'stickUnstickPost');
    }

    /**
     * @return string|null
     */
    public function getMetadataShareImage()
    {
        $router = $this->app()->router('public');
        if ($this->FirstComment !== null && $this->FirstComment->attach_count > 0) {
            foreach ($this->FirstComment->Attachments as $attachment) {
                if ($attachment->Data !== null && (
                    $attachment->Data->thumbnail_height > 0
                        || $attachment->Data->thumbnail_width > 0
                )
                ) {
                    return $router->buildLink('canonical:attachments', $attachment);
                }
            }
        }

        /** @var Group $group */
        $group = $this->Group;
        if ($group->AvatarAttachment !== null) {
            return $router->buildLink('canonical:attachments', $group->AvatarAttachment);
        }

        if ($this->User !== null && $this->User->avatar_date > 0) {
            if ($this->User->avatar_highdpi) {
                return $this->User->getAvatarUrl('h', null, true);
            }

            return $this->User->getAvatarUrl('l', null, true);
        }

        return null;
    }

    /**
     * @param int $length
     * @return string
     */
    public function getMetadataTitle($length = 75)
    {
        /** @var Comment $comment */
        $comment = $this->FirstComment;
        $message = $comment->message;

        $message = $this->app()->stringFormatter()->stripBbCode($message, [
            'stripQuote' => true
        ]);
        $message = \str_replace("\n", ' ', $message);

        return $this->app()->stringFormatter()->wholeWordTrim($message, $length, 0, '');
    }

    /**
     * @return string
     */
    public function getMessagePreview()
    {
        $limit = App::getOption('newsFeedPreviewMaxLength');
        /** @var Comment $comment */
        $comment = $this->FirstComment;
        if ($limit <= 0) {
            return $comment->message;
        }

        $stringFormatter = $this->app()->stringFormatter();
        $message = $comment->message;
        if (utf8_strlen($message) <= $limit) {
            return $message;
        }
        preg_match('#\[(ATTACH|IMG|MEDIA)(.*?)\].+\[/\1\]#i', $message, $matches, PREG_OFFSET_CAPTURE);
        $placeholder = null;

        if (count($matches) > 0) {
            $fullMatch = $matches[0][0];
            $hash = md5($fullMatch . $this->post_id . time());
            $placeholder = [
                $hash,
                $fullMatch
            ];
            $message = substr($message, 0, $matches[0][1])
                . $placeholder[0]
                . substr($message, $matches[0][1] + strlen($fullMatch));
        }

        $prepared = $stringFormatter->stripBbCode($message);
        if ($placeholder !== null) {
            $prepared = str_replace($placeholder[0], $placeholder[1], $prepared);
        }

        return $stringFormatter->wholeWordTrim($prepared, $limit, 0);
    }

    public function getNewComment(): Comment
    {
        /** @var Comment $comment */
        $comment = $this->em()->create('Truonglv\Groups:Comment');
        $comment->content_type = $this->getCommentContentType();
        $comment->content_id = $this->_getDeferredValue(function () {
            return $this->post_id;
        }, 'save');

        return $comment;
    }

    /**
     * @param \XF\Api\Result\EntityResult $result
     * @param int $verbosity
     * @param array $options
     * @return void
     */
    protected function setupApiResultData(
        \XF\Api\Result\EntityResult $result,
        $verbosity = self::VERBOSITY_NORMAL,
        array $options = []
    ) {
        if ($this->FirstComment !== null) {
            $this->FirstComment->setContent($this);
        }

        $result->includeRelation('FirstComment');
        $result->includeRelation('User');
        $result->includeRelation('Group');

        if (($verbosity & self::VERBOSITY_VERBOSE) > 0) {
            // workaround with problem XF does not transform ArrayCollection of getter
            $latestComments = $this->getLatestComments();
            if ($latestComments !== null) {
                $result->LatestComments = $latestComments->toApiResults(self::VERBOSITY_VERBOSE);
            } else {
                $result->LatestComments = [];
            }
        }

        $result->can_comment = $this->canComment();
        $result->can_delete = $this->canDelete();
        $result->can_edit = $this->canEdit();

        $result->can_react = $this->FirstComment !== null && $this->FirstComment->canReact();
        $result->is_ignored = $this->FirstComment !== null && $this->FirstComment->isIgnored();

        $router = $this->app()->router('public');
        $result->view_url = $router->buildLink('full:group-posts', $this);
    }

    /**
     * @return string
     */
    public function getCommentContentType()
    {
        return 'post';
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_post';
        $structure->primaryKey = 'post_id';
        $structure->shortName = 'Truonglv\Groups:Post';
        $structure->contentType = 'tl_group_post';

        $structure->columns = [
            'post_id' => ['type' => self::UINT, 'nullable' => true, 'autoIncrement' => true],
            'post_date' => ['type' => self::UINT, 'default' => XF::$time, 'api' => true],
            'sticky' => ['type' => self::BOOL, 'default' => false, 'api' => true]
        ];

        static::addCommentStructureElements($structure);

        $structure->getters['message_preview'] = true;

        $structure->withAliases = [
            'full' => [
                'User',
                'User.Profile',
                'User.Privacy',
                'Group',
                'Group.full',
            ],
            'api' => [
                'User',
                'User.Profile',
                'User.Privacy',
                'User.api',
                'Group',
                'Group.Feature',
                'Group.Category'
            ]
        ];

        return $structure;
    }

    protected function _postDelete()
    {
        $this->deleteAllComments('post', $this->post_id);
    }
}
