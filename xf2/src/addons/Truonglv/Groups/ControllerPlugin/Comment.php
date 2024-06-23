<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\ControllerPlugin;

use XF\Mvc\Entity\Entity;
use XF\ControllerPlugin\Editor;
use Truonglv\Groups\Entity\Group;
use XF\ControllerPlugin\AbstractPlugin;
use Truonglv\Groups\Service\Event\Creator;

class Comment extends AbstractPlugin
{
    /**
     * @param Entity $content
     * @param Group $group
     * @param array $params
     * @return mixed
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionComment(Entity $content, Group $group, array $params = [])
    {
        $this->assertPostOnly();

        if (method_exists($content, 'canComment')) {
            $error = null;
            if (!$content->canComment($error)) {
                return $this->noPermission($error);
            }
        }

        /** @var Creator $creator */
        $creator = $this->service('Truonglv\Groups:Comment\Creator', $content);
        /** @var \Truonglv\Groups\Entity\Comment $comment */
        $comment = $this->saveCommentProcess(
            $creator,
            $group->canUploadAndManageAttachments()
        );

        $creator->sendNotifications();

        return $this->view('Truonglv\Groups:Comment\Item', 'tlg_comment_item', array_merge([
            'comment' => $comment,
            'content' => $content,
            'group' => $group,
            // 'expandedLayout' => false,
        ], $params));
    }

    /**
     * @param mixed $service
     * @param bool $canUploadAttachments
     * @param string $inputName
     * @return Entity
     * @throws \XF\Mvc\Reply\Exception
     */
    public function saveCommentProcess($service, bool $canUploadAttachments, $inputName = 'message')
    {
        if ($canUploadAttachments) {
            $service->setAttachmentHash($this->filter('attachment_hash', 'str'));
        }

        /** @var Editor $editorPlugin */
        $editorPlugin = $this->plugin('XF:Editor');
        $service->setMessage($editorPlugin->fromInput($inputName));

        $errors = null;
        if (!$service->validate($errors)) {
            throw $this->controller->errorException($errors);
        }

        return $service->save();
    }
}
