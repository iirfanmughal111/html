<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use function count;
use LogicException;
use function get_class;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use function array_splice;
use function array_reverse;
use XF\Entity\ReactionTrait;
use XF\Mvc\Entity\Structure;
use InvalidArgumentException;
use function array_key_exists;
use XF\Entity\QuotableInterface;
use XF\Mvc\Entity\AbstractCollection;
use XF\BbCode\RenderableContentInterface;

/**
 * COLUMNS
 * @property int|null $comment_id
 * @property int $user_id
 * @property string $username
 * @property string $message
 * @property string $message_state
 * @property int $content_id
 * @property string $content_type
 * @property int $parent_id
 * @property array $latest_reply_ids
 * @property int $reply_count
 * @property int $comment_date
 * @property array $embed_metadata
 * @property int $attach_count
 * @property int $ip_id
 * @property int $last_edit_date
 * @property int $last_edit_user_id
 * @property int $edit_count
 * @property int $reaction_score
 * @property array $reactions_
 * @property array $reaction_users_
 *
 * GETTERS
 * @property Entity|null $Content
 * @property Group|null $Group
 * @property AbstractCollection|null $LatestReplies
 * @property int $comment_level
 * @property array $Unfurls
 * @property mixed $reactions
 * @property mixed $reaction_users
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \Truonglv\Groups\Entity\Comment $ParentComment
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\Attachment[] $Attachments
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\ReactionContent[] $Reactions
 */
class Comment extends Entity implements RenderableContentInterface, QuotableInterface
{
    use ReactionTrait;

    /**
     * @param mixed $inner
     * @return string
     */
    public function getQuoteWrapper($inner)
    {
        /** @var \XF\Entity\User|null $user */
        $user = $this->User;

        return '[QUOTE="'
            . ($user !== null ? $user->username : $this->username)
            . ', ' . App::CONTENT_TYPE_COMMENT . ': ' . $this->comment_id
            . ($user !== null ? ", member: $this->user_id" : '')
            . '"]'
            . $inner
            . "[/QUOTE]\n";
    }

    /**
     * @param mixed $context
     * @param mixed $type
     * @return array
     */
    public function getBbCodeRenderOptions($context, $type)
    {
        $group = $this->Group;
        $canViewAttachments = ($group !== null) ? $group->canViewAttachments() : false;

        return [
            'entity' => $this,
            'user' => $this->User,
            'attachments' => $this->attach_count > 0 ? $this->Attachments: [],
            'viewAttachments' => $canViewAttachments
        ];
    }

    /**
     * @param null|string $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        $group = $this->getGroup();

        return $group !== null && $group->canView($error);
    }

    /**
     * @param null|string $error
     * @return bool
     */
    public function canReply(& $error = null)
    {
        if (XF::visitor()->user_id <= 0
            || $this->comment_level > 2
        ) {
            return false;
        }
        if ($this->isFirstComment()) {
            return false;
        }

        /** @var mixed $content */
        $content = $this->Content;
        $callable = [$content, 'canComment'];

        if (!\is_callable($callable)) {
            throw new LogicException('Method canComment not exists in ' . get_class($content));
        }

        return (bool) \call_user_func_array($callable, [&$error]);
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canEdit(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        $group = $this->getGroup();
        if ($group === null) {
            return false;
        }

        if ($this->isFirstComment()) {
            /** @var mixed $callable */
            $callable = [$this->Content, 'canEdit'];

            if (is_callable($callable)) {
                return call_user_func_array($callable, [&$error]);
            }

            return false;
        }

        if (App::hasPermission('editCommentAny')) {
            return true;
        }

        $member = $group->getMember();
        if ($member === null) {
            return false;
        }

        if ($member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'editAny')) {
            return true;
        }

        $visitor = XF::visitor();

        return ($visitor->user_id == $this->user_id
            && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'editOwn')
        );
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

        $group = $this->getGroup();
        if ($group === null) {
            return false;
        }

        if ($this->isFirstComment()) {
            /** @var mixed $callable */
            $callable = [$this->Content, 'canDelete'];
            if (is_callable($callable)) {
                return call_user_func_array($callable, [&$error]);
            }

            return false;
        }

        if (App::hasPermission('deleteCommentAny')) {
            return true;
        }

        $member = $group->getMember();
        if ($member === null) {
            return false;
        }

        if ($member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'deleteAny')) {
            return true;
        }

        return ($visitor->user_id == $this->user_id
            && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'deleteOwn')
        );
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canReact(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if ($this->Content === null) {
            return false;
        }

        if ($visitor->user_id === $this->user_id) {
            $error = XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');

            return false;
        }

        return true;
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canReport(& $error = null)
    {
        return XF::visitor()->canReport($error);
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canUseInlineModeration(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        return App::hasPermission('inlineMod');
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
     * @return bool
     */
    public function isVisible()
    {
        return $this->Content !== null;
    }

    /**
     * @param mixed $attachmentId
     * @return bool
     */
    public function isAttachmentEmbedded($attachmentId)
    {
        if (count($this->embed_metadata) === 0) {
            return false;
        }

        if ($attachmentId instanceof \XF\Entity\Attachment) {
            $attachmentId = $attachmentId->attachment_id;
        }

        return isset($this->embed_metadata['attachments'][$attachmentId]);
    }

    /**
     * @return bool
     */
    public function isFirstComment()
    {
        $content = $this->Content;

        return ($content !== null && $content->get('first_comment_id') === $this->comment_id);
    }

    /**
     * @return bool
     */
    public function hasMoreReplies()
    {
        return $this->comment_level === 1 && $this->reply_count > $this->getMaxReplyIdsForCache();
    }

    /**
     * @return Entity|null
     */
    public function getContent()
    {
        if (isset($this->_getterCache['Content'])) {
            return $this->_getterCache['Content'];
        }

        if ($this->parent_id > 0 && $this->ParentComment !== null) {
            return $this->ParentComment->Content;
        }

        switch ($this->content_type) {
            case 'event':
                return $this->em()->find('Truonglv\Groups:Event', $this->content_id);
            case 'post':
                return $this->em()->find('Truonglv\Groups:Post', $this->content_id);
            case 'resource':
                return $this->em()->find('Truonglv\Groups:ResourceItem', $this->content_id);
        }

        throw new LogicException('Must be implement to get Content for: ' . $this->content_type);
    }

    /**
     * @return string
     */
    public function getContentBaseLink()
    {
        switch ($this->content_type) {
            case 'post':
                return 'group-posts';
            case 'event':
                return 'group-events';
            case 'resource':
                return 'group-resources';
            default:
                throw new InvalidArgumentException('Must be implemented!');
        }
    }

    /**
     * @return Group|null
     */
    public function getGroup()
    {
        if (array_key_exists('Group', $this->_getterCache)) {
            return $this->_getterCache['Group'];
        }

        $content = $this->getContent();
        if ($content !== null) {
            if ($content->isValidRelation('Group')) {
                return $content->getRelation('Group');
            }

            throw new LogicException('Cannot get Group entity from Content: ' . get_class($content));
        }

        return null;
    }

    /**
     * @param Group $group
     * @return void
     */
    public function setGroup(Group $group)
    {
        $this->_getterCache['Group'] = $group;
    }

    /**
     * @param Entity|null $content
     * @return void
     */
    public function setContent(Entity $content = null)
    {
        $this->_getterCache['Content'] = $content;
    }

    /**
     * @return int
     */
    public function getCommentLevel()
    {
        return $this->parent_id > 0 ? 2 : 1;
    }

    /**
     * @return AbstractCollection|null
     */
    public function getLatestReplies()
    {
        return isset($this->_getterCache['LatestReplies']) ? $this->_getterCache['LatestReplies'] : null;
    }

    /**
     * @return void
     */
    public function rebuildLatestReplies()
    {
        $db = $this->db();
        $latestReplyIds = $db->fetchAllColumn($db->limit('
            SELECT `comment_id`
            FROM `xf_tl_group_comment`
            WHERE `parent_id` = ?
            ORDER BY `comment_date` DESC
        ', $this->getMaxReplyIdsForCache()), [$this->comment_id]);
        $latestReplyIds = array_reverse($latestReplyIds, false);
        $this->latest_reply_ids = $latestReplyIds;

        $this->reply_count = $db->fetchOne('
            SELECT COUNT(*)
            FROM `xf_tl_group_comment`
            WHERE `parent_id` = ?
        ', [$this->comment_id]);
    }

    /**
     * @param AbstractCollection $replies
     * @return void
     */
    public function setLatestReplies(AbstractCollection $replies)
    {
        $this->_getterCache['LatestReplies'] = $replies;
    }

    /**
     * @return array
     */
    public function getUnfurls()
    {
        return isset($this->_getterCache['Unfurls']) ? $this->_getterCache['Unfurls'] : [];
    }

    /**
     * @param array $unfurls
     * @return void
     */
    public function setUnfurls($unfurls)
    {
        $this->_getterCache['Unfurls'] = $unfurls;
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function onNewReplyInserted(Comment $comment)
    {
        if ($comment->message_state === 'visible') {
            $this->fastUpdate('reply_count', $this->reply_count + 1);
        }
        $latestReplyIds = $this->latest_reply_ids;
        $maxReplies = $this->getMaxReplyIdsForCache();

        $latestReplyIds[] = $comment->comment_id;
        if (count($latestReplyIds) > $maxReplies) {
            array_splice($latestReplyIds, 0, -1 * $maxReplies);
        }

        $this->fastUpdate('latest_reply_ids', $latestReplyIds);
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function onReplyDeleted(Comment $comment)
    {
        $latestReplyIds = $this->db()->fetchAllColumn('
            SELECT `comment_id`
            FROM `xf_tl_group_comment`
            WHERE `parent_id` = ?
            ORDER BY `comment_date` DESC
            LIMIT ' . $this->getMaxReplyIdsForCache() . '
        ', [$this->comment_id]);

        $this->fastUpdate('latest_reply_ids', $latestReplyIds);
        $this->fastUpdate('reply_count', max(0, $this->reply_count - 1));
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUploadAndManageAttachments(& $error = null): bool
    {
        $container = $this->Content;
        if ($container === null) {
            return false;
        }

        /** @var mixed $callable */
        $callable = [$container, 'canUploadAndManageAttachments'];
        if (!is_callable($callable)) {
            return false;
        }

        return call_user_func_array($callable, [&$error]);
    }

    public function getAttachmentEditorData(): ?array
    {
        if (!$this->canUploadAndManageAttachments()) {
            return null;
        }

        /** @var \XF\Repository\Attachment $attachmentRepo */
        $attachmentRepo = $this->repository('XF:Attachment');

        return $attachmentRepo->getEditorData(
            App::CONTENT_TYPE_COMMENT,
            $this->comment_id > 0 ? $this : $this->Group
        );
    }

    /**
     * @return int
     */
    protected function getMaxReplyIdsForCache()
    {
        return 3;
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
        $result->includeRelation('User');
        if ($this->attach_count > 0) {
            $result->includeRelation('Attachments');
        }

        if (($verbosity & self::VERBOSITY_VERBOSE) > 0) {
            // workaround with problem XF does not transform ArrayCollection of getter
            $latestReplies = $this->getLatestReplies();
            if ($latestReplies !== null) {
                $result->LatestReplies = $latestReplies->toApiResults(self::VERBOSITY_VERBOSE);
            } else {
                $result->LatestReplies = [];
            }
        }

        $bbCode = $this->app()->bbCode();
        $result->message_parsed = $bbCode->render(
            $this->message,
            'apiHtml',
            App::CONTENT_TYPE_COMMENT . ':api',
            $this
        );

        $stringFormatter = $this->app()->stringFormatter();
        $messagePlainText = $stringFormatter->stripBbCode($this->message, [
            'stripQuote' => true
        ]);
        $result->message_plain_text = $messagePlainText;

        $result->is_ignored = $this->isIgnored();
        $result->can_report = $this->canReport();
        $result->can_react = $this->canReact();
        $result->can_delete = $this->canDelete();
        $result->can_edit = $this->canEdit();
        $result->can_reply = $this->canReply();
        $result->comment_level = $this->getCommentLevel();

        // has more replies.
        $result->has_more_replies = $this->hasMoreReplies();

        $this->addReactionStateToApiResult($result);

        $router = $this->app()->router('public');
        $result->view_url = $router->buildLink('canonical:group-comments', $this);
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_comment';
        $structure->primaryKey = 'comment_id';
        $structure->shortName = 'Truonglv\Groups:Comment';
        $structure->contentType = App::CONTENT_TYPE_COMMENT;

        $structure->columns = [
            'comment_id' => ['type' => self::UINT, 'nullable' => true, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'required' => true, 'api' => true],
            'username' => ['type' => self::STR, 'maxLength' => 50, 'required' => true, 'api' => true],
            'message' => ['type' => self::STR, 'required' => true, 'api' => true],

            'message_state' => ['type' => self::STR, 'allowedValues' => ['visible', 'deleted', 'moderated'],
                'default' => 'visible'],
            'content_id' => ['type' => self::UINT, 'required' => true, 'api' => true, 'writeOnce' => true],
            'content_type' => ['type' => self::STR, 'maxLength' => 25, 'required' => true,
                'api' => true, 'writeOnce' => true],
            'parent_id' => ['type' => self::UINT, 'default' => 0, 'writeOnce' => true, 'api' => true],
            'latest_reply_ids' => ['type' => self::JSON_ARRAY, 'default' => []],
            'reply_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true],

            'comment_date' => ['type' => self::UINT, 'default' => XF::$time, 'api' => true],
            'embed_metadata' => ['type' => self::JSON_ARRAY, 'default' => [], 'api' => true],
            'attach_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true],
            'ip_id' => ['type' => self::UINT, 'default' => 0],
            'last_edit_date' => ['type' => self::UINT, 'default' => 0, 'api' => true],
            'last_edit_user_id' => ['type' => self::UINT, 'default' => 0, 'api' => true],
            'edit_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true]
        ];

        $structure->getters = [
            'Content' => [
                'cache' => false,
                'getter' => true
            ],
            'Group' => false,
            'LatestReplies' => [
                'cache' => false,
                'getter' => true
            ],
            'comment_level' => true,
            'Unfurls' => true
        ];

        $structure->behaviors = [
            'XF:Reactable' => ['stateField' => 'message_state'],
            'XF:Indexable' => [
                'checkForUpdates' => ['message', 'user_id', 'event_id', 'message_state']
            ],
            'Truonglv\Groups:Activity' => [
                'stateField' => 'message_state',
                'groupIdField' => function (Comment $comment) {
                    $group = $comment->Group;

                    return $group !== null ? $group->group_id : 0;
                }
            ]
        ];

        $structure->relations = [
            'User' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:User',
                'conditions' => 'user_id',
                'primary' => true
            ],
            'ParentComment' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Comment',
                'conditions' => [
                    ['comment_id', '=', '$parent_id']
                ],
                'primary' => true
            ],
            'Attachments' => [
                'type' => self::TO_MANY,
                'entity' => 'XF:Attachment',
                'conditions' => [
                    ['content_type', '=', App::CONTENT_TYPE_COMMENT],
                    ['content_id', '=', '$comment_id']
                ],
                'with' => 'Data',
                'order' => 'attach_date'
            ]
        ];

        $structure->withAliases = [
            'full' => [
                'User',
                'User.Profile',
                'User.Privacy',
                function () {
                    if (XF::options()->showMessageOnlineStatus) {
                        return 'User.Activity';
                    }

                    return null;
                },
                function () {
                    $userId = XF::visitor()->user_id;
                    if ($userId > 0) {
                        return [
                            'Reactions|' . $userId
                        ];
                    }

                    return null;
                }
            ],
            'api' => [
                'User',
                'User.api',
                'User.Profile'
            ]
        ];

        static::addReactableStructureElements($structure);

        return $structure;
    }

    protected function _preSave()
    {
        if ($this->isUpdate() && $this->isChanged('parent_id')) {
            throw new LogicException('Unsupported to change `parent_id`');
        }
    }

    protected function _postSave()
    {
        if ($this->isInsert()) {
            if ($this->parent_id === 0) {
                /** @var mixed $content */
                $content = $this->getContent();
                $callable = [$content, 'onCommentCreated'];

                if (\is_callable($callable)) {
                    $content->onCommentCreated($this);
                    $content->saveIfChanged();
                }
            } else {
                /** @var static $parent */
                $parent = $this->ParentComment;
                $parent->onNewReplyInserted($this);
            }
        }
    }

    protected function _postDelete()
    {
        if ($this->parent_id === 0) {
            /** @var mixed $content */
            $content = $this->getContent();
            $callable = [$content, 'onCommentDeleted'];
            if (\is_callable($callable)) {
                /** @var Comment|null $lastComment */
                $lastComment = $this->finder('Truonglv\Groups:Comment')
                    ->where('content_type', $this->content_type)
                    ->where('content_id', $this->content_id)
                    ->order('comment_date', 'DESC')
                    ->fetchOne();

                $content->onCommentDeleted($this, $lastComment);
                $content->saveIfChanged();
            }
        } else {
            /** @var static|null $parent */
            $parent = $this->ParentComment;
            if ($parent !== null) {
                $parent->onReplyDeleted($this);
            }
        }

        /** @var \XF\Repository\UserAlert $userAlertRepo */
        $userAlertRepo = $this->repository('XF:UserAlert');
        $userAlertRepo->fastDeleteAlertsForContent(App::CONTENT_TYPE_COMMENT, $this->comment_id);

        /** @var \XF\Repository\Attachment $attachRepo */
        $attachRepo = $this->repository('XF:Attachment');
        $attachRepo->fastDeleteContentAttachments(App::CONTENT_TYPE_COMMENT, $this->comment_id);
    }
}
