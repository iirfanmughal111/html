<?php

namespace FS\ForumGroups\Pub\Controller;

use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use XF\Http\Upload;

use XF\Pub\Controller\AbstractController;
use FS\ForumGroups\Service\ForumGroups\AbstractFormUpload;
use XF\Template\Templater;
use InvalidArgumentException;

class ForumGroups extends AbstractController
{
    /**
     * @var RoomPath
     */
    protected $chatRoomURlPath;

    /**
     * @var Cover Image Width
     */
    protected $imageWidth = 918;

    /**
     * @var Cover Image Height
     */
    protected $imageHeight = 200;

    /**
     * @var Avatar Image Width
     */
    protected $avatarWidth = 250;

    /**
     * @var Avatar Image Height
     */
    protected $avatarHeight = 250;

    protected function preDispatchController($action, ParameterBag $params)
    {
        if (!\xf::visitor()->hasPermission('fs_forum_group_permission', 'add')) {
            throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        $this->checkUser();

        if ($params['node_id'] > 0) {
            return $this->actionSingleView($params);
        }

        $nodeFinder = \xf::finder('XF:Node')->where("parent_node_id", \xf::app()->options()->fs_forum_groups_applicable_forum);
        $tempNodeIds = $nodeFinder->pluckfrom('node_id')->fetch()->toArray();

        $forumFinder = \xf::finder('XF:Forum')->where("node_id", $tempNodeIds)->order('message_count', 'DESC');
        $nodeIds = $forumFinder->pluckfrom('node_id')->fetch()->toArray();

        $quoteNodeIds = \XF::db()->quote($nodeIds);

        $finder = null;

        if ($quoteNodeIds) {
            $setOrderFinder = \XF::finder('XF:Node');

            $finder = $setOrderFinder->where("node_id", $nodeIds)->where('node_state', 'visible')->where('user_id', \XF::visitor()->user_id)
                ->order($setOrderFinder->expression('FIELD(node_id, ' . $quoteNodeIds . ')'))
                ->fetch();
        }

        $viewParams = [
            "subForums" => $finder ?: '',
            "totalReturn" => $finder ? count($finder) : 0,
            "total" => $finder ? count($finder) : 0
        ];

        return $this->view('FS\ForumGroups:ForumGroups\Index', 'fs_forum_groups_index', $viewParams);
    }

    public function actionSingleView($params)
    {
        $this->checkUser();
        $this->matchUser($params);

        $forum = $this->assertViewableForum($params->node_id ?: $params->node_name, $this->getForumViewExtraWith());

        $threadRepo = $this->getThreadRepo();

        $threadList = $threadRepo->findThreadsForForumView(
            $forum,
            [
                'allowOwnPending' => $this->hasContentPendingApproval()
            ]
        );

        $threadList->where('sticky', 0);

        /** @var \XF\Entity\Thread[]|\XF\Mvc\Entity\AbstractCollection $threads */
        $threads = $threadList->fetch();

        $subCommunity = \XF::em()->find('XF:Node', $params->node_id);

        $viewParams = [
            'forum' => $forum,
            'threads' => $threads,
            "subForums" => $subCommunity ?: '',
        ];

        return $this->view('FS\ForumGroups:ForumGroups\Index', 'fs_forum_groups_view', $viewParams);
    }

    protected function getForumViewExtraWith()
    {
        $extraWith = [];
        $userId = \XF::visitor()->user_id;
        if ($userId) {
            $extraWith[] = 'Watch|' . $userId;
        }

        return $extraWith;
    }

    protected function nodeAddEdit(\XF\Entity\Node $node)
    {
        $nodeRepo = $this->repository('XF:Node');
        $nodeTree = $nodeRepo->createNodeTree($nodeRepo->getFullNodeList());

        /** @var \XF\Repository\Style $styleRepo */
        $styleRepo = $this->repository('XF:Style');
        $styleTree = $styleRepo->getStyleTree(false);

        /** @var \XF\Repository\Navigation $navRepo */
        $navRepo = $this->repository('XF:Navigation');
        $navChoices = $navRepo->getTopLevelEntries();

        $room = $this->em()->create('Siropu\Chat:Room');
        $roomParams = $this->roomAddEdit($room);

        $viewParams = [
            'node' => $node,
            'forum' => $node->getDataRelationOrDefault(),
            'nodeTree' => $nodeTree,
            'styleTree' => $styleTree,
            'navChoices' => $navChoices,
            'avatarWidth' => $this->avatarWidth,
            'avatarHeight' => $this->avatarHeight,
            'coverWidth' => $this->imageWidth,
            'coverHeight' => $this->imageHeight
        ] + $roomParams;

        return $this->view('FS\ForumGroups:ForumGroups\Index', 'fs_forum_groups_sub_forum_add_edit', $viewParams);
    }

    protected function roomAddEdit(\Siropu\Chat\Entity\Room $room)
    {
        $rooms = $this->getRoomRepo()
            ->findRoomsForList()
            ->notFromRoom($room->room_id)
            ->fetch()
            ->pluckNamed('room_name', 'room_id');

        $viewParams = [
            'room'       => $room,
            'rooms'      => $rooms,
            'userGroups' => $this->repository('XF:UserGroup')->findUserGroupsForList()->fetch(),
            'languages'  => $this->repository('XF:Language')->getLanguageTree(false)->getFlattened()
        ];

        return $viewParams;
    }

    public function roomSave()
    {
        $this->assertPostOnly();

        $visitor = \XF::visitor();
       

        if (!$visitor->canCreateSiropuChatRooms()) {
            return $this->noPermission();
        }

        $room = $this->em()->create('Siropu\Chat:Room');
     

        if ($visitor->canPasswordProtectSiropuChatRooms()) {
            $room->room_password = $this->filter('room_password', 'str');
        }


        if ($visitor->canEditSiropuChatRoomSettings()) {
            $room->room_user_groups  = $this->filter('room_user_groups', 'array-uint');
            $room->room_leave        = $this->filter('room_leave', 'uint');
            $room->room_readonly     = $this->filter('room_readonly', 'bool');
            $room->room_locked       = $this->filter('room_locked', 'datetime');
            $room->room_rss          = $this->filter('room_rss', 'bool');
            $room->room_max_users    = $this->filter('room_max_users', 'uint');
            $room->room_prune        = $this->filter('room_prune', 'uint');
            $room->room_flood        = $this->filter('room_flood', 'uint');
            $room->room_thread_id    = $this->filter('room_thread_id', 'uint');
            $room->room_thread_reply = $this->filter('room_thread_reply', 'bool');
            $room->room_language_id  = $this->filter('room_language_id', 'uint');
            $room->room_child_ids    = $this->filter('room_child_ids', 'array-uint');

            if ($room->room_prune) {
                if (!$room->room_last_prune) {
                    $room->room_last_prune = \XF::$time;
                }
            } else {
                $room->room_last_prune = 0;
            }
        }

        $users = $this->filter('room_users', 'str');
       
        if ($visitor->canSetSiropuChatRoomUsers()) {
            if (!empty($users)) {
                $userFinder = $this->finder('XF:User')
                    ->where('username', array_map('trim', explode(',', $users)))
                    ->fetch()
                    ->filter(function (\XF\Entity\User $user) {
                        return ($user->canUseSiropuChat() && $user->canJoinSiropuChatRooms());
                    });

                if ($userFinder->count()) {
                    $room->room_users = $userFinder->pluckNamed('username', 'user_id');
                } else {
                    return $this->message(\XF::phrase('siropu_chat_room_users_no_valid'));
                }
            } else {
                $room->room_users = [];
            }
        }

        $input = $this->filter([
            'room_name'        => 'str',
            'room_description' => 'str'
        ]);

        $room->bulkSet($input);
        $room->save();

        $parts = explode('/room/', $this->buildLink('chat/room/', $room));

        if (count($parts) > 1) {
            $nextValue = $parts[1];
        }

        $replaceRoute = 'chat/room/' . $nextValue;

        $this->addRouteFilter($replaceRoute, 'chat/' . $this->filter('replace_route', 'str'));

        $this->chatRoomURlPath = $replaceRoute;
        if ($this->filter('join_room', 'bool')) {
            return $this->plugin('Siropu\Chat:Room')->joinRoom($room);
        }

        if ($room->isUpdate()) {
            return $this->message(\XF::phrase('your_changes_have_been_saved'));
        }

        $reply = $this->view('Siropu\Chat:Room\List', 'siropu_chat_room_list');
        $reply->setJsonParam('room_id', $room->room_id);

        return $room;
    }

    public function actionAdd()
    {
        $this->checkUser();

        /** @var \XF\Entity\Node $node */
        $node = $this->em()->create('XF:Node');
        $node->node_type_id = "Forum";
        $node->parent_node_id = intval($this->app()->options()->fs_forum_groups_applicable_forum);
        return $this->nodeAddEdit($node);
    }

    public function actionSave(ParameterBag $params)
    {
        $this->checkUser();
        $this->matchUser($params);

        if ($params['node_id']) {
            $this->isApproved($params['node_id']);
            $node = $this->assertNodeExists($params['node_id']);
        } else {
            /** @var \XF\Entity\Node $node */
            $node = $this->em()->create('XF:Node');
            $node->node_type_id = "Forum";
        }

        $this->validateAvatarImage();
        $this->validateCoverImage();

        $this->roomSave();

        $this->nodeSaveProcess($node)->run();

        $node->fastUpdate('user_id', \XF::visitor()->user_id);

        $node->fastUpdate('room_path', $this->chatRoomURlPath);

        $this->addRouteFilter('forums/' . $node->title . '.' . $node->node_id . '/', $this->filter('replace_route', 'str'));

        $this->setGroupImages($node, 'avatarFile', 'FS\ForumGroups:ForumGroups\Avatar');
        $this->setGroupImages($node, 'coverFile', 'FS\ForumGroups:ForumGroups\Cover');

        $this->permissionRebuild();

        return $this->redirect($this->buildLink('forumGroups/' . $node->node_id));
    }

    protected function nodeSaveProcess(\XF\Entity\Node $node)
    {
        $form = $this->formAction();

        $forumTitleDesc = $this->filter([
            'node' => [
                'title' => 'str',
                'description' => 'str',
            ]
        ]);

        $input = [
            'node' => [
                'title' => $forumTitleDesc['node']['title'],
                'node_name' => '',
                'description' => $forumTitleDesc['node']['description'],
                'parent_node_id' => \xf::app()->options()->fs_forum_groups_applicable_forum,
                'display_order' => '',
                'display_in_list' => true,
                'style_id' => '',
                'navigation_id' => ''
            ]
        ];

        if (!$this->filter('style_override', 'bool')) {
            $input['node']['style_id'] = 0;
        }

        $data = $node->getDataRelationOrDefault(false);
        $node->addCascadedSave($data);

        $form->basicEntitySave($node, $input['node']);
        $this->saveTypeData($form, $node, $data);

        return $form;
    }

    protected function saveTypeData(FormAction $form, \XF\Entity\Node $node, \XF\Entity\AbstractNode $data)
    {
        $forumType = $this->getForumTypeHandlerForAddEdit($node);
        if (!$forumType) {
            $form->logError(\XF::phrase('forum_type_handler_not_found'), 'forum_type_id');
            return;
        }

        $forumInput = [
            'allow_posting' => 'true',
            'moderate_threads' => 'false',
            'moderate_replies' => 'false',
            'count_messages' => 'true',
            'find_new' => 'true',
            'allowed_watch_notifications' => 'all',
            'default_sort_order' => 'last_post_date',
            'default_sort_direction' => 'desc',
            'list_date_limit_days' => 0,
            'default_prefix_id' => 0,
            'require_prefix' => false,
            'min_tags' => 0,
            'allow_index' => 'allow'
        ];

        $forumInput['index_criteria'] = $this->filterIndexCriteria();

        /** @var \XF\Entity\Forum $data */
        $data->bulkSet($forumInput);
        $data->forum_type_id = $forumType->getTypeId();

        $typeConfig = $forumType->setupTypeConfigSave($form, $node, $data, $this->request);
        if ($typeConfig instanceof \XF\Mvc\Entity\ArrayValidator) {
            if ($typeConfig->hasErrors()) {
                $form->logErrors($typeConfig->getErrors());
            }
            $typeConfig = $typeConfig->getValuesForced();
        }

        $data->type_config = $typeConfig;
    }

    protected function setGroupImages($node, $varName, $serviceName)
    {
        $forumGroupImage = $this->service($serviceName, $node);

        $uploadedFile = $this->request->getFile($varName);

        if ($uploadedFile) {
            $forumGroupImage->setUpload($uploadedFile);
            if (!$forumGroupImage->validate($errors)) {
                return $this->error($errors);
            }

            $forumGroupImage->upload();
        }
    }

    protected function validateAvatarImage()
    {
        $uploadedFile = $this->request->getFile('avatarFile');

        if ($uploadedFile) {
            $uploadedFile->requireImage();

            if (!$uploadedFile->isValid($errors)) {
                throw $this->exception($this->error(\XF::phrase('uploaded_file_must_be_valid_image'), 404));
            }

            $imageInfo = @getimagesize($uploadedFile->getTempFile());

            $width = (int) $imageInfo[0];
            $height = (int) $imageInfo[1];

            if ($width < $this->avatarWidth || $height < $this->avatarHeight) {
                throw $this->exception($this->error(\XF::phrase('fs_group_please_upload_image_at_least_xy_pixels', [
                    'width' => $this->avatarWidth,
                    'height' => $this->avatarHeight
                ]), 404));
            } elseif (!$this->app->imageManager()->canResize($width, $height)) {
                throw $this->exception($this->error(\XF::phrase('uploaded_image_is_too_big'), 404));
            }
        }
    }

    protected function validateCoverImage()
    {
        $uploadedFile = $this->request->getFile('coverFile');

        if ($uploadedFile) {
            $uploadedFile->requireImage();

            if (!$uploadedFile->isValid($errors)) {
                throw $this->exception($this->error(\XF::phrase('uploaded_file_must_be_valid_image'), 404));
            }


            $imageInfo = @getimagesize($uploadedFile->getTempFile());

            $width = (int) $imageInfo[0];
            $height = (int) $imageInfo[1];

            if ($width < $this->imageWidth || $height < $this->imageHeight) {
                throw $this->exception($this->error(\XF::phrase('fs_group_please_upload_image_at_least_xy_pixels', [
                    'width' => $this->imageWidth,
                    'height' => $this->imageHeight
                ]), 404));
            } elseif (!$this->app->imageManager()->canResize($width, $height)) {
                throw $this->exception($this->error(\XF::phrase('uploaded_image_is_too_big'), 404));
            }
        }
    }

    /**
     * @param \XF\Entity\Node $node
     *
     * @return \XF\ForumType\AbstractHandler|null
     */
    protected function getForumTypeHandlerForAddEdit(\XF\Entity\Node $node)
    {
        /** @var \XF\Entity\Forum $forum */
        $forum = $node->getDataRelationOrDefault(false);

        if (!$node->exists()) {
            $forumTypeId = "discussion";
            return $this->app->forumType($forumTypeId, false);
        } else {
            return $forum->TypeHandler;
        }
    }

    /**
     * @return array
     */
    protected function filterIndexCriteria()
    {
        $criteria = [];

        $input = [
            'max_days_post' => [
                'enabled' => false,
                'value' => 0
            ],
            'max_days_last_post' => [
                'enabled' => false,
                'value' => 0
            ],
            'min_replies' => [
                'enabled' => false,
                'value' => 0
            ],
            'min_reaction_score' => [
                'enabled' => false,
                'value' => 0
            ]
        ];

        foreach ($input as $rule => $criterion) {
            if (!$criterion['enabled']) {
                continue;
            }

            $criteria[$rule] = $criterion['value'];
        }

        return $criteria;
    }

    protected function addRouteFilter($findRoute, $replaceRoute)
    {
        $routeFilter = $this->em()->create('XF:RouteFilter');

        $this->routeFilterSaveProcess($routeFilter, $findRoute, $replaceRoute)->run();
    }


    protected function routeFilterSaveProcess(\XF\Entity\RouteFilter $routeFilter, $findRoute, $replaceRoute)
    {
        $form = \xf::app()->formAction();

        $input = [
            'find_route' => $findRoute,
            'replace_route' => $replaceRoute,
            'url_to_route_only' => '',
            'enabled' => 'true'
        ];
        $form->basicEntitySave($routeFilter, $input);

        return $form;
    }


    public function actionAddModerator(ParameterBag $params)
    {
        $this->isApproved($params['node_id']);

        $this->checkUser();
        $this->matchUser($params);

        $input = $this->filter([
            'username' => 'str',
            'type' => 'str',
            'type_id' => 'array-uint'
        ]);

        if ($input['username'] === '' || $input['type'] === '') {
            $viewParams = [
                'typeHandlers' => $this->app->getContentTypeField('moderator_handler_class'),
                'type' => $input['type'],
                'typeId' => $input['type_id'],
                'subForum' => \XF::em()->find('XF:Node', $params->node_id)
            ];
            return $this->view('XF:Moderator\AddChoice', 'moderator_add_choice', $viewParams);
        }

        $moderatorFields = $this->filter([
            'username' => 'str',
            'type' => 'str',
        ]);

        $input = [
            'username' => $moderatorFields['username'],
            'type' => $moderatorFields['type'],
            "type_id" => $moderatorFields['type'] == '_super' ? [] :
                [
                    "node" =>
                    $params->node_id
                ]
        ];

        $user = $this->finder('XF:User')->where('username', $input['username'])->fetchOne();
        if (!$user) {
            return $this->error(\XF::phrase('requested_user_not_found'));
        }

        $generalModerator = $this->em()->find('XF:Moderator', $user->user_id);
        if (!$generalModerator) {
            $generalModerator = $this->em()->create('XF:Moderator');
            $generalModerator->user_id = $user->user_id;
            $generalModerator->is_super_moderator = ($input['type'] == '_super');
        }

        if ($input['type'] != '_super') {
            $handler = $this->getModRepo()->getModeratorHandler($input['type']);
            if (!$handler) {
                return $this->error(\XF::phrase('please_choose_valid_moderator_type'), 404);
            }

            $contentId = $input['type_id'][$input['type']] ?? 0;
            if (!$handler->getContentTitle($contentId)) {
                return $this->error(\XF::phrase('please_select_a_valid_type_of_moderator'), 404);
            }

            $contentModerator = $this->finder('XF:ModeratorContent')
                ->where([
                    'content_type' => $input['type'],
                    'content_id' => $contentId,
                    'user_id' => $user->user_id
                ])
                ->fetchOne();

            if (!$contentModerator) {
                $contentModerator = $this->em()->create('XF:ModeratorContent');

                $contentModerator->content_type = $input['type'];
                $contentModerator->content_id = $contentId;
                $contentModerator->user_id = $user->user_id;
            }
        } else {
            $contentModerator = null;
        }

        return $this->moderatorAddEdit($generalModerator, $contentModerator);
    }

    public function actionContentEdit(ParameterBag $params)
    {
        // moderator_id replace with node_id

        $this->checkUser();

        $contentModerator = $this->assertContentModeratorExists($params['node_id']);
        $generalModerator = $this->assertGeneralModeratorExists($contentModerator->user_id);
        return $this->moderatorAddEdit($generalModerator, $contentModerator);
    }

    protected function moderatorAddEdit(
        \XF\Entity\Moderator $generalModerator,
        \XF\Entity\ModeratorContent $contentModerator = null
    ) {
        /** @var \XF\Repository\PermissionEntry $permissionEntryRepo */
        $permissionEntryRepo = $this->repository('XF:PermissionEntry');

        $modRepo = $this->getModRepo();

        $existingPermissionValues = $permissionEntryRepo->getGlobalUserPermissionEntries($generalModerator->user_id);

        if ($contentModerator) {
            $moderatorHandler = $modRepo->getModeratorHandler($contentModerator->content_type);
            if (!$moderatorHandler) {
                return $this->error(\XF::phrase('this_content_moderator_relates_to_unknown_content_type'));
            }

            $contentTitle = $moderatorHandler->getContentTitle($contentModerator->content_id);

            $contentPermissionValues = $permissionEntryRepo->getContentUserPermissionEntries(
                $contentModerator->content_type,
                $contentModerator->content_id,
                $contentModerator->user_id
            );
            $existingPermissionValues = \XF\Util\Arr::mapMerge($existingPermissionValues, $contentPermissionValues);
        } else {
            $contentTitle = '';
        }

        $user = $generalModerator->User;


        $moderatorPermissionData = $this->getModeratorPermissionData(
            $contentModerator ? $contentModerator->content_type : null
        );

        $viewParams = [
            'user' => $user,
            'generalModerator' => $generalModerator,
            'contentModerator' => $contentModerator,

            'contentTitle' => $contentTitle,
            'isStaff' => $generalModerator->exists() ? $user->is_staff : true,

            'existingValues' => $existingPermissionValues,
            'allowValues' => ['allow', 'content_allow'],

            'interfaceGroups' => $moderatorPermissionData['interfaceGroups'],
            'globalPermissions' => $moderatorPermissionData['globalPermissions'],
            'contentPermissions' => $moderatorPermissionData['contentPermissions'],

            'userGroups' => $this->em()->getRepository('XF:UserGroup')->getUserGroupTitlePairs()
        ];

        return $this->view('XF:Moderator\Edit', 'moderator_edit', $viewParams);
    }

    public function getModeratorPermissionData($contentType = null)
    {
        $moderationIds = explode(",", "editAnyMessage,deleteAnyMessage,viewHiddenUsers,changeAuthor,editNotice,editRules,editAds,editRoomSettings,postAnnouncements,stickUnstickThread,lockUnlockThread,deleteAnyThread,threadReplyBan,editAnyPost,deleteAnyPost,warn,manageAnyTag,viewDeleted,viewModerated,undelete,approveUnapprove,markSolutionAnyThread");
        $permissionGroupsIds = explode(",", "siropuChatModerator,siropuChatModerator,siropuChatModerator,siropuChatModerator,siropuChatAdmin,siropuChatAdmin,siropuChatAdmin,siropuChatAdmin,siropuChatAdmin,forum,forum,forum,forum,forum,forum,forum,forum,forum,forum,forum,forum,forum");

        /** @var \XF\Repository\Permission $permissionRepo */
        $permissionRepo = $this->repository('XF:Permission');

        $contentHandler = $contentType ? $permissionRepo->getPermissionHandler($contentType) : null;

        $permissions = $permissionRepo->findPermissionsForList()
            ->where('permission_type', 'flag')->where("permission_id", $moderationIds)
            ->where("permission_group_id", $permissionGroupsIds) // all that's supported
            ->fetch();

        $interfaceGroups = $permissionRepo->findInterfaceGroupsForList()->where('is_moderator', 1)->fetch();

        $globalPermissions = [];
        $contentPermissions = [];

        foreach ($permissions as $key => $permission) {
            if (!isset($interfaceGroups[$permission->interface_group_id])) {
                continue;
            }

            if ($contentHandler && $contentHandler->isValidPermission($permission)) {
                $contentPermissions[$permission->interface_group_id][] = $permission;
            } else {
                $globalPermissions[$permission->interface_group_id][] = $permission;
            }
        }

        return [
            'interfaceGroups' => $interfaceGroups,
            'contentPermissions' => $contentPermissions,
            'globalPermissions' => $globalPermissions
        ];
    }

    public function actionModeratorSave(ParameterBag $params)
    {
        $this->checkUser();

        $this->assertPostOnly();

        $findInput = $this->filter([
            'user_id' => 'uint',
            'content_type' => 'str',
            'content_id' => 'uint'
        ]);

        $user = $this->assertRecordExists('XF:User', $findInput['user_id']);

        $generalModerator = $this->em()->find('XF:Moderator', $user->user_id);
        if (!$generalModerator) {
            $generalModerator = $this->em()->create('XF:Moderator');
            $generalModerator->user_id = $user->user_id;
        }

        $contentModerator = null;
        if ($findInput['content_type'] && $findInput['content_id']) {
            $contentModerator = $this->finder('XF:ModeratorContent')->where($findInput)->fetchOne();
            if (!$contentModerator) {
                $contentModerator = $this->em()->create('XF:ModeratorContent');
                $contentModerator->bulkSet($findInput);
            }
        }

        if (!$contentModerator) {
            $generalModerator->is_super_moderator = true;
        }

        $this->moderatorSaveProcess($generalModerator, $contentModerator)->run();

        return $this->redirect($this->buildLink('forumGroups/' . $findInput['content_id'] . '/moderator-list'));
    }

    protected function moderatorSaveProcess(
        \XF\Entity\Moderator $generalModerator,
        \XF\Entity\ModeratorContent $contentModerator = null
    ) {
        $form = $this->formAction();

        $input = $this->filter([
            'extra_user_group_ids' => 'array-uint',
            'globalPermissions' => 'array',
            'contentPermissions' => 'array',
            'is_staff' => 'bool'
        ]);

        $user = $generalModerator->User;

        $form->basicEntitySave($user, [
            'is_staff' => $input['is_staff']
        ]);

        /** @var \XF\Service\UpdatePermissions $permissionUpdater */
        $permissionUpdater = $this->service('XF:UpdatePermissions');
        $permissionUpdater->setUser($user);

        $form->basicEntitySave($generalModerator, [
            'extra_user_group_ids' => $input['extra_user_group_ids']
        ]);
        $form->apply(function () use ($permissionUpdater, $input) {
            $permissionUpdater->setGlobal();
            $permissionUpdater->updatePermissions($input['globalPermissions']);
        });

        if ($contentModerator) {
            $form->basicEntitySave($contentModerator, []);

            $form->complete(function () use ($permissionUpdater, $contentModerator, $input) {
                $permissionUpdater->setContent($contentModerator->content_type, $contentModerator->content_id);
                $permissionUpdater->updatePermissions($input['contentPermissions']);
            });
        }

        return $form;
    }

    public function actionModeratorList(ParameterBag $params)
    {
        $this->isApproved($params['node_id']);

        $this->checkUser();

        $modRepo = $this->getModRepo();

        // $superModerators = $this->findModeratorsForList(true)->fetch();

        $contentModFinder = $this->findContentModeratorsForList($params->node_id);

        $userIdFilter = $this->filter('user_id', 'uint');
        if ($userIdFilter) {
            $moderator = $this->assertGeneralModeratorExists($userIdFilter);
            $displayLimit = null;

            $contentModFinder->where('user_id', $moderator->user_id);
        } else {
            $moderator = null;
            $displayLimit =  10;
        }

        $contentModerators = $contentModFinder
            ->where('content_type', array_keys($modRepo->getModeratorHandlers()))
            ->fetch();

        $groupedModerators = $modRepo->getGroupedContentModeratorsForList(
            $contentModerators,
            $displayLimit
        );
        $contentModeratorTotals = $modRepo->getContentModeratorTotals();

        $users = $this->finder('XF:User')
            ->whereIds(array_keys($contentModeratorTotals))
            ->order('username')
            ->fetch();

        $viewParams = [
            // 'superModerators' => $superModerators,
            'contentModerators' => $groupedModerators,
            'contentModeratorTotals' => $contentModeratorTotals,
            'displayLimit' => $displayLimit,
            'users' => $users,
            'userIdFilter' => $userIdFilter,
            'forumGroup' => $params
        ];
        return $this->view('XF:Moderator\Listing', 'fs_forum_groups_moderator_list', $viewParams);
    }

    /**
     * @return Finder
     */
    public function findModeratorsForList($isSuperModerator = false)
    {
        $finder = $this->finder('XF:Moderator')->where('user_id', \XF::visitor()->user_id)
            ->with('User', true)
            ->order('User.username');

        if ($isSuperModerator) {
            $finder->where('is_super_moderator', 1);
        }

        return $finder;
    }

    /**
     * @return Finder
     */
    public function findContentModeratorsForList($Id)
    {
        return $this->finder('XF:ModeratorContent')->where('content_id', $Id)
            ->with(['User', 'Moderator'])
            ->order(['content_id', 'content_type']);
    }

    public function actionContentDelete(ParameterBag $params)
    {
        $this->checkUser();

        // moderator_id replace with node_id

        $contentModerator = $this->assertContentModeratorExists($params['node_id']);
        $handler = $this->getModRepo()->getModeratorHandler($contentModerator->content_type);
        $contentTitle = $handler->getContentTitle($contentModerator->content_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $contentModerator,
            $this->buildLink('forumGroups/content/delete', $contentModerator),
            $this->buildLink('forumGroups/content/edit', $contentModerator),
            $this->buildLink('forumGroups'),
            sprintf(
                "%s %s%s%s",
                $contentModerator->User->username,
                \XF::language()->parenthesis_open,
                $contentTitle,
                \XF::language()->parenthesis_close
            )
        );
    }

    public function actionAvatar(ParameterBag $params)
    {
        $this->isApproved($params['node_id']);

        $this->checkUser();
        $this->matchUser($params);

        $subCommunity = $this->assertNodeExists($params['node_id']);

        /** @var \FS\ForumGroups\Service\ForumGroups\Avatar $avatar */
        $avatar = $this->service('FS\ForumGroups:ForumGroups\Avatar', $subCommunity);

        return $this->handleAvatarOrCoverProcess($subCommunity, $avatar);
    }

    public function actionCover(ParameterBag $params)
    {
        $this->isApproved($params['node_id']);

        $this->checkUser();
        $this->matchUser($params);

        $subCommunity = $this->assertNodeExists($params['node_id']);

        /** @var \FS\ForumGroups\Service\ForumGroups\Cover $cover */
        $cover = $this->service('FS\ForumGroups:ForumGroups\Cover', $subCommunity);
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

                return $this->redirect($this->buildLink('forumGroups', $subCommunity));
            }

            return $this->view('FS\ForumGroups:ForumGroups\CoverReposition', 'fs_forum_groups_cover_reposition', [
                'group' => $subCommunity
            ]);
        }

        return $this->handleAvatarOrCoverProcess($subCommunity, $cover);
    }

    /**
     * @param \XF\Entity\Node $subCommunity
     * @param AbstractFormUpload $service
     * @param array $params
     * @return \XF\Mvc\Reply\AbstractReply
     */
    protected function handleAvatarOrCoverProcess(
        \XF\Entity\Node $subCommunity,
        AbstractFormUpload $service,
        array $params = []
    ) {
        if ($this->isPost()) {
            if (
                $this->filter('delete', 'bool') === true
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

            return $this->redirect($this->buildLink('forumGroups', $subCommunity));
        }

        list($baseWidth, $baseHeight) = $service->getBaseDimensions();
        $params += [
            'subCommunity' => $subCommunity,
            'formAction' => $service->getFormAction(),
            'fieldLabel' => $service->getFormFieldLabel(),
            'canDelete' => $service->canDeleteExisting(),
            'baseWidth' => $baseWidth,
            'baseHeight' => $baseHeight
        ];

        return $this->view('FS\ForumGroups:ForumGroups\FormUpload', 'fs_forum_groups_form_upload', $params);
    }

    public function permissionRebuild()
    {

        // $userGroup = \xf::app()->finder('XF:UserGroup')->whereId(2)->fetchOne();


        // $permissions = [
        //     'general' => [

        //         'viewNode' => 'content_allow',
        //     ],
        //     'forum' => [

        //         'postThread' => 'content_allow',
        //         'postReply' => 'content_allow',
        //     ]
        // ];



        // $permissionUpdater = \xf::app()->service('XF:UpdatePermissions');
        // $permissionUpdater->setContent("node", $node->node_id)->setUserGroup($userGroup);
        // $permissionUpdater->updatePermissions($permissions);

        $userGroups = $this->getUserGroupRepo()->findUserGroupsForList()->fetch();

        if (count($userGroups)) {
            $permissionUpdater = \xf::app()->service('XF:UpdatePermissions');
            foreach ($userGroups as $group) {

                $permissionUpdater->setUserGroup($group)->setGlobal();
                if (\xf::app()->container()->isCached('permission.builder')) {
                    \xf::app()->permissionBuilder()->refreshData();
                }

                $permissionUpdater->triggerCacheRebuild();
            }
        }
    }

    public function getUserGroupRepo()
    {
        return \xf::app()->repository('XF:UserGroup');
    }

    /**
     * @return \XF\Repository\Moderator
     */
    protected function getModRepo()
    {
        return $this->repository('XF:Moderator');
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \XF\Entity\Moderator
     */
    protected function assertGeneralModeratorExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('XF:Moderator', $id, $with, $phraseKey);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \XF\Entity\ModeratorContent
     */
    protected function assertContentModeratorExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('XF:ModeratorContent', $id, $with, $phraseKey);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \XF\Entity\Node
     */
    protected function assertNodeExists($id, $with = null, $phraseKey = null)
    {
        $node = $this->assertRecordExists('XF:Node', $id, $with, $phraseKey);
        if ($node->node_type_id != "Forum") {
            throw $this->exception($this->error(\XF::phrase('requested_node_not_found'), 404));
        }
        return $node;
    }

    /**
     * @return \XF\Repository\Thread
     */
    protected function getThreadRepo()
    {
        return $this->repository('XF:Thread');
    }

    /**
     * @param string|int $nodeIdOrName
     * @param array $extraWith
     *
     * @return \XF\Entity\Forum
     *
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableForum($nodeIdOrName, array $extraWith = [])
    {
        if ($nodeIdOrName === null) {
            throw $this->exception($this->notFound(\XF::phrase('requested_forum_not_found')));
        }

        $visitor = \XF::visitor();
        $extraWith[] = 'Node.Permissions|' . $visitor->permission_combination_id;
        if ($visitor->user_id) {
            $extraWith[] = 'Read|' . $visitor->user_id;
        }

        $finder = $this->em()->getFinder('XF:Forum');
        $finder->with('Node', true)->with($extraWith);
        if (is_int($nodeIdOrName) || $nodeIdOrName === strval(intval($nodeIdOrName))) {
            $finder->where('node_id', $nodeIdOrName);
        } else {
            $finder->where(['Node.node_name' => $nodeIdOrName, 'Node.node_type_id' => 'Forum']);
        }

        /** @var \XF\Entity\Forum $forum */
        $forum = $finder->fetchOne();
        if (!$forum) {
            throw $this->exception($this->notFound(\XF::phrase('requested_forum_not_found')));
        }
        if (!$forum->canView($error)) {
            throw $this->exception($this->noPermission($error));
        }

        $this->plugin('XF:Node')->applyNodeContext($forum->Node);

        return $forum;
    }

    public function getRoomRepo()
    {
        return $this->repository('Siropu\Chat:Room');
    }

    protected function checkUser()
    {
        if (!\XF::visitor()->user_id) {
            throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
        }
    }

    protected function isApproved($nodeId)
    {
        $node = $this->assertNodeExists($nodeId);

        if ($node['node_state'] != 'visible') {
            throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
        }
    }

    protected function matchUser($params)
    {
        if ($params['node_id']) {
            $node = $this->assertNodeExists($params['node_id']);

            if (!$node) {
                throw $this->exception($this->notFound(\XF::phrase('invalid_page_requested')));
            }

            if (\XF::visitor()->user_id != $node->user_id) {
                throw $this->exception($this->notFound(\XF::phrase('do_not_have_permission')));
            }
        }
    }
}