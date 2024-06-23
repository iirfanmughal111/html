<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Pub\Controller;

use XF;
use Throwable;
use function count;
use function reset;
use LogicException;
use function intval;
use function compact;
use function explode;
use XF\Mvc\Reply\View;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use function array_shift;
use function utf8_strlen;
use XF\PrintableException;
use XFMG\Repository\Album;
use Truonglv\Groups\Callback;
use Truonglv\Groups\Listener;
use Truonglv\Groups\Util\Arr;
use XF\Repository\Attachment;
use XF\ControllerPlugin\Editor;
use XF\Mvc\Reply\AbstractReply;
use XF\ControllerPlugin\Undelete;
use Truonglv\Groups\Entity\Feature;
use Truonglv\Groups\XF\Entity\User;
use Truonglv\Groups\Service\Group\Joiner;
use Truonglv\Groups\Service\Group\Merger;
use Truonglv\Groups\Service\Post\Creator;
use XF\Pub\Controller\AbstractController;
use Truonglv\Groups\Service\Album\Importer;
use Truonglv\Groups\Service\Group\Reassigner;
use Truonglv\Groups\Service\Group\AbstractFormUpload;

class Group extends AbstractController
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        parent::preDispatchController($action, $params);

        if (!App::hasPermission('view')) {
            throw $this->exception($this->noPermission());
        }
    }

    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @param AbstractReply $reply
     * @return void
     */
    protected function postDispatchController($action, ParameterBag $params, AbstractReply & $reply)
    {
        parent::postDispatchController($action, $params, $reply);

        if (!$reply instanceof View) {
            return;
        }

        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = $reply->getParam('group');
        if ($group !== null) {
            $reply->setContainerKey('tlg-group-' . $group->group_id);
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        if ($params['group_id'] > 0) {
            return $this->rerouteController(__CLASS__, 'view', $params);
        }

        $dataListPlugin = App::groupListPlugin($this);

        $categoryParams = $dataListPlugin->getCategoryListData();
        $viewableCategoryIds = $categoryParams['categories']->keys();

        $listParams = $dataListPlugin->getGroupListData(
            $viewableCategoryIds,
            null,
            function (\Truonglv\Groups\Finder\Group $finder) {
                $defaultSort = App::getOption('defaultSort');
                $finder->order($defaultSort['order'], $defaultSort['direction']);
            }
        );

        $this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'groups');
        $this->assertCanonicalUrl($this->buildLink('groups', null, ['page' => $listParams['page']]));

        $viewParams = $categoryParams + $listParams;

        return $this->view('Truonglv\Groups:Group\Index', 'tlg_group_index', $viewParams);
    }

    public function actionFilters()
    {
        return App::groupListPlugin($this)->actionFilters();
    }

    public function actionView(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith());
        $this->assertCanonicalUrl($this->buildLink('groups', $group));

        if (!$group->isPublicGroup()
            && ($group->Member === null || !$group->Member->isValidMember())
            && !App::hasPermission('bypassViewPrivacy')
        ) {
            return $this->rerouteController(__CLASS__, 'about', $params);
        }

        $defaultTab = $group->getDefaultTab();
        $params->offsetSet('_originAction', 'view');

        switch ($defaultTab) {
            case 'discussions':
                return $this->rerouteController(__CLASS__, 'discussions', $params);
            case 'members':
                return $this->rerouteController(__CLASS__, 'members', $params);
            case 'events':
                return $this->rerouteController(__CLASS__, 'events', $params);
            case 'media':
                return $this->rerouteController(__CLASS__, 'media', $params);
            case 'resources':
                return $this->rerouteController(__CLASS__, 'resources', $params);
        }

        return $this->actionFeeds($params);
    }

    public function actionFeeds(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith());
        $originAction = $params->offsetGet('_originAction');
        if ($originAction !== 'view') {
            $this->assertCanonicalUrl($this->buildLink('groups/feeds', $group));
        }

        $page = $this->filterPage();
        $perPage = $this->options()->tl_groups_commentsPerPage;
        $postRepo = App::postRepo();

        $stickyPosts = null;
        $maxPostStickies = $this->options()->tl_groups_maxPostStickies;
        if ($page === 1 && $maxPostStickies > 0) {
            $postFinder = $postRepo->findStickyPostsInGroup($group);
            $postFinder->limit($maxPostStickies);

            $stickyPosts = $postFinder->fetch();
            $stickyPosts = $postRepo->addLatestCommentsIntoPosts($stickyPosts);
        }

        $postFinder = $postRepo->findPostsInGroup($group);
        $postFinder->where('sticky', 0);

        $total = $postFinder->total();
        $this->assertValidPage($page, $perPage, $total, 'groups' . ($originAction === 'view' ? '' : '/feeds'), $group);

        $posts = $postFinder->limitByPage($page, $perPage)->fetch();
        $posts = $postRepo->addLatestCommentsIntoPosts($posts);

        $attachmentData = null;
        if ($group->canUploadAndManageAttachments()) {
            /** @var Attachment $attachmentRepo */
            $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentHash = null;

            $attachmentData = $attachmentRepo->getEditorData(App::CONTENT_TYPE_COMMENT, $group, $attachmentHash, [
                'group_id' => $group->group_id
            ]);
        }

        App::groupRepo()->logView($group);

        $viewParams = [
            'group' => $group,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'posts' => $posts,
            'stickyPosts' => $stickyPosts,
            'attachmentData' => $attachmentData,
            'pageNavLink' => 'groups' . ($originAction === 'view' ? '' : '/feeds'),
        ];

        return $this->view('Truonglv\Groups:Group\View', 'tlg_group_view', $viewParams);
    }

    public function actionDiscussions(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith(), true);

        if ($params->offsetGet('_originAction') !== 'view') {
            $this->assertCanonicalUrl($this->buildLink('groups/discussions', $group));
        }

        if (!$group->canViewForums($error)) {
            return $this->noPermission($error);
        }

        $nodeTree = App::nodeTreeData()->forumTree($group);

        if ($nodeTree->count() === 1
            && $this->options()->tl_groups_maxNodes == 1
        ) {
            $childIds = $nodeTree->childIds();

            return $this->rerouteController('XF:Forum', 'forum', [
                'node_id' => reset($childIds)
            ]);
        }

        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = $this->repository('XF:Node');
        $nodeExtras = $nodeRepo->getNodeListExtras($nodeTree);

        Listener::addContentLanguageResponseHeader($group);
        App::groupRepo()->logView($group);

        $viewParams = [
            'group' => $group,
            'nodeTree' => $nodeTree,
            'nodeExtras' => $nodeExtras
        ];

        return $this->view('Truonglv\Groups:Group\Discussions', 'tlg_group_discussions', $viewParams);
    }

    public function actionAbout(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith());
        $this->assertCanonicalUrl($this->buildLink('groups/about', $group));

        $memberRepo = App::memberRepo();

        Listener::addContentLanguageResponseHeader($group);
        App::groupRepo()->logView($group);

        $viewParams = [
            'group' => $group,
            'managers' => $memberRepo->getManagers($group)
        ];

        if ($group->isPublicGroup()
            || ($group->Member !== null && $group->Member->isValidMember())
        ) {
            $viewParams['members'] = $memberRepo->findMembersForList($group)
                ->memberOnly()
                ->limit(20)
                ->fetch();
        }

        return $this->view('Truonglv\Groups:Group\About', 'tlg_group_about', $viewParams);
    }

    public function actionMedia(ParameterBag $params)
    {
        if (!App::isEnabledXenMediaAddOn()) {
            return $this->noPermission();
        }

        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith(), true);
        if ($params->offsetGet('_originAction') !== 'view') {
            $this->assertCanonicalUrl($this->buildLink('groups/media', $group));
        }

        /** @var \XFMG\ControllerPlugin\AlbumList $albumListPlugin */
        $albumListPlugin = $this->plugin('XFMG:AlbumList');
        $page = $this->filterPage();

        /** @var Album $albumRepo */
        $albumRepo = $this->repository('XFMG:Album');
        /** @var \Truonglv\Groups\XFMG\Finder\Album $albumFinder */
        $albumFinder = $albumRepo->findAlbumsForMixedList([
            'categoryIds' => [
                // dirty bypass XFMG check
                // @see \Truonglv\Groups\XFMG\Finder\Album::inCategoriesIncludePersonalAlbums()
                'tlg_group_id' => $group->group_id,
            ],
        ]);

        $filters = $albumListPlugin->getFilterInput();
        $filters['group_id'] = $group->group_id;
        $albumListPlugin->applyFilters($albumFinder, $filters);

        $albumFinder->where('GroupAlbum.group_id', $group->group_id);

        $totalItems = $albumFinder->total();
        $perPage = $this->options()->xfmgAlbumsPerPage;

        $albumFinder->limitByPage($page, $perPage);
        $albums = $albumFinder->fetch();
        /** @var \Truonglv\Groups\XFMG\Entity\Album $album */
        foreach ($albums as $album) {
            $album->setTLGGroup($group);
        }
        $albums = $albums->filterViewable();

        if (isset($filters['owner_id'])) {
            $ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
        } else {
            $ownerFilter = null;
        }

        Listener::addContentLanguageResponseHeader($group);
        App::groupRepo()->logView($group);

        $viewParams = [
            'group' => $group,
            // force it's to false and we will check after user click button.
            'canAddAlbum' => false,
            'filters' => $filters,
            'albums' => $albums,
            'ownerFilter' => $ownerFilter,
            'total' => $totalItems,
            'page' => $page,
            'perPage' => $perPage,
            'canLinkAlbums' => $group->canLinkAlbums() || $group->canAddAlbums(),
        ];

        return $this->view(
            'Truonglv\Groups:Group\Media',
            'tlg_group_media_list',
            $viewParams
        );
    }

    public function actionMediaAdd(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canLinkAlbums() && !$group->canAddAlbums()) {
            return $this->noPermission();
        }

        if ($this->isPost()) {
            $input = $this->filter([
                'action' => 'str',
                'album_ids' => 'array-str',
            ]);

            if ($input['action'] === 'new' && $group->canAddAlbums()) {
                return $this->redirect($this->buildLink('media/albums/create', null, [
                    'group_id' => $group->group_id
                ]));
            } elseif ($input['action'] === 'existing' && $group->canLinkAlbums()) {
                $albumIds = [];
                foreach ($input['album_ids'] as $albumId) {
                    $parts = Arr::stringToArray($albumId, '/\,/');
                    $albumId = array_shift($parts);
                    $albumId = intval($albumId);

                    if ($albumId > 0) {
                        $albumIds[] = $albumId;
                    }
                }

                if (count($albumIds) === 0) {
                    return $this->error(XF::phrase('please_enter_valid_value'));
                }

                /** @var Importer $importer */
                $importer = $this->service('Truonglv\Groups:Album\Importer', $group);
                $importer->setAlbumIds($albumIds);

                try {
                    $importer->save();
                } catch (Throwable $e) {
                    if ($e instanceof PrintableException) {
                        throw $e;
                    }

                    // silent.
                }

                return $this->redirect($this->buildLink('groups/media', $group));
            }
        }

        return $this->view(
            'Truonglv\Groups:Group\MediaAdd',
            'tlg_media_album_add_chooser',
            [
                'group' => $group,
                'canAddAlbums' => $group->canAddAlbums(),
                'canLinkAlbums' => $group->canLinkAlbums(),
                'isInline' => $this->filter('_xfWithData', 'bool'),
            ]
        );
    }

    public function actionField(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith(), true);
        $this->assertCanonicalUrl($this->buildLink('groups/field', $group));

        $fieldId = $this->filter('field', 'str');
        $tabFields = $group->getExtraFieldTabs();

        if (!isset($tabFields[$fieldId])) {
            return $this->redirect($this->buildLink('groups', $group));
        }

        /** @var \XF\CustomField\Set $fieldSet */
        $fieldSet = $group->custom_fields;
        $definition = $fieldSet->getDefinition($fieldId);
        $fieldValue = $fieldSet->getFieldValue($fieldId);

        Listener::addContentLanguageResponseHeader($group);
        App::groupRepo()->logView($group);

        $viewParams = [
            'group' => $group,
            'category' => $group->Category,

            'fieldId' => $fieldId,
            'fieldDefinition' => $definition,
            'fieldValue' => $fieldValue
        ];

        return $this->view(
            'Truonglv\Groups:Group\Extra',
            'tlg_group_field',
            $viewParams
        );
    }

    public function actionMembers(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith(), true);
        if ($params->offsetGet('_originAction') !== 'view') {
            $this->assertCanonicalUrl($this->buildLink('groups/members', $group));
        }

        $listParams = App::memberListPlugin($this)->getMemberListData($group);

        $canInlineMod = false;
        /** @var \Truonglv\Groups\Entity\Member $member */
        foreach ($listParams['members'] as $member) {
            if ($member->canInlineMod()) {
                $canInlineMod = true;

                break;
            }
        }

        $listParams['canInlineMod'] = $canInlineMod;

        $this->assertValidPage(
            $listParams['page'],
            $listParams['perPage'],
            $listParams['total'],
            'groups/members',
            $group
        );

        Listener::addContentLanguageResponseHeader($group);
        App::groupRepo()->logView($group);

        return $this->view('Truonglv\Groups:Group\Members', 'tlg_group_members', $listParams);
    }

    public function actionAddPost(ParameterBag $params)
    {
        $this->assertPostOnly();
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canAddPost($error)) {
            return $this->noPermission($error);
        }

        /** @var Creator $creator */
        $creator = $this->service('Truonglv\Groups:Post\Creator', $group);

        /** @var \Truonglv\Groups\ControllerPlugin\Comment $commentPlugin */
        $commentPlugin = $this->plugin('Truonglv\Groups:Comment');
        /** @var \Truonglv\Groups\Entity\Post $post */
        $post = $commentPlugin->saveCommentProcess($creator, $group->canUploadAndManageAttachments());
        $creator->sendNotifications();

        $viewParams = [
            'group' => $group,
            'post' => $post
        ];

        return $this->view('Truonglv\Groups:Post\Item', 'tlg_post_item', $viewParams);
    }

    public function actionAdd()
    {
        $this->assertCanonicalUrl($this->buildLink('groups/add'));

        $categoryRepo = App::categoryRepo();

        $categories = $categoryRepo->getViewableCategories();
        $canAdd = false;

        /** @var \Truonglv\Groups\Entity\Category $category */
        foreach ($categories as $category) {
            if ($category->canAddGroup()) {
                $canAdd = true;

                break;
            }
        }

        if (!$canAdd) {
            return $this->noPermission();
        }

        $categoryTree = $categoryRepo->createCategoryTree($categories);
        $categoryTree = $categoryTree->filter(null, function (
            $id,
            \Truonglv\Groups\Entity\Category $category,
            $depth,
            $children
        ) {
            if ($children) {
                return true;
            }

            if ($category->canAddGroup()) {
                return true;
            }

            return false;
        });

        $categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

        $viewParams = [
            'categoryTree' => $categoryTree,
            'categoryExtras' => $categoryExtras
        ];

        return $this->view('Truonglv\Groups:Group\AddChooser', 'tlg_group_add_chooser', $viewParams);
    }

    public function actionEdit(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);

        $this->assertCanonicalUrl($this->buildLink('groups/edit', $group));

        if (!$group->canEdit($errors)) {
            return $this->noPermission($errors);
        }

        $dataList = App::groupListPlugin($this);
        if ($this->isPost()) {
            $editor = $this->setupGroupEditService($group);

            if (!$editor->validate($errors)) {
                return $this->error($errors);
            }

            $group = $editor->save();
            $this->finalizeGroupEdit($editor);

            return $this->redirect($this->buildLink('groups', $group));
        }

        $editableTags = null;
        $uneditableTags = null;

        if ($group->canEditTags()) {
            /** @var \XF\Service\Tag\Changer $tagger */
            $tagger = $this->service('XF:Tag\Changer', App::CONTENT_TYPE_GROUP, $group);

            $grouped = $tagger->getExistingTagsByEditability();

            $editableTags = $grouped['editable'];
            $uneditableTags = $grouped['uneditable'];
        }

        $viewParams = [
            'group' => $group,
            'category' => $group->Category,
            'languages' => $this->data('XF:Language')->getLocaleList(),
            'canEditTags' => $group->canEditTags(),
            'editableTags' => $editableTags,
            'uneditableTags' => $uneditableTags
        ];

        return $dataList->getGroupForm('Truonglv\Groups:Group\Edit', $viewParams);
    }

    public function actionAddForum(ParameterBag $params)
    {
        $this->request()->set('group_id', $params->group_id);

        return $this->rerouteController('Truonglv\Groups:Forum', 'add', $params);
    }

    public function actionInvite(ParameterBag $params)
    {
        $this->assertPostOnly();

        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canInvitePeople($error)) {
            return $this->noPermission($error);
        }

        /** @var mixed $limit */
        $limit = (int) App::hasPermission('maxInvitesPerDay');
        if ($limit >= 0) {
            $totalInvites = $this->finder('Truonglv\Groups:Log')
                ->where('action', 'invite')
                ->where('user_id', XF::visitor()->user_id)
                ->where('log_date', '>=', XF::$time - 86400)
                ->total();

            if ($totalInvites >= $limit) {
                return $this->error(XF::phrase('tlg_you_reached_maximum_invites_today_allowed_x', [
                    'max' => $limit
                ]));
            }
        }

        $username = $this->filter('username', 'str');
        if ($username === '') {
            return $this->error(XF::phrase('please_enter_valid_name'));
        }

        /** @var \Truonglv\Groups\Service\Group\Inviter $inviter */
        $inviter = $this->service('Truonglv\Groups:Group\Inviter', $group);
        $inviter->setLogAction(true);
        $inviter->toUsernameOrEmail($username);

        if (!$inviter->validate($errors)) {
            return $this->error($errors);
        }
        $this->assertNotFlooding('tlgMemberInvite');

        $member = $inviter->sendInvitation();

        return $this->message(XF::phrase('tlg_an_invitation_was_sent_to_the_user_x', [
            'name' => $member->username
        ]));
    }

    public function actionReassign(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canReassign($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $username = $this->filter('username', 'str');

            /** @var Reassigner $reassigner */
            $reassigner = $this->service('Truonglv\Groups:Group\Reassigner', $group);
            $reassigner->setNewOwnerName($username);

            $reassigner->assign();

            return $this->redirect($this->buildLink('groups', $group));
        }

        return $this->view('Truonglv\Groups:Group\Reassign', 'tlg_group_reassign', [
            'group' => $group,
            'quickAssign' => $this->filter('_xfWithData', 'bool')
        ]);
    }

    public function actionMerge(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canMerge($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $source = $this->filter('source_group', 'str');
            $source = explode(',', $source);

            $sourceId = array_shift($source);
            $sourceId = intval($sourceId);

            $sourceGroup = App::assertionPlugin($this)->assertGroupViewable($sourceId);

            /** @var Merger $merger */
            $merger = $this->service('Truonglv\Groups:Group\Merger', $group);
            $merger->setAlertType($this->filter('alert_type', 'str'));
            $merger->merge($sourceGroup);

            return $this->redirect($this->buildLink('groups', $group));
        }

        return $this->view('Truonglv\Groups:Group\Merge', 'tlg_group_merge', [
            'group' => $group,
            'hasWrapper' => $this->filter('_xfWithData', 'bool') === false
        ]);
    }

    public function actionReport(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canReport($errors)) {
            return $this->noPermission($errors);
        }

        /** @var \XF\ControllerPlugin\Report $reportPlugin */
        $reportPlugin = $this->plugin('XF:Report');

        return $reportPlugin->actionReport(
            App::CONTENT_TYPE_GROUP,
            $group,
            $this->buildLink('groups/report', $group),
            $this->buildLink('groups', $group)
        );
    }

    public function actionAvatar(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canManageAvatar($errors)) {
            return $this->noPermission($errors);
        }

        /** @var \Truonglv\Groups\Service\Group\Avatar $avatar */
        $avatar = $this->service('Truonglv\Groups:Group\Avatar', $group);

        return $this->handleAvatarOrCoverProcess($group, $avatar);
    }

    public function actionCover(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canManageCover($errors)) {
            return $this->noPermission($errors);
        }

        /** @var \Truonglv\Groups\Service\Group\Cover $cover */
        $cover = $this->service('Truonglv\Groups:Group\Cover', $group);
        if ($this->filter('reposition', 'bool') === true) {
            if ($this->isPost()) {
                $crop = $this->filter([
                    'crop' => [
                        'w' => 'uint',
                        'h' => 'uint',
                        'x' => 'str',
                        'y' => 'str'
                    ]
                ]);

                $cover->setCropData($crop['crop']);
                $cover->saveCropData();

                return $this->redirect($this->buildLink('groups', $group));
            }

            return $this->view('Truonglv\Groups:Group\CoverReposition', 'tlg_group_cover_reposition', [
               'group' => $group
            ]);
        }

        return $this->handleAvatarOrCoverProcess($group, $cover);
    }

    public function actionPrivacy(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canUpdatePrivacy($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $input = $this->filter([
                'privacy' => 'str',
                'always_moderate_join' => 'bool',
                'allow_guest_posting' => 'bool'
            ]);

            /** @var \Truonglv\Groups\Entity\Category $category */
            $category = $group->Category;
            if (!$category->canAddGroupType($input['privacy'], $error)) {
                throw $this->exception($this->noPermission($error));
            }

            /** @var \Truonglv\Groups\Service\Group\Editor $editor */
            $editor = $this->service('Truonglv\Groups:Group\Editor', $group);
            $editor->getGroup()->bulkSet($input);

            if (!$editor->validate($errors)) {
                return $this->error($errors);
            }

            $editor->save();

            return $this->redirect($this->buildLink('groups', $group));
        }

        return $this->view('Truonglv\Groups:Group\Privacy', 'tlg_group_privacy_update', [
            'group' => $group
        ]);
    }

    public function actionEvents(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith(), true);

        if ($params->offsetGet('_originAction') !== 'view') {
            $this->assertCanonicalUrl($this->buildLink('groups/events', $group));
        }

        if (!$group->canViewEvents($error)) {
            return $this->noPermission($error);
        }

        $viewLayout = $this->filter('view', 'str');
        if (!in_array($viewLayout, ['list', 'calendar'], true)) {
            $viewLayout = App::getOption('defaultEventView');
        }


        Listener::addContentLanguageResponseHeader($group);
        App::groupRepo()->logView($group);

        if ($viewLayout === 'calendar') {
            return $this->view('Truonglv\Groups:Event\Calendar', 'tlg_group_event_calendar', [
                'group' => $group
            ]);
        }

        $tabFilter = $this->filter('filter', 'str');
        if (!\in_array($tabFilter, ['ongoing', 'upcoming', 'closed'], true)) {
            $tabFilter = 'upcoming';
        }

        $listParams = App::eventListPlugin($this)->getEventListData(
            $group,
            function (\Truonglv\Groups\Finder\Event $event) use ($tabFilter) {
                switch ($tabFilter) {
                    case 'ongoing':
                        $event->ongoing();

                        break;
                    case 'upcoming':
                        $event->upcoming();

                        break;
                    case 'closed':
                        $event->closed();

                        break;
                }
            }
        );

        $this->assertValidPage(
            $listParams['page'],
            $listParams['perPage'],
            $listParams['total'],
            'groups/events',
            $group
        );

        $listParams += [
            'tabFilter' => $tabFilter,
            'filters' => [
                'filter' => $tabFilter,
                'view' => 'list'
            ]
        ];

        return $this->view('Truonglv\Groups:Group\Events', 'tlg_group_events', $listParams);
    }

    public function actionResources(ParameterBag $params)
    {
        if (!App::isEnabledResources()) {
            return $this->noPermission();
        }

        $group = $this->assertViewableGroup($params['group_id'], $this->getGroupViewExtraWith(), true);

        if ($params->offsetGet('_originAction') !== 'view') {
            $this->assertCanonicalUrl($this->buildLink('groups/resources', $group));
        }

        if (!$group->canViewResources($error)) {
            return $this->noPermission($error);
        }

        $finder = App::resourceRepo()->findResourcesForList($group);

        $perPage = App::getOption('resourcesPerPage');
        $page = $this->filterPage();

        $total = $finder->total();
        $this->assertValidPage($page, $perPage, $total, 'groups/resources', $group);

        $resources = $total > 0 ? $finder->limitByPage($page, $perPage)->fetch() : $this->em()->getEmptyCollection();

        Listener::addContentLanguageResponseHeader($group);
        App::groupRepo()->logView($group);

        $listParams = [
            'group' => $group,
            'page' => $page,
            'perPage' => $perPage,
            'resources' => $resources,
            'total' => $total,
        ];

        return $this->view(
            'Truonglv\Groups:Group\Resources',
            'tlg_group_resources',
            $listParams
        );
    }

    public function actionTags(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);

        return App::groupListPlugin($this)->actionTags(
            'Truonglv\Groups:Group\Tags',
            App::CONTENT_TYPE_GROUP,
            $group,
            $this->buildLink('groups/tags', $group),
            $this->buildLink('groups', $group)
        );
    }

    public function actionPreview(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);

        $groups = $this->em()->getBasicCollection([$group->group_id => $group]);
        App::groupRepo()->addMembersIntoGroups($groups);
        $group = $groups->first();

        $viewParams = [
            'group' => $group
        ];

        return $this->view(
            'Truonglv\Groups:Group\Preview',
            'tlg_group_preview',
            $viewParams
        );
    }

    public function actionDelete(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canDelete('soft', $error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $inputData = $this->filter([
                'hard_delete' => 'bool',
                'reason' => 'str',
            ]);

            if ($inputData['hard_delete'] === true && !$group->canDelete('hard')) {
                return $this->noPermission();
            }

            /** @var \Truonglv\Groups\Service\Deleter $deleter */
            $deleter = XF::service('Truonglv\Groups:Deleter', $group);
            $deleter
                ->setStateField('group_state')
                ->delete($inputData['hard_delete'], $inputData['reason']);

            return $this->redirect($this->buildLink('group-categories', $group->Category));
        }

        return $this->view(
            'Truonglv\Groups:Group\Delete',
            'tlg_group_delete',
            [
                'confirmUrl' => $this->buildLink('groups/delete', $group),
                'breadcrumbs' => $group->getBreadcrumbs(),
                'entity' => $group,
            ]
        );
    }

    public function actionUndelete(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canUndelete($error)) {
            return $this->noPermission($error);
        }

        /** @var Undelete $undeletePlugin */
        $undeletePlugin = $this->plugin('XF:Undelete');

        return $undeletePlugin->actionUndelete(
            $group,
            $this->buildLink('groups/undelete', $group),
            $this->buildLink('groups', $group),
            $group->name,
            'group_state'
        );
    }

    public function actionToggleApprove(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canApproveUnapprove($error)) {
            return $this->noPermission($error);
        }

        /** @var \Truonglv\Groups\Service\Group\Approver $approver */
        $approver = $this->service('Truonglv\Groups:Group\Approver', $group);
        $approver->setAlertApproved(false);
        $approver->toggle();

        return $this->redirect($this->buildLink('groups', $group));
    }

    public function actionFeature(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canFeatureUnfeature($error)) {
            return $this->noPermission($error);
        }

        $this->assertCanonicalUrl($this->buildLink('groups/feature', $group));

        $redirect = $this->filter('redirect', 'str');

        if ($this->isPost()) {
            $expireDate = 0;
            if ($this->filter('expire_type', 'bool') === true) {
                $expireDate = $this->filter('expire_date', 'datetime,end');
                if ($expireDate <= XF::$time) {
                    return $this->error(XF::phrase('please_enter_a_date_in_the_future'));
                }
            }

            /** @var \Truonglv\Groups\Entity\Feature $feature */
            $feature = $group->getRelationOrDefault('Feature', false);

            $feature->group_id = $group->group_id;
            $feature->expire_date = $expireDate;

            $feature->save();

            return $this->redirect($redirect !== '' ? $redirect : $this->buildLink('groups', $group));
        }

        $formParams = [
            'hiddenInputs' => [
                'redirect' => $redirect
            ]
        ];

        $expireDate = 0;
        /** @var Feature|null $feature */
        $feature = $group->Feature;
        if ($feature !== null) {
            $expireDate = $feature->expire_date;
        }

        return App::assistantPlugin($this)->formTimePeriod(
            XF::phrase('tlg_feature_group'),
            $this->buildLink('groups/feature', $group),
            $expireDate,
            $formParams
        );
    }

    public function actionMove(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canMove($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $categoryId = $this->filter('category_id', 'uint');
            /** @var \Truonglv\Groups\Entity\Category $category */
            $category = App::assertionPlugin($this)->assertCategoryViewable($categoryId);

            if ($category->category_id != $group->category_id) {
                $group->category_id = $category->category_id;
                $group->save();
            }

            return $this->redirect($this->buildLink('groups', $group));
        }

        $viewParams = [
            'group' => $group,
            'nodeTree' => App::categoryRepo()->createCategoryTree(),
            'hasWrapper' => $this->filter('_xfWithData', 'bool') === false
        ];

        return $this->view(
            'Truonglv\Groups:Group\Move',
            'tlg_group_move',
            $viewParams
        );
    }

    public function actionUnfeature(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canFeatureUnfeature($error)) {
            return $this->noPermission($error);
        }

        $this->assertCanonicalUrl($this->buildLink('groups/unfeature', $group));

        /** @var Feature|null $feature */
        $feature = $group->Feature;
        if ($feature !== null) {
            $feature->delete();
        }

        $redirect = $this->filter('redirect', 'str');

        return $this->redirect($redirect !== '' ? $redirect : $this->buildLink('groups', $group));
    }

    public function actionJoin(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);

        if (!$group->canJoin($error)) {
            return $this->noPermission($error);
        }

        $redirect = $this->filter('redirect', 'str');

        if ($this->isPost()) {
            /** @var Joiner $joiner */
            $joiner = $this->service('Truonglv\Groups:Group\Joiner', $group, XF::visitor());
            if (!$joiner->validate($errors)) {
                return $this->error($errors);
            }

            $joiner->save();
            $joiner->sendNotifications();

            return $this->redirect($redirect !== '' ? $redirect : $this->buildLink('groups', $group));
        }

        return $this->view('Truonglv\Groups:Member\Join', 'tlg_group_member_join', [
            'group' => $group,
            'redirect' => $redirect,
            'isInline' => $this->filter('_xfWithData', 'bool')
        ]);
    }

    public function actionFind()
    {
        $q = $this->filter('q', 'str,no-trim');
        if (utf8_strlen($q) > 2) {
            $finder = App::groupFinder();
            $finder->applyGlobalVisibilityChecks();
            $finder->applyGlobalPrivacyChecks();

            $finder->where('name', 'LIKE', $this->app()->db()->escapeLike($q) . '%');
            $finder->order('name');

            $groups = $finder->limit(10)->fetch();
        } else {
            $groups = [];
            $q = '';
        }

        $results = [];
        $templater = $this->app()->templater();
        /** @var \Truonglv\Groups\Entity\Group $group */
        foreach ($groups as $group) {
            $results[] = [
                'id' => $group->group_id,
                'text' => $group->group_id . ',' . $group->name,
                'q' => $q,
                'iconHtml' => Callback::renderAvatar('', ['group' => $group, 'forceImage' => true], $templater)
            ];
        }

        $replier = $this->view(
            'Truonglv\Groups:Group\Find',
            '',
            compact('results', 'q')
        );
        $replier->setJsonParams([
            'results' => $results,
            'q' => $q
        ]);

        return $replier;
    }

    public function actionBadge(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params['group_id']);
        if (!$group->canUseAsBadge($error)) {
            return $this->noPermission($error);
        }

        /** @var User $user */
        $user = XF::visitor();
        if ($user->tlg_badge_group_id === $group->group_id) {
            $user->tlg_badge_group_id = 0;
        } else {
            $user->tlg_badge_group_id = $group->group_id;
        }

        $user->save();

        return $this->redirect($this->buildLink('groups', $group));
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param AbstractFormUpload $service
     * @param array $params
     * @return \XF\Mvc\Reply\AbstractReply
     */
    protected function handleAvatarOrCoverProcess(
        \Truonglv\Groups\Entity\Group $group,
        AbstractFormUpload $service,
        array $params = []
    ) {
        if ($this->isPost()) {
            if ($this->filter('delete', 'bool') === true
                && $service->canDeleteExisting()
            ) {
                $service->delete();
            }

            $uploadedFile = $this->request->getFile('file');
            if ($uploadedFile) {
                $service->setUpload($uploadedFile);
                if (!$service->validate($errors)) {
                    return $this->error($errors);
                }

                $service->upload();
            }

            return $this->redirect($this->buildLink('groups', $group));
        }

        list($baseWidth, $baseHeight) = $service->getBaseDimensions();
        $params += [
            'group' => $group,
            'formAction' => $service->getFormAction(),
            'fieldLabel' => $service->getFormFieldLabel(),
            'canDelete' => $service->canDeleteExisting(),
            'baseWidth' => $baseWidth,
            'baseHeight' => $baseHeight
        ];

        return $this->view('Truonglv\Groups:Group\FormUpload', 'tlg_group_form_upload', $params);
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return \Truonglv\Groups\Service\Group\Editor
     */
    protected function setupGroupEditService(\Truonglv\Groups\Entity\Group $group)
    {
        /** @var \Truonglv\Groups\Service\Group\Editor $editor */
        $editor = $this->service('Truonglv\Groups:Group\Editor', $group);

        $input = $this->filter([
            'name' => 'str',
            'short_description' => 'str',
        ]);
        if (App::isEnabledLanguage()) {
            $input['language_code'] = $this->filter('language_code', 'str');
        }

        if ($group->canEditTags()) {
            $editor->setTags($this->filter('tags', 'str'));
        }

        /** @var Editor $editorPlugin */
        $editorPlugin = $this->plugin('XF:Editor');
        $description = $editorPlugin->fromInput('description');
        $editor->setDescription($description);

        $customFields = $this->filter('custom_fields', 'array');
        $editor->setCustomFields($customFields);

        $editor->getGroup()->bulkSet($input);

        return $editor;
    }

    protected function finalizeGroupEdit(\Truonglv\Groups\Service\Group\Editor $editor): void
    {
    }

    /**
     * @param mixed $groupId
     * @param array $with
     * @param bool $viewContent
     * @return \Truonglv\Groups\Entity\Group
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableGroup($groupId, array $with = [], bool $viewContent = false): \Truonglv\Groups\Entity\Group
    {
        return App::assertionPlugin($this)->assertGroupViewable($groupId, $with, $viewContent);
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return \Truonglv\Groups\Entity\Member
     */
    protected function setupNewMember(\Truonglv\Groups\Entity\Group $group)
    {
//        $visitor = \XF::visitor();
//        /** @var \Truonglv\Groups\Entity\Member $member */
//        $member = $this->em()->create('Truonglv\Groups:Member');
//
//        $member->group_id = $group->group_id;
//        $member->user_id = $visitor->user_id;
//        $member->username = $visitor->username;
//
//        if ($group->always_moderate_join) {
//            $member->member_role_id = '';
//            $member->member_state = App::MEMBER_STATE_MODERATED;
//        } else {
//            $member->member_state = App::MEMBER_STATE_VALID;
//            $member->member_role_id = App::MEMBER_ROLE_ID_MEMBER;
//        }
//
//        $member->alert = App::MEMBER_ALERT_OPT_ALL;
//
//        return $member;
        throw new LogicException(__METHOD__ . ' has been deprecated.');
    }

    /**
     * @return array
     */
    protected function getGroupViewExtraWith()
    {
        return ['full'];
    }

    /**
     * @param array $activities
     * @return array|bool
     */
    public static function getActivityDetails(array $activities)
    {
        return \Truonglv\Groups\ControllerPlugin\Assistant::getActivityDetails($activities);
    }
}
