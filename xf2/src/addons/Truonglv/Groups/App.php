<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups;

use XF;
use Exception;
use LogicException;
use XF\Entity\User;
use function intval;
use XF\Entity\Forum;
use XF\Entity\Thread;
use XFMG\Entity\Album;
use XF\Repository\AddOn;
use XF\Mvc\Entity\Entity;
use XF\Repository\UserAlert;
use InvalidArgumentException;
use Truonglv\Groups\Util\Arr;
use XF\Repository\Attachment;
use Truonglv\Groups\Data\NodeTree;
use Truonglv\Groups\Repository\Log;
use Truonglv\Groups\Data\Navigation;
use Truonglv\Groups\Repository\Post;
use Truonglv\Groups\Entity\UserCache;
use Truonglv\Groups\Repository\Event;
use Truonglv\Groups\Repository\Field;
use Truonglv\Groups\Repository\Group;
use Truonglv\Groups\Repository\Member;
use Truonglv\Groups\Repository\Comment;
use Truonglv\Groups\Repository\Category;
use Truonglv\Groups\Service\Group\Logger;
use Truonglv\Groups\XF\Entity\UserOption;
use Truonglv\Groups\Option\GroupNodeCache;
use Truonglv\Groups\Repository\MemberRole;
use Truonglv\Groups\Repository\ResourceItem;

class App
{
    const ADDON_ID = 'Truonglv/Groups';
    const PERMISSION_GROUP = 'tl_groups';
    const PERMISSION_ADMIN_MANAGE_GROUPS = 'manageGroups';

    const CONTENT_TYPE_GROUP = 'tl_group';
    const CONTENT_TYPE_MEMBER = 'tl_group_member';
    const CONTENT_TYPE_EVENT = 'tl_group_event';
    const CONTENT_TYPE_COMMENT = 'tl_group_comment';
    const CONTENT_TYPE_POST = 'tl_group_post';
    const CONTENT_TYPE_RESOURCE = 'tl_group_resource';

    const NODE_TYPE_ID = 'Forum';

    // state constants
    const STATE_VISIBLE = 'visible';
    const STATE_MODERATED = 'moderated';
    const STATE_DELETED = 'deleted';

    const FIELD_REGISTRY_KEY_NAME = 'tl_group_fields';
    const CONTAINER_KEY_CUSTOM_FIELDS = 'customFields.tl_groups';
    const CONTAINER_KEY_MEMBER_ROLE = 'tl_group.memberRole';

    const MEMBER_ROLE_PERM_KEY_COMMENT = 'comment';
    const MEMBER_ROLE_PERM_KEY_EVENT = 'event';
    const MEMBER_ROLE_PERM_KEY_FORUM = 'forum';
    const MEMBER_ROLE_PERM_KEY_GROUP = 'group';
    const MEMBER_ROLE_PERM_KEY_MEMBER = 'member';
    const MEMBER_ROLE_PERM_KEY_THREAD = 'thread';
    const MEMBER_ROLE_PERM_KEY_RESOURCE = 'resource';
    const MEMBER_ROLE_PERM_KEY_MEDIA = 'media';

    const OPTION_MANUAL_REBUILD_PERMISSION = 'optionManualRebuildPermissions';

    const KEY_PAGE_PARAMS_GROUP = 'tlg_group';

    /**
     * Users can see group and it's content
     */
    const PRIVACY_PUBLIC = 'public';
    /**
     * Users can see group name but not for inside content
     */
    const PRIVACY_CLOSED = 'closed';
    /**
     * Only member belong to group or users has an permission to view group and it's content
     */
    const PRIVACY_SECRET = 'secret';

    const MEMBER_ROLE_ID_ADMIN = 'admin';
    const MEMBER_ROLE_ID_MODERATOR = 'moderator';
    const MEMBER_ROLE_ID_MEMBER = 'member';

    /**
     * The state apply for valid group members
     */
    const MEMBER_STATE_VALID = 'valid';

    /**
     * The state apply for banned group members
     */
    const MEMBER_STATE_BANNED = 'banned';

    /**
     * The state apply for members be invited from other
     */
    const MEMBER_STATE_INVITED = 'invited';

    /**
     * The state apply for members waiting confirmation from group moderator
     */
    const MEMBER_STATE_MODERATED = 'moderated';

    /**
     * Turn off alerts.
     */
    const MEMBER_ALERT_OPT_OFF = 'off';

    /**
     * Receive alerts via email only.
     */
    const MEMBER_ALERT_OPT_EMAIL_ONLY = 'email';

    /**
     * Receive alerts via XenForo alert system.
     */
    const MEMBER_ALERT_OPT_ALERT_ONLY = 'alert';

    /**
     * Receive alerts via both email and notification
     */
    const MEMBER_ALERT_OPT_ALL = 'all';

    /**
     * @var int
     */
    public static $nodeStartOrder = 100000;

    /**
     * @var bool
     */
    public static $isAppendGroupNameIntoNodeTitle = false;

    /**
     * Global value to used bypass permission checking to create albums for visitor.
     *
     * @var null|\Truonglv\Groups\Entity\Group
     */
    public static $createAlbumInGroup = null;

    /**
     * @var array
     */
    private static $cached = [];

    /**
     * @param string $permissionId
     * @param mixed $entity
     * @param User|null $user
     * @return bool
     */
    public static function hasThreadPostPermission(string $permissionId, $entity, User $user = null)
    {
        $user = $user !== null ? $user : XF::visitor();
        if ($user->user_id <= 0) {
            return false;
        }

        /** @var UserCache|null $userCache */
        $userCache = $user->getRelation('TLGUserCache');
        if ($userCache === null) {
            return false;
        }

        if ($entity instanceof Thread) {
            $nodeId = $entity->node_id;
        } elseif ($entity instanceof Forum) {
            $nodeId = $entity->node_id;
        } else {
            $nodeId = intval($entity);
        }

        $groupId = GroupNodeCache::getGroupId($nodeId);
        if ($groupId <= 0) {
            return false;
        }

        $member = isset($userCache->cache_data[$groupId]) ? $userCache->cache_data[$groupId] : null;
        if ($member === null || !isset($member[UserCache::KEY_MEMBER_ROLE_ID])) {
            return false;
        }

        $memberRoles = self::memberRoleRepo()->getAllMemberRoles();

        /** @var \Truonglv\Groups\Entity\MemberRole|null $memberRoleEntity */
        $memberRoleEntity = isset($memberRoles[$member[UserCache::KEY_MEMBER_ROLE_ID]])
            ? $memberRoles[$member[UserCache::KEY_MEMBER_ROLE_ID]]
            : null;

        return $memberRoleEntity !== null
            && $memberRoleEntity->hasRole(self::MEMBER_ROLE_PERM_KEY_THREAD, $permissionId);
    }

    /**
     * @param string|Exception $error
     * @param string $type
     * @return void
     */
    public static function logError($error, $type = 'error')
    {
        XF::logException(
            ($error instanceof Exception) ? $error : new Exception($error),
            false,
            "[tl] Social Groups: [{$type}] - "
        );
    }

    /**
     * @param int $destVersion
     * @param string $method
     * @param string|null $replaceMethod
     * @return void
     */
    public static function deprecated($destVersion, $method, $replaceMethod = null)
    {
        $addOns = XF::app()->registry()->get('addOns');
        if ($addOns['Truonglv/Groups'] >= $destVersion) {
            throw new LogicException('Must be remove deprecated method (' . $method . ')');
        }

        $suggestionMessage = '';
        if ($replaceMethod !== null) {
            $suggestionMessage = 'Recommendation to use there methods: ' . $replaceMethod;
        }

        XF::logError(sprintf(
            '[tl] Social Groups: Method (%s) is deprecated and remove in version (%d)%s',
            $method,
            $destVersion,
            $suggestionMessage
        ));
    }

    /**
     * @param User $receiver
     * @param int $senderId
     * @param string $senderName
     * @param string $contentType
     * @param int $contentId
     * @param string $action
     * @param array $extra
     * @return bool
     */
    public static function alert(
        User $receiver,
        $senderId,
        $senderName,
        $contentType,
        $contentId,
        $action,
        array $extra = []
    ) {
        /** @var UserAlert $userAlertRepo */
        $userAlertRepo = XF::repository('XF:UserAlert');
        $extra['depends_on_addon_id'] = 'Truonglv/Groups';

        return $userAlertRepo->alert($receiver, $senderId, $senderName, $contentType, $contentId, $action, $extra);
    }

    public static function getGroupEntityFromEntity(?Entity $entity = null): ?\Truonglv\Groups\Entity\Group
    {
        /** @var mixed $callable*/
        $callable = [$entity, 'getTLGGroup'];
        if (is_callable($callable)) {
            /** @var \Truonglv\Groups\Entity\Group|null $group */
            $group = call_user_func($callable);
            if ($group !== null) {
                return $group;
            }
        }

        if ($entity instanceof Thread) {
            return self::getGroupEntityFromEntity($entity->Forum);
        } elseif ($entity instanceof Forum) {
            if (!self::isEnabledForums()) {
                return null;
            }

            /** @var \Truonglv\Groups\Entity\Forum|null $mixed */
            $mixed = $entity->getRelation('GroupForum');

            return $mixed !== null ? $mixed->Group : null;
        } elseif ($entity instanceof Album) {
            if (!self::isEnabledXenMediaAddOn()) {
                return null;
            }

            /** @var \Truonglv\Groups\XFMG\Entity\Album $mixed */
            $mixed = $entity;
            /** @var \Truonglv\Groups\Entity\Album|null $groupAlbum */
            $groupAlbum = $mixed->getRelation('GroupAlbum');
            if ($groupAlbum === null) {
                return null;
            }

            return $groupAlbum->Group;
        }

        return null;
    }

    /**
     * @param Entity|mixed $entity
     * @return int
     */
    public static function getGroupIdFromEntity($entity)
    {
        $group = self::getGroupEntityFromEntity($entity);

        return $group !== null ? $group->group_id : 0;
    }

    /**
     * @return array
     */
    public static function getAllowedPrivacy()
    {
        return [self::PRIVACY_PUBLIC, self::PRIVACY_CLOSED, self::PRIVACY_SECRET];
    }

    /**
     * @return array
     */
    public static function getAllowedMemberStates()
    {
        return [
            self::MEMBER_STATE_VALID,
            self::MEMBER_STATE_MODERATED,
            self::MEMBER_STATE_INVITED,
            self::MEMBER_STATE_BANNED
        ];
    }

    /**
     * @return array
     */
    public static function getAllowedStates()
    {
        return [self::STATE_VISIBLE, self::STATE_MODERATED, self::STATE_DELETED];
    }

    /**
     * @return bool
     */
    public static function isEnabledXenMediaAddOn()
    {
        $enableMedia = (int) self::getOption('enableMedia');
        if ($enableMedia <= 0) {
            return false;
        }

        /** @var AddOn $addOnRepo */
        $addOnRepo = XF::repository('XF:AddOn');
        $enabledAddOns = $addOnRepo->getEnabledAddOns();

        return isset($enabledAddOns['XFMG']);
    }

    /**
     * @return bool
     */
    public static function isEnabledLanguage()
    {
        return self::getOption('enableLanguage') == 1;
    }

    /**
     * @return bool
     */
    public static function isEnabledForums()
    {
        $enabled = (int) self::getOption('enableForums');

        return $enabled === 1;
    }

    /**
     * @return bool
     */
    public static function isEnabledResources()
    {
        return self::getOption('enableResources') == 1;
    }

    /**
     * @return bool
     */
    public static function isEnabledEvents()
    {
        return self::getOption('enableEvents') == 1;
    }

    public static function isEnabledBadge(User $user): bool
    {
        $enableBadge = XF::app()->options()->tl_groups_enableBadge > 0;
        if (!$enableBadge || $user->user_id <= 0) {
            return false;
        }

        /** @var UserOption $option */
        $option = $user->Option;
        if (!$option->tlg_show_badge) {
            return false;
        }

        return true;
    }

    /**
     * @param string $permission
     * @param User|null $user
     * @param string $group
     * @return bool
     */
    public static function hasPermission($permission, User $user = null, $group = self::PERMISSION_GROUP)
    {
        $user = $user !== null ? $user : XF::visitor();

        return $user->hasPermission($group, $permission);
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param string $contentType
     * @param int $contentId
     * @param string $action
     * @param array $extra
     * @return \Truonglv\Groups\Entity\Log
     * @throws \XF\PrintableException
     */
    public static function logAction(
        \Truonglv\Groups\Entity\Group $group,
        $contentType,
        $contentId,
        $action,
        array $extra = []
    ) {
        /** @var Logger $logger */
        $logger = XF::service('Truonglv\Groups:Group\Logger', $group);

        return $logger->log($contentType, $contentId, $action, $extra);
    }

    /**
     * @return array
     */
    public static function getAllowedAlertOptions()
    {
        return [
            self::MEMBER_ALERT_OPT_OFF,
            self::MEMBER_ALERT_OPT_EMAIL_ONLY,
            self::MEMBER_ALERT_OPT_ALERT_ONLY,
            self::MEMBER_ALERT_OPT_ALL
        ];
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public static function getOption($name)
    {
        $options = XF::options();
        $name = sprintf('%s_%s', self::PERMISSION_GROUP, $name);

        if (!$options->offsetExists($name)) {
            throw new InvalidArgumentException(sprintf(
                'Option (%s) does not exists.',
                $name
            ));
        }

        $value = $options->offsetGet($name);
        if (is_array($value)) {
            $args = func_get_args();
            array_shift($args);

            if (count($args) > 0) {
                $value = Arr::get($value, implode('.', $args));
                if ($value === null) {
                    throw new InvalidArgumentException(sprintf(
                        'Sub-option (%s) does not exists.',
                        implode('.', $args)
                    ));
                }
            }
        }

        return $value;
    }

    /**
     * @return Attachment
     */
    public static function attachmentRepo()
    {
        /** @var Attachment $attachmentRepo */
        $attachmentRepo = XF::repository('XF:Attachment');

        return $attachmentRepo;
    }

    /**
     * @return \Truonglv\Groups\Repository\Category
     */
    public static function categoryRepo()
    {
        /** @var Category $categoryRepo */
        $categoryRepo = XF::repository('Truonglv\Groups:Category');

        return $categoryRepo;
    }

    /**
     * @return \Truonglv\Groups\Repository\Member
     */
    public static function memberRepo()
    {
        /** @var Member $memberRepo */
        $memberRepo = XF::repository('Truonglv\Groups:Member');

        return $memberRepo;
    }

    /**
     * @return \Truonglv\Groups\Repository\MemberRole
     */
    public static function memberRoleRepo()
    {
        /** @var MemberRole $memberRoleRepo */
        $memberRoleRepo = XF::repository('Truonglv\Groups:MemberRole');

        return $memberRoleRepo;
    }

    /**
     * @return \Truonglv\Groups\Repository\Group
     */
    public static function groupRepo()
    {
        /** @var Group $groupRepo */
        $groupRepo = XF::repository('Truonglv\Groups:Group');

        return $groupRepo;
    }

    /**
     * @return \Truonglv\Groups\Repository\Event
     */
    public static function eventRepo()
    {
        /** @var Event $eventRepo */
        $eventRepo = XF::repository('Truonglv\Groups:Event');

        return $eventRepo;
    }

    /**
     * @return \Truonglv\Groups\Repository\Comment
     */
    public static function commentRepo()
    {
        /** @var Comment $commentRepo */
        $commentRepo = XF::repository('Truonglv\Groups:Comment');

        return $commentRepo;
    }

    /**
     * @return \Truonglv\Groups\Repository\Field
     */
    public static function fieldRepo()
    {
        /** @var Field $fieldRepo */
        $fieldRepo = XF::repository('Truonglv\Groups:Field');

        return $fieldRepo;
    }

    /**
     * @return Post
     */
    public static function postRepo()
    {
        /** @var Post $postRepo */
        $postRepo = XF::repository('Truonglv\Groups:Post');

        return $postRepo;
    }

    /**
     * @return Log
     */
    public static function logRepo()
    {
        /** @var Log $logRepo */
        $logRepo = XF::repository('Truonglv\Groups:Log');

        return $logRepo;
    }

    /**
     * @return ResourceItem
     */
    public static function resourceRepo()
    {
        /** @var ResourceItem $repo */
        $repo = XF::repository('Truonglv\Groups:ResourceItem');

        return $repo;
    }

    // Finders

    /**
     * @return \Truonglv\Groups\Finder\Group
     */
    public static function groupFinder()
    {
        /** @var \Truonglv\Groups\Finder\Group $groupFinder */
        $groupFinder = XF::finder('Truonglv\Groups:Group');

        return $groupFinder;
    }

    /**
     * @return \Truonglv\Groups\Finder\Comment
     */
    public static function commentFinder()
    {
        /** @var \Truonglv\Groups\Finder\Comment $commentFinder */
        $commentFinder = XF::finder('Truonglv\Groups:Comment');

        return $commentFinder;
    }

    /**
     * @return \Truonglv\Groups\Finder\Event
     */
    public static function eventFinder()
    {
        /** @var \Truonglv\Groups\Finder\Event $eventFinder */
        $eventFinder = XF::finder('Truonglv\Groups:Event');

        return $eventFinder;
    }

    /**
     * @return \Truonglv\Groups\Finder\Member
     */
    public static function memberFinder()
    {
        /** @var \Truonglv\Groups\Finder\Member $memberFinder */
        $memberFinder = XF::finder('Truonglv\Groups:Member');

        return $memberFinder;
    }

    /**
     * @return \XF\Mvc\Entity\Finder
     */
    public static function categoryFinder()
    {
        return XF::finder('Truonglv\Groups:Category');
    }

    /**
     * @return \XF\Mvc\Entity\Finder
     */
    public static function postFinder()
    {
        return XF::finder('Truonglv\Groups:Post');
    }

    // data

    /**
     * @return \Truonglv\Groups\Data\NodeTree
     */
    public static function nodeTreeData()
    {
        /** @var NodeTree $nodeTree */
        $nodeTree = XF::app()->data('Truonglv\Groups:NodeTree');

        return $nodeTree;
    }

    /**
     * @return \Truonglv\Groups\Data\Navigation
     */
    public static function navigationData()
    {
        /** @var Navigation $navigation */
        $navigation = XF::app()->data('Truonglv\Groups:Navigation');

        return $navigation;
    }

    // controller plugin

    /**
     * @param \XF\Mvc\Controller $controller
     * @return \Truonglv\Groups\ControllerPlugin\GroupList
     */
    public static function groupListPlugin(\XF\Mvc\Controller $controller)
    {
        if (!isset(self::$cached['plugin.GroupList'])) {
            self::$cached['plugin.GroupList'] = $controller->plugin('Truonglv\Groups:GroupList');
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::$cached['plugin.GroupList'];
    }

    /**
     * @param \XF\Mvc\Controller $controller
     * @return \Truonglv\Groups\ControllerPlugin\EventList
     */
    public static function eventListPlugin(\XF\Mvc\Controller $controller)
    {
        if (!isset(self::$cached['plugin.EventList'])) {
            self::$cached['plugin.EventList'] = $controller->plugin('Truonglv\Groups:EventList');
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::$cached['plugin.EventList'];
    }

    /**
     * @param \XF\Mvc\Controller $controller
     * @return \Truonglv\Groups\ControllerPlugin\MemberList
     */
    public static function memberListPlugin(\XF\Mvc\Controller $controller)
    {
        if (!isset(self::$cached['plugin.MemberList'])) {
            self::$cached['plugin.MemberList'] = $controller->plugin('Truonglv\Groups:MemberList');
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::$cached['plugin.MemberList'];
    }

    /**
     * @param \XF\Mvc\Controller $controller
     * @return \Truonglv\Groups\ControllerPlugin\Assertion
     */
    public static function assertionPlugin(\XF\Mvc\Controller $controller)
    {
        if (!isset(self::$cached['plugin.assertion'])) {
            self::$cached['plugin.assertion'] = $controller->plugin('Truonglv\Groups:Assertion');
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::$cached['plugin.assertion'];
    }

    /**
     * @param \XF\Mvc\Controller $controller
     * @return \Truonglv\Groups\ControllerPlugin\Assistant
     */
    public static function assistantPlugin(\XF\Mvc\Controller $controller)
    {
        if (!isset(self::$cached['plugin.assistant'])) {
            self::$cached['plugin.assistant'] = $controller->plugin('Truonglv\Groups:Assistant');
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::$cached['plugin.assistant'];
    }
}
