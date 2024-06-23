<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Api\Controller;

use XF;
use Truonglv\Groups\App;
use Truonglv\Groups\Service\Post\Creator;
use XF\Api\Controller\AbstractController;

class Posts extends AbstractController
{
    public function actionPost()
    {
        $this->assertRequiredApiInput(['message', 'group_id']);

        $group = $this->assertViewableGroup($this->filter('group_id', 'uint'));
        $error = null;
        if (XF::isApiCheckingPermissions() && !$group->canAddPost($error)) {
            return $this->noPermission();
        }

        /** @var Creator $creator */
        $creator = $this->service('Truonglv\Groups:Post\Creator', $group);

        $creator->setMessage($this->filter('message', 'str'));
        if ($group->canUploadAndManageAttachments()
            && $this->request()->exists('attachment_key')
        ) {
            $hash = $this->getAttachmentTempHashFromKey(
                $this->filter('attachment_key', 'str'),
                App::CONTENT_TYPE_COMMENT,
                ['group_id' => $group->group_id]
            );
            if ($hash !== null) {
                $creator->setAttachmentHash($hash);
            }
        }

        if (!$creator->validate($errors)) {
            return $this->error($errors);
        }

        /** @var \Truonglv\Groups\Entity\Post $post */
        $post = $creator->save();
        $creator->sendNotifications();

        return $this->apiSuccess([
            'post' => $post->toApiResult()
        ]);
    }

    /**
     * @param int $groupId
     * @param string $with
     * @return \Truonglv\Groups\Entity\Group
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableGroup($groupId, $with = 'api')
    {
        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $this->assertViewableApiRecord('Truonglv\Groups:Group', $groupId, $with);

        return $group;
    }
}
