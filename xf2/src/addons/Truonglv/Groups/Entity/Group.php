<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use function max;
use XF\Entity\User;
use function strlen;
use function in_array;
use Truonglv\Groups\App;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;
use function array_column;
use XF\Entity\DeletionLog;
use function array_replace;
use XF\Entity\ApprovalQueue;
use XF\Mvc\Entity\Structure;
use XF\Entity\Forum as XFForum;
use XF\Mvc\Entity\AbstractCollection;
use Truonglv\Groups\Service\Group\Cover;
use Truonglv\Groups\Option\GroupNodeCache;

/**
 * COLUMNS
 * @property int|null $group_id
 * @property string $name
 * @property string $short_description
 * @property string $description
 * @property int $category_id
 * @property int $owner_user_id
 * @property string $owner_username
 * @property string $privacy
 * @property string $group_state
 * @property int $created_date
 * @property int $avatar_attachment_id
 * @property int $cover_attachment_id
 * @property array $tags
 * @property string $language_code
 * @property int $member_count
 * @property int $event_count
 * @property int $discussion_count
 * @property int $view_count
 * @property int $node_count
 * @property array $member_cache
 * @property array $custom_fields_
 * @property int $last_activity
 * @property int $member_moderated_count
 * @property int $album_count
 * @property array $cover_crop_data
 * @property bool $always_moderate_join
 * @property bool $allow_guest_posting
 *
 * GETTERS
 * @property \XF\CustomField\Set $custom_fields
 * @property Member|null $Member
 * @property string|null $language_title
 * @property \XF\Mvc\Entity\AbstractCollection|null $CardMembers
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \Truonglv\Groups\Entity\Category $Category
 * @property \Truonglv\Groups\Entity\Feature $Feature
 * @property \XF\Entity\DeletionLog $DeletionLog
 * @property \XF\Entity\ApprovalQueue $ApprovalQueue
 * @property \Truonglv\Groups\Entity\Member $MemberOwner
 * @property \XF\Mvc\Entity\AbstractCollection|\Truonglv\Groups\Entity\Member[] $Members
 * @property \XF\Mvc\Entity\AbstractCollection|\Truonglv\Groups\Entity\GroupView[] $Views
 * @property \XF\Entity\Attachment $AvatarAttachment
 * @property \XF\Entity\Attachment $CoverAttachment
 * @property \XF\Mvc\Entity\AbstractCollection|\XF\Entity\TagContent[] $Tags
 * @property \XF\Mvc\Entity\AbstractCollection|\Truonglv\Groups\Entity\GroupFieldValue[] $CustomFields
 */
class Group extends Entity implements XF\Entity\LinkableInterface
{
    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        /** @var Category|null $category */
        $category = $this->Category;
        if ($category === null || !$category->canView($error)) {
            return false;
        }

        $visitor = XF::visitor();

        if ($this->group_state === App::STATE_MODERATED) {
            if (!App::hasPermission('viewModerated')
                && ($visitor->user_id <= 0 || $visitor->user_id != $this->owner_user_id)
            ) {
                $error = XF::phrase('tlg_requested_group_not_found');

                return false;
            }
        } elseif ($this->group_state === App::STATE_DELETED) {
            if (!App::hasPermission('viewDeleted')) {
                $error = XF::phrase('tlg_requested_group_not_found');

                return false;
            }
        }

        if (App::hasPermission('bypassViewPrivacy')) {
            return true;
        }

        if ($this->isSecretGroup()) {
            if ($this->Member === null) {
                $error = XF::phrase('tlg_requested_group_not_found');

                return false;
            }
        }

        if ($this->Member !== null && $this->Member->isBanned()) {
            if ($this->Member->ban_end_date == 0) {
                // banned forever
                $error = XF::phrase('tlg_you_have_banned_to_access_this_group');
            } else {
                $error = XF::phrase('tlg_you_have_banned_to_access_this_group_banned_expire_after_x', [
                    'date' => $this->app()->templater()->fn('date', [$this->Member->ban_end_date])
                ]);
            }

            return false;
        }

        return true;
    }

    /**
     * View inside group content. Like threads, events, etc...
     *
     * @param mixed $error
     * @return bool
     */
    public function canViewContent(& $error = null)
    {
        if (!$this->canView($error)) {
            return false;
        }

        if (App::hasPermission('bypassViewPrivacy')) {
            return true;
        }

        if ($this->isClosedGroup() || $this->isSecretGroup()) {
            if ($this->Member !== null && $this->Member->isValidMember()) {
                return true;
            }

            if ($this->isSecretGroup()) {
                $error = XF::phrase('tlg_requested_group_not_found');
            } else {
                $error = XF::phrase('tlg_you_need_become_a_member_of_the_group_x_to_view_the_content', [
                    'title' => $this->name,
                    'url' => $this->app()->router('public')->buildLink('groups', $this)
                ]);
            }

            return false;
        }

        return true;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canViewResources(& $error = null)
    {
        return App::isEnabledResources();
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canViewEvents(& $error = null)
    {
        return App::isEnabledEvents();
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canEditTags(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        /** @var Category|null $category */
        $category = $this->Category;

        return $category !== null && $category->canEditTags($this, $error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canInvitePeople(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        $limit = (int) App::hasPermission('maxInvitesPerDay');
        if ($limit === 0) {
            return false;
        }

        /** @var Member|null $member */
        $member = $this->Member;
        if ($member === null) {
            return false;
        }

        return $member->isValidMember() && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'invite');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canMove(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        return App::hasPermission('move');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canApproveMembers(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if ($this->member_moderated_count < 1) {
            return false;
        }

        if ($this->Member === null) {
            return false;
        }

        return $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'approve');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canApproveUnapprove(& $error = null)
    {
        if ($this->group_state === App::STATE_DELETED) {
            return false;
        }

        return XF::visitor()->user_id > 0 && App::hasPermission('approveUnapprove');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canFeatureUnfeature(& $error = null)
    {
        if (!$this->isVisible()) {
            return false;
        }

        return XF::visitor()->user_id > 0 && App::hasPermission('featureUnfeature');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUseInlineModeration(& $error = null)
    {
        return XF::visitor()->user_id > 0 && App::hasPermission('inlineMod');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canEdit(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if (App::hasPermission('editGroupAny')) {
            return true;
        }

        return $this->Member !== null && $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_GROUP, 'edit');
    }

    /**
     * @param string $type
     * @param mixed $error
     * @return bool
     */
    public function canDelete($type = 'soft', & $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        if (App::hasPermission('deleteGroupAny')) {
            return true;
        }

        if ($type === 'soft' && $this->Member !== null) {
            return $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_GROUP, 'delete');
        }

        return false;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUndelete(& $error = null)
    {
        if ($this->group_state !== App::STATE_DELETED) {
            return false;
        }

        return XF::visitor()->user_id > 0 && App::hasPermission('undelete');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canAddForum(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if ($this->node_count >= App::getOption('maxNodes')) {
            return false;
        }

        if ($this->Member === null) {
            return false;
        }

        return $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_FORUM, 'add');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canViewForums(& $error = null)
    {
        return App::isEnabledForums();
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canEditForum(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if ($this->Member === null) {
            return false;
        }

        return $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_FORUM, 'edit');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canDeleteForum(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if ($this->Member === null) {
            return false;
        }

        return $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_FORUM, 'delete');
    }

    /**
     * @param mixed $error
     * @param User|null $asUser
     * @return bool
     */
    public function canReport(& $error = null, User $asUser = null)
    {
        $user = $asUser !== null ? $asUser : XF::visitor();

        return $user->canReport($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canReassign(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        return App::hasPermission('reassign');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUpdatePrivacy(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if (App::hasPermission('editGroupAny')) {
            return true;
        }

        if ($this->Member === null) {
            return false;
        }

        return $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_GROUP, 'privacy');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canManageAvatar(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if (App::hasPermission('editGroupAny')) {
            return true;
        }

        if ($this->Member === null) {
            return false;
        }

        return $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_GROUP, 'avatar');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canManageCover(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if (App::hasPermission('editGroupAny')) {
            return true;
        }

        if ($this->Member === null) {
            return false;
        }

        return $this->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_GROUP, 'cover');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canAddEvent(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if (!$this->canViewEvents($error)) {
            return false;
        }

        $member = $this->Member;

        return $member !== null && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_EVENT, 'add');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canAddResource(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if (!$this->canViewResources($error)) {
            return false;
        }

        $member = $this->Member;

        return $member !== null && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_RESOURCE, 'add');
    }

    /**
     * @param null|string $key
     * @param mixed $default
     * @return array|mixed|null
     */
    public function getCoverCropData($key = null, $default = null)
    {
        if ($key !== null && strlen($key) > 0) {
            return \array_key_exists($key, $this->cover_crop_data) ? $this->cover_crop_data[$key] : $default;
        }

        return array_replace(Cover::getDefaultCropData(), $this->cover_crop_data);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUploadAndManageAttachments(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if ($this->Category === null || !$this->Category->canUploadAndManageAttachments($error)) {
            return false;
        }

        /** @var Member|null $member */
        $member = $this->Member;
        if ($member === null) {
            return false;
        }

        return $member->isValidMember()
            && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'uploadAttachment');
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canViewAttachments(& $error = null)
    {
        if (!$this->isPublicGroup()) {
            if (XF::visitor()->user_id <= 0) {
                return false;
            }

            /** @var Member|null $member */
            $member = $this->Member;

            return ($member !== null && $member->isValidMember());
        }

        return true;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canJoin(& $error = null)
    {
        // join to this group
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        /** @var Member|null $member */
        $member = $this->Member;
        if ($member !== null) {
            $error = XF::phrase('tlg_you_already_in_this_group');

            return false;
        }

        // TODO: Check permission?
        return true;
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canLeave(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        $member = $this->getMember();
        if ($member === null) {
            return false;
        }

        return $member->canLeave($error);
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canAddPost(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        $member = $this->getMember();
        if ($this->isPublicGroup() && $member === null) {
            return !!$this->allow_guest_posting;
        }

        return $member !== null
            && $member->isValidMember()
            && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_COMMENT, 'post');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canMerge(& $error = null)
    {
        return XF::visitor()->user_id > 0 && App::hasPermission('merge');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canAddAlbums(& $error = null): bool
    {
        if (XF::visitor()->user_id <= 0 || !App::isEnabledXenMediaAddOn()) {
            return false;
        }

        /** @var Member|null $member */
        $member = $this->Member;

        return $member !== null && $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEDIA, 'createAlbum');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canLinkAlbums(& $error = null): bool
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        return App::isEnabledXenMediaAddOn()
            && XF::visitor()->hasPermission(App::PERMISSION_GROUP, 'linkAlbums');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUseAsBadge(& $error = null): bool
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0 || $this->app()->options()->tl_groups_enableBadge <= 0) {
            return false;
        }

        return $this->Member !== null && $this->Member->isValidMember();
    }

    public function isEnabledGroupBadge(): bool
    {
        /** @var \Truonglv\Groups\XF\Entity\User $visitor */
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return false;
        }

        return $visitor->tlg_badge_group_id === $this->group_id;
    }

    /**
     * @return bool
     */
    public function isUnread()
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0) {
            return true;
        }

        if (!isset($this->Views[$visitor->user_id])) {
            return true;
        }

        return $this->last_activity > $this->Views[$visitor->user_id]->view_date;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->group_state === App::STATE_VISIBLE;
    }

    /**
     * @return bool
     */
    public function isClosedGroup()
    {
        return $this->privacy === App::PRIVACY_CLOSED;
    }

    /**
     * @return bool
     */
    public function isSecretGroup()
    {
        return $this->privacy === App::PRIVACY_SECRET;
    }

    /**
     * @return bool
     */
    public function isPublicGroup()
    {
        return $this->privacy === App::PRIVACY_PUBLIC;
    }

    /**
     * @return \XF\Phrase
     */
    public function getGroupStatePhrase()
    {
        // @phpstan-ignore-next-line
        return XF::phrase($this->group_state);
    }

    /**
     * @return \Truonglv\Groups\Entity\Event
     */
    public function getNewEvent()
    {
        /** @var Event $event */
        $event = $this->em()->create('Truonglv\Groups:Event');
        $event->group_id = $this->group_id;

        return $event;
    }

    /**
     * @return Member
     */
    public function getNewMember()
    {
        /** @var \Truonglv\Groups\Entity\Member $member */
        $member = $this->em()->create('Truonglv\Groups:Member');

        $member->group_id = $this->_getDeferredValue(function () {
            return $this->group_id;
        }, 'save');

        return $member;
    }

    /**
     * @return bool
     */
    public function isIgnored()
    {
        return XF::visitor()->isIgnoring($this->owner_user_id);
    }

    /**
     * @param bool $canonical
     * @return string|null
     */
    public function getAvatarUrl($canonical = false)
    {
        /** @var Attachment|null $attachment */
        $attachment = $this->AvatarAttachment;
        if ($attachment === null) {
            return null;
        }

        return $this
            ->app()
            ->router('public')
            ->buildLink(($canonical ? 'canonical:' : '') . 'attachments', $attachment);
    }

    /**
     * @param bool $canonical
     * @return string|null
     */
    public function getCoverUrl($canonical = false)
    {
        /** @var Attachment|null $attachment */
        $attachment = $this->CoverAttachment;
        if ($attachment === null) {
            return null;
        }

        return $this
            ->app()
            ->router('public')
            ->buildLink(($canonical ? 'canonical:' : '') . 'attachments', $attachment);
    }

    /**
     * @param bool $includeSelf
     * @return array
     */
    public function getBreadcrumbs($includeSelf = true)
    {
        $breadcrumbs = $this->Category !== null ? $this->Category->getBreadcrumbs() : [];

        if ($includeSelf) {
            $breadcrumbs[] = [
                'value' => $this->name,
                'href' => $this->app()->router('public')->buildLink('canonical:groups', $this)
            ];
        }

        return $breadcrumbs;
    }

    /**
     * @return string
     */
    public function getFieldEditMode()
    {
        $visitor = XF::visitor();

        $isSelf = ($visitor->user_id == $this->owner_user_id || $this->group_id <= 0);
        $isMod = ($visitor->user_id > 0 && App::hasPermission('editGroupAny'));

        if ($isMod || !$isSelf) {
            return $isSelf ? 'moderator_user' : 'moderator';
        } else {
            return 'user';
        }
    }

    /**
     * @return \XF\CustomField\Set
     */
    public function getCustomFields()
    {
        /** @var \XF\CustomField\DefinitionSet $fieldDefinitions */
        $fieldDefinitions = $this->app()->container(App::CONTAINER_KEY_CUSTOM_FIELDS);
        $class = $this->app()->extendClass('XF\CustomField\Set');

        return new $class($fieldDefinitions, $this);
    }

    /**
     * @return array
     */
    public function getExtraFieldTabs()
    {
        if (!$this->getValue('custom_fields') || $this->Category === null) {
            // if they haven't set anything, we can bail out quickly
            return [];
        }

        /** @var \XF\CustomField\Set $fieldSet */
        $fieldSet = $this->custom_fields;
        $definitionSet = $fieldSet->getDefinitionSet()
            ->filterOnly($this->Category->field_cache)
            ->filterGroup('new_tab')
            ->filterWithValue($fieldSet);

        $output = [];
        foreach ($definitionSet as $fieldId => $definition) {
            $output[$fieldId] = $definition->title;
        }

        return $output;
    }

    /**
     * @param \XF\Entity\Thread $thread
     * @see \Truonglv\Groups\XF\Entity\Forum::threadAdded()
     * @return void
     */
    public function onThreadCreated(\XF\Entity\Thread $thread)
    {
        $this->discussion_count++;
    }

    /**
     * @param \XF\Entity\Thread $thread
     * @see \Truonglv\Groups\XF\Entity\Forum::threadRemoved()
     * @return void
     */
    public function onThreadRemoved(\XF\Entity\Thread $thread)
    {
        $this->discussion_count--;
    }

    /**
     * @param Member $member
     * @return void
     */
    public function onMemberJoined(Member $member)
    {
        $this->member_count++;
    }

    /**
     * @param Member $member
     * @return void
     */
    public function onMemberLeaved(Member $member)
    {
        $this->member_count--;
    }

    /**
     * @return array
     */
    public function getNavigationItems()
    {
        return App::navigationData()->getNavigationItems($this);
    }

    /**
     * @return array
     */
    public function getEventIds()
    {
        return $this->db()->fetchAllColumn('
            SELECT `event_id`
            FROM `xf_tl_group_event`
            WHERE `group_id` = ?
        ', $this->group_id);
    }

    /**
     * @return bool
     */
    public function isFeatured()
    {
        /** @var Feature|null $feature */
        $feature = $this->Feature;

        return $feature !== null;
    }

    /**
     * @return Member|null
     */
    public function getMember()
    {
        $userId = XF::visitor()->user_id;
        if ($userId <= 0) {
            return null;
        }

        return $this->Members[$userId];
    }

    /**
     * @return string|null
     */
    public function getLanguageTitle()
    {
        if ($this->language_code === '') {
            return null;
        }

        $localeList = $this->app()->data('XF:Language')->getLocaleList();

        return isset($localeList[$this->language_code]) ? $localeList[$this->language_code] : null;
    }

    public function getDefaultTab(): string
    {
        $category = $this->Category;
        $defaultTab = App::getOption('defaultTab');
        if ($category !== null && $category->default_tab !== '') {
            $defaultTab = $category->default_tab;
        }

        return in_array($defaultTab, $this->getDisabledNavigationTabs(), true)
            // About tab should never be disabled by default.
            ? 'about'
            : $defaultTab;
    }

    public function getDisabledNavigationTabs(): array
    {
        $category = $this->Category;
        if ($category !== null) {
            return $category->disabled_navigation_tabs;
        }

        return [];
    }

    public function getCardMembers(): ?AbstractCollection
    {
        if (array_key_exists('CardMembers', $this->_getterCache)) {
            return $this->_getterCache['CardMembers'];
        }

        return null;
    }

    public function setCardMembers(AbstractCollection $members): void
    {
        $this->_getterCache['CardMembers'] = $members;
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
        $result->includeRelation('Category');

        $result->custom_fields = $this->Category !== null
            ? (object) $this->custom_fields->getNamedFieldValues($this->Category->field_cache)
            : [];
        $result->tags = array_column($this->tags, 'tag');

        if (XF::visitor()->user_id > 0) {
            /** @var Member|null $member */
            $member = $this->Member;
            $result->is_owner = $member !== null && $member->isOwner();
        } else {
            $result->is_owner = false;
        }

        $result->can_edit = $this->canEdit();
        $result->can_edit_tags = $this->canEditTags();
        $result->can_post = $this->canAddPost();
        $result->can_delete = $this->canDelete();
        $result->can_hard_delete = $this->canDelete('hard');

        $result->is_joined = $this->getMember() !== null;
        $result->can_join = $this->canJoin();
        $result->can_leave = $this->canLeave();

        $result->is_ignored = $this->isIgnored();

        $result->can_manage_avatar = $this->canManageAvatar();
        $result->can_manage_cover = $this->canManageCover();

        $result->avatar_url = $this->getAvatarUrl(true);
        $result->cover_url = $this->getCoverUrl(true);

        $router = $this->app()->router('public');
        $result->view_url = $router->buildLink('canonical:groups', $this);
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group';
        $structure->primaryKey = 'group_id';
        $structure->shortName = 'Truonglv\Groups:Group';
        $structure->contentType = App::CONTENT_TYPE_GROUP;

        $structure->columns = [
            'group_id' => ['type' => self::UINT, 'nullable' => true, 'autoIncrement' => true],
            'name' => [
                'type' => self::STR,
                // \XF::phrase('tlg_please_enter_valid_group_name')
                'required' => 'tlg_please_enter_valid_group_name',
                'maxLength' => 100,
                'api' => true
            ],
            'short_description' => [
                'type' => self::STR,
                // \XF::phrase('tlg_please_enter_valid_group_short_description')
                'required' => 'tlg_please_enter_valid_group_short_description',
                'maxLength' => 255,
                'api' => true
            ],
            'description' => ['type' => self::STR, 'required' => true, 'api' => true],
            'category_id' => ['type' => self::UINT, 'required' => true, 'api' => true],
            'owner_user_id' => ['type' => self::UINT, 'required' => true, 'api' => true],
            'owner_username' => ['type' => self::STR, 'required' => true, 'maxLength' => 50, 'api' => true],
            'privacy' => [
                'type' => self::STR,
                'allowedValues' => App::groupRepo()->getAllowedPrivacy(),
                'default' => App::PRIVACY_PUBLIC,
                'api' => true
            ],
            'group_state' => [
                'type' => self::STR,
                'allowedValues' => App::getAllowedStates(),
                'default' => App::STATE_VISIBLE,
                'api' => true
            ],
            'created_date' => ['type' => self::UINT, 'default' => time(), 'api' => true],

            'avatar_attachment_id' => ['type' => self::UINT, 'default' => 0],
            'cover_attachment_id' => ['type' => self::UINT, 'default' => 0],

            'tags' => ['type' => self::JSON_ARRAY, 'default' => [], 'api' => true],
            'language_code' => ['type' => self::STR, 'default' => '', 'maxLength' => 32, 'api' => true],

            'member_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true],
            'event_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true],
            'discussion_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true],
            'view_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true],

            'node_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => false],

            'member_cache' => ['type' => self::JSON_ARRAY, 'default' => []],
            'custom_fields' => ['type' => self::JSON_ARRAY, 'default' => []],

            'last_activity' => ['type' => self::UINT, 'default' => 0, 'api' => true],
            'member_moderated_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true],
            'album_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0, 'api' => true],

            'cover_crop_data' => ['type' => self::JSON_ARRAY, 'default' => []],
            'always_moderate_join' => ['type' => self::BOOL, 'default' => false, 'api' => true],
            'allow_guest_posting' => ['type' => self::BOOL, 'default' => false, 'api' => true]
        ];

        $structure->getters = [
            'custom_fields' => true,
            'Member' => [
                'cache' => false,
                'getter' => true
            ],
            'language_title' => true,
            // members display in grid card
            'CardMembers' => true,
        ];

        $structure->behaviors = [
            'XF:Taggable' => ['stateField' => 'group_state'],
            'XF:Indexable' => [
                'checkForUpdates' => ['name', 'category_id', 'owner_user_id', 'tags', 'group_state']
            ],
            'XF:CustomFieldsHolder' => [
                'valueTable' => 'xf_tl_group_field_value',
                'checkForUpdates' => ['category_id'],
                'getAllowedFields' => function ($group) {
                    return $group->Category ? $group->Category->field_cache : [];
                }
            ]
        ];

        $structure->relations = [
            'User' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:User',
                'conditions' => [
                    ['user_id', '=', '$owner_user_id']
                ],
                'primary' => true
            ],
            'Category' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Category',
                'conditions' => 'category_id',
                'primary' => true
            ],
            'Feature' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Feature',
                'conditions' => 'group_id',
                'primary' => true
            ],
            'DeletionLog' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:DeletionLog',
                'conditions' => [
                    ['content_type', '=', App::CONTENT_TYPE_GROUP],
                    ['content_id', '=', '$group_id']
                ]
            ],
            'ApprovalQueue' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:ApprovalQueue',
                'conditions' => [
                    ['content_type', '=', App::CONTENT_TYPE_GROUP],
                    ['content_id', '=', '$group_id']
                ]
            ],
            'MemberOwner' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Member',
                'conditions' => [
                    ['group_id', '=', '$group_id'],
                    ['user_id', '=', '$owner_user_id']
                ]
            ],
            'Members' => [
                'type' => self::TO_MANY,
                'entity' => 'Truonglv\Groups:Member',
                'conditions' => 'group_id',
                'key' => 'user_id',
                'order' => 'joined_date'
            ],
            'Views' => [
                'type' => self::TO_MANY,
                'entity' => 'Truonglv\Groups:GroupView',
                'conditions' => [
                    ['group_id', '=', '$group_id']
                ],
                'key' => 'user_id',
                'order' => 'view_date'
            ],
            'AvatarAttachment' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:Attachment',
                'conditions' => [
                    ['attachment_id', '=', '$avatar_attachment_id']
                ],
                'primary' => true,
                'with' => ['Data']
            ],
            'CoverAttachment' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:Attachment',
                'conditions' => [
                    ['attachment_id', '=', '$cover_attachment_id']
                ],
                'primary' => true,
                'with' => ['Data']
            ],
            'Tags' => [
                'entity' => 'XF:TagContent',
                'type' => self::TO_MANY,
                'conditions' => [
                    ['content_type', '=', $structure->contentType],
                    ['content_id', '=', '$group_id']
                ],
                'key' => 'tag_id'
            ],
            'CustomFields' => [
                'entity' => 'Truonglv\Groups:GroupFieldValue',
                'type' => self::TO_MANY,
                'conditions' => 'group_id',
                'key' => 'field_id'
            ],
        ];

        $structure->withAliases = [
            'fullView' => [
                'Category',
                'Feature',
                'AvatarAttachment',
                'CoverAttachment',
                function () {
                    $userId = XF::visitor()->user_id;
                    if ($userId > 0) {
                        return [
                            'Members|' . $userId
                        ];
                    }

                    return null;
                }
            ],
            'full' => [
                'fullView',
                function () {
                    $userId = XF::visitor()->user_id;
                    if ($userId > 0) {
                        return [
                            'Views|' . $userId,
                            'Members|' . $userId,
                        ];
                    }

                    return null;
                }
            ],
            'api' => [
                'fullView',
                'Feature',
                'AvatarAttachment',
                'CoverAttachment'
            ]
        ];

        return $structure;
    }

    protected function _preSave()
    {
        if ($this->isInsert() && $this->last_activity <= 0) {
            $this->last_activity = \time();
        }
    }

    protected function _postSave()
    {
        $approvalChange = $this->isStateChanged('group_state', App::STATE_MODERATED);
        $deletionChange = $this->isStateChanged('group_state', App::STATE_DELETED);

        $this->triggerCategoryUpdate();

        if ($this->isUpdate()) {
            /** @var \XF\Entity\ApprovalQueue|null $approvalQueue */
            $approvalQueue = $this->ApprovalQueue;
            if ($approvalChange === 'leave' && $approvalQueue !== null) {
                $approvalQueue->delete();
            }

            /** @var DeletionLog|null $deletionLog */
            $deletionLog = $this->DeletionLog;
            if ($deletionChange === 'leave' && $deletionLog !== null) {
                $deletionLog->delete();
            }
        }

        /** @var DeletionLog|null $deletionLog */
        $deletionLog = $this->DeletionLog;

        if ($approvalChange === 'enter') {
            /** @var ApprovalQueue $approvalQueue */
            $approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
            $approvalQueue->content_date = $this->created_date;
            $approvalQueue->save();
        } elseif ($deletionChange === 'enter' && $deletionLog === null) {
            /** @var DeletionLog $delLog */
            $delLog = $this->getRelationOrDefault('DeletionLog', false);
            $delLog->setFromVisitor();
            $delLog->save();
        }

        if ($this->isChanged('owner_user_id')) {
            /** @var User|null $newUser */
            $newUser = $this->User;
            if ($newUser !== null) {
                App::groupRepo()->adjustUserOwnGroupsCount($newUser, 1);
            }

            /** @var User|null $oldUser */
            $oldUser = $this->getExistingRelation('User');
            if ($oldUser !== null) {
                App::groupRepo()->adjustUserOwnGroupsCount($oldUser, -1);
            }
        }

        if ($this->isChanged(['privacy', 'group_state'])) {
            $this->app()->jobManager()->enqueueLater(
                'tlgGroupRebuild' . $this->group_id,
                XF::$time,
                'Truonglv\Groups:GroupItemRebuild',
                [
                    'groupId' => $this->group_id,
                    'rebuildUserCache' => true,
                ]
            );
        }
    }

    protected function _postDelete()
    {
        /** @var Category|null $category */
        $category = $this->Category;
        if ($category !== null) {
            $category->onGroupDeleted($this);
            $category->save();
        }

        /** @var User|null $user */
        $user = $this->User;
        if ($user !== null) {
            App::groupRepo()->adjustUserOwnGroupsCount($user, -1);
        }

        /** @var \XF\Entity\ApprovalQueue|null $approvalQueue */
        $approvalQueue = $this->ApprovalQueue;
        if ($this->group_state === App::STATE_MODERATED && $approvalQueue !== null) {
            $approvalQueue->delete();
        }

        /** @var DeletionLog|null $deletionLog */
        $deletionLog = $this->DeletionLog;
        if ($this->group_state === App::STATE_DELETED && $deletionLog !== null) {
            $deletionLog->delete();
        }

        /** @var \XF\Repository\Attachment $attachmentRepo */
        $attachmentRepo = $this->repository('XF:Attachment');
        $attachmentRepo->fastDeleteContentAttachments(App::CONTENT_TYPE_GROUP, [$this->group_id]);

        $db = $this->db();

        $db->delete('xf_tl_group_view', 'group_id = ?', $this->group_id);
        $db->delete('xf_tl_group_view_log', 'group_id = ?', $this->group_id);
        $db->delete('xf_tl_group_field_value', 'group_id = ?', $this->group_id);
        $db->delete('xf_tl_group_feature', 'group_id = ?', $this->group_id);

        $this->app()->jobManager()
                    ->enqueueLater(
                        'tlGroups_groupDel_' . $this->group_id,
                        XF::$time,
                        'Truonglv\Groups:GroupDelete',
                        ['group_id' => $this->group_id]
                    );

        if (App::isEnabledForums()) {
            $this->deleteForums();
        }
        GroupNodeCache::onGroupDeleted($this->group_id);

        /** @var \XF\Repository\UserAlert $userAlertRepo */
        $userAlertRepo = $this->repository('XF:UserAlert');
        $userAlertRepo->fastDeleteAlertsForContent($this->getEntityContentType(), $this->group_id);
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    protected function deleteForums()
    {
        // delete group forums.
        $forums = $this
            ->finder('XF:Forum')
            ->with('Node', true)
            ->with('GroupForum')
            ->where('GroupForum.group_id', $this->group_id)
            ->fetch();

        if ($forums->count() <= 0) {
            return;
        }

        /** @var XFForum $forum */
        foreach ($forums as $forum) {
            $this->deleteForum($forum);
        }
    }

    /**
     * @return void
     */
    protected function triggerCategoryUpdate()
    {
        /** @var Category|null $category */
        $category = $this->Category;

        if ($this->isInsert()) {
            if ($category !== null) {
                $category->onGroupAdded($this);
            }
        } elseif ($this->isUpdate()) {
            if ($this->isChanged('category_id')) {
                /** @var Category|null $oldCategoryEntity */
                $oldCategoryEntity = $this->getExistingRelation('Category');
                if ($oldCategoryEntity !== null) {
                    $oldCategoryEntity->onGroupDeleted($this);
                    $oldCategoryEntity->saveIfChanged();
                }

                if ($category !== null) {
                    $category->onGroupAdded($this);
                }
            }

            if ($category !== null) {
                $category->onGroupUpdated($this);
            }
        }

        if ($category !== null) {
            $category->saveIfChanged();
        }
    }

    /**
     * @param XFForum $forum
     * @throws \XF\PrintableException
     * @return void
     */
    public function deleteForum(XFForum $forum)
    {
        if (App::getOption('deleteForumAction', 'type') == 1) {
            /** @var \Truonglv\Groups\XF\Entity\Node|mixed $node */
            $node = $forum->getRelationOrDefault('Node');
            $node->parent_node_id = App::getOption('deleteForumAction', 'node_id');

            $forum->save();
        } else {
            $forum->delete();
        }

        if (!$this->isDeleted()) {
            $this->fastUpdate('discussion_count', max(0, $this->discussion_count - $forum->discussion_count));
        }

        $db = $this->db();
        $db->delete('xf_tl_group_forum', 'group_id = ? AND node_id = ?', [$this->group_id, $forum->node_id]);
        GroupNodeCache::rebuildCache();
    }

    // rebuild methods

    /**
     * @return void
     */
    public function rebuildCounters()
    {
        $db = $this->db();

        $this->member_count = $db->fetchOne('
            SELECT COUNT(*)
            FROM xf_tl_group_member
            WHERE group_id = ? AND member_state = ?
        ', [$this->group_id, App::MEMBER_STATE_VALID]);
        
        $this->member_moderated_count = $db->fetchOne('
            SELECT COUNT(*)
            FROM xf_tl_group_member
            WHERE group_id = ? AND member_state = ?
        ', [$this->group_id, App::MEMBER_STATE_MODERATED]);

        $this->event_count = $db->fetchOne('
            SELECT COUNT(*)
            FROM xf_tl_group_event
            WHERE group_id = ?
        ', $this->group_id);

        $this->album_count = $db->fetchOne('
            SELECT COUNT(*)
            FROM xf_tl_group_mg_album
            WHERE group_id = ?
        ', $this->group_id);

        $nodeStats = $db->fetchRow('
            SELECT COUNT(*) AS total, SUM(forum.discussion_count) AS discussion_count
            FROM xf_tl_group_forum AS gf
                INNER JOIN xf_forum AS forum ON (forum.node_id = gf.node_id)
            WHERE gf.group_id = ?
        ', $this->group_id);

        $this->node_count = (int) $nodeStats['total'];
        $this->discussion_count = (int) $nodeStats['discussion_count'];
    }

    public function rebuildMemberCache(): void
    {
        $members = App::memberFinder()->inGroup($this)
            ->where('member_state', App::MEMBER_STATE_VALID)
            ->order('joined_date', 'desc')
            ->limit(10)
            ->fetch();
        $memberCache = [];
        /** @var Member $member */
        foreach ($members as $member) {
            $memberCache[$member->user_id] = $member->getDataForCache();
        }
        $this->fastUpdate('member_cache', $memberCache);
    }

    /**
     * @param bool $canonical
     * @param array $extraParams
     * @param mixed $hash
     * @return string
     */
    public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
    {
        $route = $this->getContentPublicRoute();

        return $this->app()->router('public')
            ->buildLink($canonical ? "canonical:{$route}" : $route, $this, $extraParams);
    }

    /**
     * @return string
     */
    public function getContentPublicRoute()
    {
        return 'groups';
    }

    /**
     * @param string $context
     * @return string
     */
    public function getContentTitle(string $context = '')
    {
        return $this->name;
    }
}
