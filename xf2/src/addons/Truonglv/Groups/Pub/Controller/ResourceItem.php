<?php

namespace Truonglv\Groups\Pub\Controller;

use XF;
use XF\Http\Upload;
use XF\Mvc\Reply\View;
use function strtolower;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use Truonglv\Groups\Listener;
use XF\Repository\Attachment;
use XF\ControllerPlugin\Delete;
use XF\ControllerPlugin\Editor;
use XF\Mvc\Reply\AbstractReply;
use XF\Pub\Controller\AbstractController;
use Truonglv\Groups\Service\ResourceItem\Icon;
use Truonglv\Groups\Service\ResourceItem\Creator;

class ResourceItem extends AbstractController
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        if (!App::hasPermission('view') || !App::isEnabledResources()) {
            throw $this->exception($this->noPermission());
        }

        $this->assertRegistrationRequired();
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

        /** @var \Truonglv\Groups\Entity\ResourceItem|null $resource */
        $resource = $reply->getParam('resource');
        if ($resource !== null && $resource->Group !== null) {
            $reply->setContainerKey('tlg-group-' . $resource->Group->group_id);
            $reply->setContentKey('tlg-group-resource-' . $resource->resource_id);
        }

        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = $reply->getParam('group');
        if (strtolower($action) === 'add' && $group !== null) {
            $reply->setContainerKey('tlg-group-' . $group->group_id);
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        $resource = $this->assertViewableResource($params['resource_id']);

        $this->assertCanonicalUrl($this->buildLink('group-resources', $resource));

        $page = $this->filterPage();
        $perPage = App::getOption('commentsPerPage');

        $finder = App::resourceRepo()->findCommentsForView($resource);

        $total = $finder->total();
        $this->assertValidPage($page, $perPage, $total, 'group-resources', $resource);

        $comments = $total > 0
            ? $finder->limitByPage($page, $perPage)->fetch()
            : $this->em()->getEmptyCollection();

        if (isset($comments[$resource->first_comment_id])) {
            unset($comments[$resource->first_comment_id]);
            $total--;
        }

        App::commentRepo()->addContentIntoComments($comments);
        if ($resource->Group !== null) {
            Listener::addContentLanguageResponseHeader($resource->Group);
        }

        App::resourceRepo()->logView($resource);

        return $this->view(
            'Truonglv\Groups:ResourceItem\View',
            'tlg_resource_view',
            [
                'resource' => $resource,
                'group' => $resource->Group,
                'comments' => $comments,
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
            ]
        );
    }

    public function actionAdd()
    {
        $groupId = $this->filter('group_id', 'uint');
        $group = App::assertionPlugin($this)->assertGroupViewable($groupId);

        if (!$group->canAddResource($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            /** @var Creator $creator */
            $creator = $this->service('Truonglv\Groups:ResourceItem\Creator', $group);

            $resource = $this->saveResourceData($creator);

            return $this->redirect($this->buildLink('group-resources', $resource));
        }

        $resource = $this->em()->create('Truonglv\Groups:ResourceItem');
        /** @var Attachment $attachmentRepo */
        $attachmentRepo = $this->repository('XF:Attachment');
        $attachmentData = $attachmentRepo->getEditorData(
            App::CONTENT_TYPE_RESOURCE,
            $group
        );

        return $this->view(
            'Truonglv\Groups:ResourceItem\Add',
            'tlg_resource_add',
            [
                'group' => $group,
                'resource' => $resource,
                'attachmentData' => $attachmentData,
            ]
        );
    }

    public function actionEdit(ParameterBag $params)
    {
        $resource = $this->assertViewableResource($params['resource_id']);
        if (!$resource->canEdit($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            /** @var \Truonglv\Groups\Service\ResourceItem\Editor $editor */
            $editor = $this->service('Truonglv\Groups:ResourceItem\Editor', $resource);
            $this->saveResourceData($editor);

            return $this->redirect($this->buildLink('group-resources', $resource));
        }

        /** @var Attachment $attachmentRepo */
        $attachmentRepo = $this->repository('XF:Attachment');
        $attachmentData = $attachmentRepo->getEditorData(
            App::CONTENT_TYPE_RESOURCE,
            $resource
        );

        return $this->view(
            'Truonglv\Groups:ResourceItem\Add',
            'tlg_resource_add',
            [
                'group' => $resource->Group,
                'resource' => $resource,
                'attachmentData' => $attachmentData,
            ]
        );
    }

    public function actionDelete(ParameterBag $params)
    {
        $resource = $this->assertViewableResource($params['resource_id']);
        if (!$resource->canDelete($error)) {
            return $this->noPermission($error);
        }

        /** @var Delete $deletePlugin */
        $deletePlugin = $this->plugin('XF:Delete');

        return $deletePlugin->actionDelete(
            $resource,
            $this->buildLink('group-resources/delete', $resource),
            $this->buildLink('group-resources', $resource),
            $this->buildLink('groups/resources', $resource->Group),
            $resource->title
        );
    }

    public function actionComment(ParameterBag $params)
    {
        $resource = $this->assertViewableResource($params['resource_id']);

        /** @var \Truonglv\Groups\ControllerPlugin\Comment $commentPlugin */
        $commentPlugin = $this->plugin('Truonglv\Groups:Comment');
        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $resource->Group;

        return $commentPlugin->actionComment($resource, $group);
    }

    public function actionDownload(ParameterBag $params)
    {
        $resource = $this->assertViewableResource($params['resource_id']);
        if (!$resource->canDownload($error)) {
            return $this->noPermission($error);
        }

        /** @var \XF\Entity\Attachment|null $attachment */
        $attachment = $this->finder('XF:Attachment')
            ->where('content_type', $resource->getEntityContentType())
            ->where('content_id', $resource->resource_id)
            ->where('unassociated', 0)
            ->fetchOne();
        if ($attachment === null) {
            return $this->noPermission();
        }

        $this->request()->set('no_canonical', true);
        App::resourceRepo()->logDownload($resource->resource_id);

        return $this->rerouteController('XF:Attachment', 'index', [
            'attachment_id' => $attachment->attachment_id
        ]);
    }

    public function actionLogs(ParameterBag $params)
    {
        $resource = $this->assertViewableResource($params['resource_id']);
        if (!$resource->canViewDownloadLogs($error)) {
            return $this->noPermission($error);
        }

        $page = $this->filterPage();
        $perPage = 20;

        $finder = $this->finder('Truonglv\Groups:ResourceDownloadLog');
        $finder->with('User', true);
        $finder->where('resource_id', $resource->resource_id);
        $finder->order('total', 'DESC');

        $total = $finder->total();
        $this->assertValidPage($page, $perPage, $total, 'group-resources/logs', $resource);

        $entities = $total > 0
            ? $finder->limitByPage($page, $perPage)->fetch()
            : $this->em()->getEmptyCollection();

        return $this->view(
            'Truonglv\Groups:ResourceItem\Users',
            'tlg_resource_download_logs',
            [
                'resource' => $resource,
                'group' => $resource->Group,
                'entities' => $entities,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage
            ]
        );
    }

    public function actionIcon(ParameterBag $params)
    {
        $resource = $this->assertViewableResource($params['resource_id']);
        if (!$resource->canEdit($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $iconType = $this->filter('icon_type', 'str');
            /** @var Icon $iconService */
            $iconService = $this->service('Truonglv\Groups:ResourceItem\Icon', $resource);
            if ($this->filter('delete', 'bool') === true) {
                $iconService->deleteIcons();
                ;
            }

            if ($iconType === 'url') {
                $iconService->setIconUrl($this->filter('icon_url', 'str'));
            } else {
                /** @var Upload|false $file */
                $file = $this->request()->getFile('resource_icon', false);
                if ($file === false) {
                    throw $this->exception($this->error(XF::phrase('provided_file_is_not_valid_image')));
                }
                $iconService->setUploadFile($file);
            }

            if (!$iconService->validate($errors)) {
                return $this->error($errors);
            }

            $iconService->save();

            return $this->redirect($this->buildLink('group-resources', $resource));
        }

        return $this->view(
            'Truonglv\Groups:ResourceItem\Icon',
            'tlg_resource_icon',
            [
                'resource' => $resource,
                'group' => $resource->Group,
            ]
        );
    }

    /**
     * @param Creator|\Truonglv\Groups\Service\ResourceItem\Editor $service
     * @return \Truonglv\Groups\Entity\ResourceItem
     */
    protected function saveResourceData($service)
    {
        $service->getPreparer()->setAttachmentHash(
            $this->filter('attachment_hash', 'str')
        );
        $service->getResource()->bulkSet($this->filter([
            'title' => 'str'
        ]));

        /** @var Editor $editorPlugin */
        $editorPlugin = $this->plugin('XF:Editor');
        $service->setMessage($editorPlugin->fromInput('message'));

        $iconType = $this->filter('icon_type', 'str');
        if ($iconType === 'remote') {
            $service->getPreparer()->setIconUrl($this->filter('icon_url', 'str'));
        } else {
            /** @var mixed $file */
            $file = $this->request()->getFile('resource_icon', false);
            if ($file instanceof Upload) {
                $service->getPreparer()->setIconFile($file);
            }
        }

        $errors = null;
        if (!$service->validate($errors)) {
            throw $this->exception($this->error($errors));
        }

        /** @var \Truonglv\Groups\Entity\ResourceItem $resource */
        $resource = $service->save();

        return $resource;
    }

    /**
     * @param int $id
     * @param string $with
     * @return \Truonglv\Groups\Entity\ResourceItem
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableResource($id, $with = 'fullView')
    {
        /** @var \Truonglv\Groups\Entity\ResourceItem $resource */
        $resource = $this->assertViewableRecord(
            'Truonglv\Groups:ResourceItem',
            $id,
            $with,
            'tlg_requested_resource_not_found'
        );

        return $resource;
    }
}
