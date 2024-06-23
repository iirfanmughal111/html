<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Structure;
use Truonglv\Groups\XF\Entity\User;
use XF\Entity\AbstractCategoryTree;
use Truonglv\Groups\Job\CategoryDelete;

/**
 * COLUMNS
 * @property int|null $category_id
 * @property string $category_title
 * @property string $description
 * @property array $allow_view_user_group_ids
 * @property array $allow_create_user_group_ids
 * @property int $min_tags
 * @property bool $always_moderate
 * @property int $group_count
 * @property array $field_cache
 * @property string $default_privacy
 * @property array $disabled_navigation_tabs
 * @property string $default_tab
 * @property int $parent_category_id
 * @property int $display_order
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property array $breadcrumb_data
 *
 * GETTERS
 * @property string $title
 *
 * RELATIONS
 * @property \XF\Mvc\Entity\AbstractCollection|\Truonglv\Groups\Entity\Group[] $Groups
 */
class Category extends AbstractCategoryTree
{
    /**
     * @var array
     */
    protected $_viewableDescendants = [];

    /**
     * @return array
     */
    public function getViewableDescendants()
    {
        $userId = XF::visitor()->user_id;
        if (!isset($this->_viewableDescendants[$userId])) {
            $viewable = App::categoryRepo()->getViewableCategories($this);
            $this->_viewableDescendants[$userId] = $viewable->toArray();
        }

        return $this->_viewableDescendants[$userId];
    }

    /**
     * @param array $descendents
     * @param mixed $userId
     * @return void
     */
    public function cacheViewableDescendents(array $descendents, $userId = null)
    {
        if ($userId === null) {
            $userId = XF::visitor()->user_id;
        }

        $this->_viewableDescendants[$userId] = $descendents;
    }

    /**
     * @param string $error
     * @return bool
     */
    public function canView(& $error = '')
    {
        $visitor = XF::visitor();
        if (!$visitor->hasPermission(App::PERMISSION_GROUP, 'view')) {
            return false;
        }

        if (isset($this->allow_view_user_group_ids[0]) && $this->allow_view_user_group_ids[0] === -1) {
            // all users can view this category
            return true;
        }

        return $visitor->isMemberOf($this->allow_view_user_group_ids);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canAddGroup(& $error = null)
    {
        /** @var User $visitor */
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if (!$visitor->canAddGroup($error)) {
            $error = XF::phrase('tlg_you_may_not_add_groups_to_this_category');

            return false;
        }

        if (isset($this->allow_create_user_group_ids[0])
            && $this->allow_create_user_group_ids[0] === -1
        ) {
            return true;
        }

        if (!$visitor->isMemberOf($this->allow_create_user_group_ids)) {
            $error = XF::phrase('tlg_you_may_not_add_groups_to_this_category');

            return false;
        }

        return true;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canAddSecretGroup(& $error = null)
    {
        return $this->canAddGroupType(App::PRIVACY_SECRET, $error);
    }

    /**
     * @param string $privacy
     * @param mixed $error
     * @return bool
     */
    public function canAddGroupType(string $privacy, & $error = null): bool
    {
        if (!in_array($privacy, App::getAllowedPrivacy(), true)) {
            return false;
        }

        /** @var User $visitor */
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0 || !$visitor->canAddGroup($error)) {
            return false;
        }

        if ($privacy === App::PRIVACY_PUBLIC) {
            return true;
        }

        if ($privacy === App::PRIVACY_SECRET) {
            if (!App::hasPermission('addSecretGroups')) {
                $error = XF::phrase('tlg_do_not_have_permission_to_add_secret_group');

                return false;
            }
        } elseif ($privacy === App::PRIVACY_CLOSED) {
            if (!App::hasPermission('addClosedGroups')) {
                $error = XF::phrase('tlg_do_not_have_permission_to_add_closed_group');

                return false;
            }
        }

        return true;
    }

    /**
     * @param Group|null $group
     * @param null $error
     * @return bool
     */
    public function canEditTags(Group $group = null, & $error = null)
    {
        $enableTagging = (bool) $this->app()->options()->enableTagging;
        if (!$enableTagging) {
            return false;
        }

        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if ($group !== null) {
            if ($group->canEdit($error)) {
                return true;
            }
        } else {
            $ownerRole = App::memberRoleRepo()->getCreatorRole();
            if ($ownerRole->hasRole(App::MEMBER_ROLE_PERM_KEY_GROUP, 'edit')) {
                return true;
            }
        }

        return App::hasPermission('editGroupAny');
    }

    /**
     * @param null|string $error
     * @return bool
     */
    public function canUploadAndManageAttachments(& $error = null)
    {
        return XF::visitor()->user_id > 0;
    }

    /**
     * @return array
     */
    public function getCategoryListExtras()
    {
        return [
            'group_count' => $this->group_count
        ];
    }

    /**
     * @return Group
     */
    public function getNewGroup()
    {
        /** @var Group $group */
        $group = $this->em()->create('Truonglv\Groups:Group');
        $group->category_id = $this->category_id;
        if ($this->default_privacy === App::PRIVACY_SECRET) {
            $group->privacy = $this->canAddSecretGroup() ? $this->default_privacy : App::PRIVACY_PUBLIC;
        } else {
            $group->privacy = $this->default_privacy;
        }

        return $group;
    }

    /**
     * @return string
     */
    public function getNewGroupState()
    {
        if (!$this->always_moderate
            || App::hasPermission('bypassModerated')
            || App::hasPermission('approveUnapprove')
        ) {
            return App::STATE_VISIBLE;
        }

        return App::STATE_MODERATED;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->category_title;
    }

    /**
     * @param bool $includeSelf
     * @return array
     */
    public function getBreadcrumbs($includeSelf = true)
    {
        return $this->_getBreadcrumbs($includeSelf, 'public', 'group-categories');
    }

    /**
     * @param Group $group
     * @return void
     */
    public function onGroupAdded(Group $group)
    {
        if ($group->group_state === App::STATE_VISIBLE
            && \in_array($group->privacy, App::groupRepo()->getVisiblePrivacy(), true)
        ) {
            $this->group_count++;
        }
    }

    /**
     * @param Group $group
     * @return void
     */
    public function onGroupUpdated(Group $group)
    {
        $visiblePrivacyList = App::groupRepo()->getVisiblePrivacy();

        $adjust = 0;
        if ($group->isChanged('group_state') || $group->isChanged('privacy')) {
            $visibleChanged = $group->isStateChanged('group_state', App::STATE_VISIBLE);

            if (\in_array($group->privacy, $visiblePrivacyList, true)) {
                if ($visibleChanged === 'enter') {
                    $adjust++;
                } elseif ($visibleChanged === 'leave') {
                    $adjust--;
                }
            }

            if ($group->group_state === App::STATE_VISIBLE) {
                $newPrivacy = $group->privacy;
                $oldPrivacy = $group->getExistingValue('privacy');

                if (\in_array($oldPrivacy, $visiblePrivacyList, true)
                    && !\in_array($newPrivacy, $visiblePrivacyList, true)
                ) {
                    // changed from visible privacy (public, closed) to non-visible privacy (secret)
                    $adjust--;
                } elseif (\in_array($newPrivacy, $visiblePrivacyList, true)
                    && !\in_array($oldPrivacy, $visiblePrivacyList, true)
                ) {
                    // changed from non-visible privacy (secret) to visible privacy (public, closed)
                    $adjust++;
                }
            }
        }

        $this->group_count = $this->group_count + $adjust;
    }

    /**
     * @param Group $group
     * @return void
     */
    public function onGroupDeleted(Group $group)
    {
        if ($group->group_state == App::STATE_VISIBLE
            && \in_array($group->privacy, App::groupRepo()->getVisiblePrivacy(), true)
        ) {
            $this->group_count--;
        }
    }

    /**
     * @return void
     */
    public function rebuildCounters()
    {
        $this->group_count = App::groupFinder()
                ->inCategory($this)
                ->where('group_state', App::STATE_VISIBLE)
                ->where('privacy', App::groupRepo()->getVisiblePrivacy())
                ->total();
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
        $result->can_add_group = $this->canAddGroup();
        $result->can_add_secret_group = $this->canAddSecretGroup();
        $result->can_upload_attachments = $this->canUploadAndManageAttachments();
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_category';
        $structure->shortName = 'Truonglv\Groups:Category';
        $structure->primaryKey = 'category_id';
        $structure->contentType = 'tl_group_category';

        $structure->columns = [
            'category_id' => ['type' => self::UINT, 'nullable' => true, 'autoIncrement' => true, 'api' => true],
            'category_title' => ['type' => self::STR, 'required' => true, 'maxLength' => 150, 'api' => true],
            'description' => ['type' => self::STR, 'maxLength' => 255, 'default' => '', 'api' => true],
            'allow_view_user_group_ids' => ['type' => self::JSON_ARRAY, 'default' => []],
            'allow_create_user_group_ids' => ['type' => self::JSON_ARRAY, 'default' => []],
            'min_tags' => ['type' => self::UINT, 'default' => 0],
            'always_moderate' => ['type' => self::BOOL, 'default' => false],
            'group_count' => ['type' => self::UINT, 'default' => 0, 'forced' => true, 'api' => true],
            'field_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
            'default_privacy' => [
                'type' => self::STR,
                'allowedValues' => App::groupRepo()->getAllowedPrivacy(),
                'default' => App::PRIVACY_PUBLIC,
            ],
            'disabled_navigation_tabs' => ['type' => self::LIST_COMMA, 'default' => []],
            'default_tab' => ['type' => self::STR, 'default' => ''],
        ];

        static::addCategoryTreeStructureElements($structure);

        $structure->getters = [
            'title' => true
        ];

        $structure->behaviors = [
            'XF:TreeStructured' => [
                'parentField' => 'parent_category_id',
                'titleField' => 'title',
                'rebuildService' => 'XF:RebuildNestedSet'
            ]
        ];

        $structure->relations = [
            'Groups' => [
                'type' => self::TO_MANY,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => 'category_id',
                'key' => 'group_id',
                'order' => 'last_activity'
            ]
        ];

        return $structure;
    }

    protected function _postDelete()
    {
        $this->app()
            ->jobManager()
            ->enqueueLater(
                'tl_groups_cat_' . $this->category_id,
                XF::$time,
                CategoryDelete::class,
                ['category_id' => $this->category_id]
            );

        $this->db()->delete(
            'xf_tl_group_category_field',
            'category_id = ?',
            $this->category_id
        );
    }
}
