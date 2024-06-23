<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Api\Controller;

use XF;
use function max;
use function count;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use function array_merge;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\ArrayCollection;
use XF\Api\Controller\AbstractController;
use Truonglv\Groups\Service\Comment\Editor;
use Truonglv\Groups\Service\Comment\Replier;

class Comment extends AbstractController
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertApiScopeByRequestMethod('tl_groups');
    }

    public function actionGet(ParameterBag $params)
    {
        $comment = $this->assertViewableComment($params->comment_id);
        if ($comment->Content === null) {
            return $this->notFound();
        }

        return $this->apiResult([
            'comment' => $comment->toApiResult(),
            'content' => $comment->Content->toApiResult()
        ]);
    }

    public function actionPost(ParameterBag $params)
    {
        $comment = $this->assertViewableComment($params->comment_id);
        $error = null;
        if (XF::isApiCheckingPermissions() && !$comment->canEdit($error)) {
            return $this->noPermission($error);
        }

        $input = $this->filter([
            'message' => '?str',
            'attachment_key' => 'str'
        ]);

        /** @var Editor $editor */
        $editor = $this->service('Truonglv\Groups:Comment\Editor', $comment);

        if ($input['message'] !== null) {
            $editor->setMessage($input['message']);
        }

        if ($comment->Group !== null
            && $comment->Group->canUploadAndManageAttachments()
            && strlen($input['attachment_key']) > 0
        ) {
            $hash = $this->getAttachmentTempHashFromKey(
                $input['attachment_key'],
                App::CONTENT_TYPE_COMMENT,
                ['comment_id' => $comment->comment_id]
            );
            if ($hash !== null) {
                $editor->setAttachmentHash($hash);
            }
        }

        if (!$editor->validate($errors)) {
            return $this->error($errors);
        }

        /** @var \Truonglv\Groups\Entity\Comment $comment */
        $comment = $editor->save();

        return $this->apiSuccess([
            'comment' => $comment->toApiResult()
        ]);
    }

    public function actionGetReplies(ParameterBag $params)
    {
        $comment = $this->assertViewableComment($params->comment_id);

        $loadedCommentIds = $this->filter('loaded', 'array-uint');
        $limit = $this->filter('limit', 'uint');
        if ($limit <= 0) {
            $limit = 10;
        }

        $limit = max(10, $limit);

        $finder = App::commentFinder();

        $finder->where('content_type', $comment->content_type);
        $finder->where('content_id', $comment->content_id);
        $finder->where('parent_id', $comment->parent_id);

        if (count($loadedCommentIds) > 0) {
            $finder->where('comment_id', '<>', $loadedCommentIds);
        }

        if ($this->filter('is_after', 'bool') === true) {
            $finder->where('comment_date', '>=', $comment->comment_date);
        } else {
            $finder->where('comment_date', '<=', $comment->comment_date);
        }

        $comments = $finder->fetch($limit);
        if ($comment->parent_id === 0) {
            App::commentRepo()->addRecentRepliesIntoComments($comments);
        }

        App::attachmentRepo()->addAttachmentsToContent($comments, App::CONTENT_TYPE_COMMENT);
        $loadedCommentIds = array_merge($loadedCommentIds, $comments->keys());

        return $this->apiResult([
            'comments' => $comments->toApiResults(Entity::VERBOSITY_VERBOSE),
            'loaded' => $loadedCommentIds,
            'hasMore' => $finder->total() > $limit
        ]);
    }

    public function actionPostReplies(ParameterBag $params)
    {
        $this->assertRequiredApiInput(['message']);

        $comment = $this->assertViewableComment($params->comment_id);
        $error = null;
        if (XF::isApiCheckingPermissions() && !$comment->canReply($error)) {
            return $this->noPermission($error);
        }

        /** @var Replier $replier */
        $replier = $this->service('Truonglv\Groups:Comment\Replier', $comment);
        $replier->setMessage($this->filter('message', 'str'));

        if (!$replier->validate($errors)) {
            return $this->error($errors);
        }

        /** @var \Truonglv\Groups\Entity\Comment $comment */
        $comment = $replier->save();
        $replier->sendNotifications();

        return $this->apiSuccess([
            'comment' => $comment->toApiResult()
        ]);
    }

    public function actionDelete(ParameterBag $params)
    {
        $comment = $this->assertViewableComment($params->comment_id);
        $error = null;
        if (XF::isApiCheckingPermissions() && !$comment->canDelete($error)) {
            return $this->noPermission($error);
        }

        /** @var mixed|null $contentEntity */
        $contentEntity = $comment->getContent();
        if ($contentEntity !== null && $contentEntity->first_comment_id === $comment->comment_id) {
            $contentEntity->delete();
        } else {
            $comment->delete();
        }

        return $this->apiSuccess();
    }

    public function actionPostReact(ParameterBag $params)
    {
        $comment = $this->assertViewableComment($params->comment_id);

        /** @var \XF\Api\ControllerPlugin\Reaction $reactPlugin */
        $reactPlugin = $this->plugin('XF:Api:Reaction');

        return $reactPlugin->actionReact($comment);
    }

    public function actionGetReactions(ParameterBag $params)
    {
        $comment = $this->assertViewableComment($params->comment_id);

        // TODO: get reactions data
    }

    public function actionPostReport(ParameterBag $params)
    {
        $this->assertRequiredApiInput(['message']);

        $comment = $this->assertViewableComment($params->comment_id);
        $error = null;
        if (XF::isApiCheckingPermissions() && !$comment->canReport($error)) {
            return $this->noPermission($error);
        }

        /** @var \XF\Service\Report\Creator $creator */
        $creator = $this->service('XF:Report\Creator', App::CONTENT_TYPE_COMMENT, $comment);
        $creator->setMessage($this->filter('message', 'str'));

        if (!$creator->validate($errors)) {
            return $this->error($errors);
        }

        $creator->save();
        $creator->sendNotifications();

        return $this->apiSuccess([
            'message' => XF::phrase('thank_you_for_reporting_this_content')
        ]);
    }

    public function actionGetAttachments(ParameterBag $params)
    {
        $comment = $this->assertViewableComment($params->comment_id);
        $apiResults = [];

        if ($comment->attach_count > 0) {
            /** @var ArrayCollection $attachments */
            $attachments = $comment->Attachments;

            $apiResults['attachments'] = $attachments->toApiResults(Entity::VERBOSITY_VERBOSE);
        }

        return$this->apiResult($apiResults);
    }

    /**
     * @param mixed $commentId
     * @param string $with
     * @return \Truonglv\Groups\Entity\Comment
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableComment($commentId, $with = 'api')
    {
        /** @var \Truonglv\Groups\Entity\Comment $comment */
        $comment = $this->assertViewableApiRecord('Truonglv\Groups:Comment', $commentId, $with);

        return $comment;
    }
}
