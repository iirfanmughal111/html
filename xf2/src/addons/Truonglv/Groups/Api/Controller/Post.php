<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Api\Controller;

use XF;
use function count;
use function explode;
use function array_map;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Mvc\Entity\Entity;
use Truonglv\Groups\Entity\Comment;
use XF\Api\Controller\AbstractController;
use Truonglv\Groups\Service\Comment\Editor;
use Truonglv\Groups\Service\Comment\Creator;

class Post extends AbstractController
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
        $post = $this->assertViewablePost($params->post_id);
        if ($this->filter('with_comments', 'bool') === true) {
            $commentData = $this->getCommentDataPaginated($post, $this->filterPage());
        } else {
            $commentData = [];
        }

        /** @var Comment $comment */
        $comment = $post->FirstComment;
        App::attachmentRepo()->addAttachmentsToContent(
            $this->em()->getBasicCollection([$comment->comment_id => $comment]),
            App::CONTENT_TYPE_COMMENT
        );

        $apiResult = [
            'post' => $post->toApiResult()
        ] + $commentData;

        return $this->apiResult($apiResult);
    }

    public function actionPost(ParameterBag $params)
    {
        $post = $this->assertViewablePost($params->post_id);
        $error = null;
        if (XF::isApiCheckingPermissions() && !$post->canEdit($error)) {
            return $this->noPermission($error);
        }

        $input = $this->filter([
            'message' => '?str',
            'attachment_key' => 'str'
        ]);

        /** @var Editor $editor */
        $editor = $this->service('Truonglv\Groups:Comment\Editor', $post->FirstComment);

        if ($input['message'] !== null) {
            $editor->setMessage($input['message']);
        }
        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $post->Group;
        if ($group->canUploadAndManageAttachments() && $post->FirstComment !== null) {
            $hash = $this->getAttachmentTempHashFromKey(
                $input['attachment_key'],
                App::CONTENT_TYPE_COMMENT,
                ['comment_id' => $post->FirstComment->comment_id]
            );
            if ($hash !== null) {
                $editor->setAttachmentHash($hash);
            }
        }

        if (!$editor->validate($errors)) {
            return $this->error($errors);
        }

        $editor->save();

        return $this->apiSuccess([
            'post' => $post->toApiResult()
        ]);
    }

    public function actionGetComments(ParameterBag $params)
    {
        $post = $this->assertViewablePost($params->post_id);
        $commentData = $this->getCommentDataPaginated($post, $this->filterPage());

        return $this->apiResult($commentData);
    }

    public function actionPostComments(ParameterBag $params)
    {
        $this->assertRequiredApiInput(['message']);
        $post = $this->assertViewablePost($params->post_id);

        $error = null;
        if (XF::isApiCheckingPermissions() && !$post->canComment($error)) {
            return $this->noPermission($error);
        }

        /** @var Creator $creator */
        $creator = $this->service('Truonglv\Groups:Comment\Creator', $post);
        $creator->setMessage($this->filter('message', 'str'));

        if (!$creator->validate($errors)) {
            return $this->error($errors);
        }

        /** @var Comment $comment */
        $comment = $creator->save();

        $creator->sendNotifications();

        return $this->apiSuccess([
            'comment' => $comment->toApiResult()
        ]);
    }

    public function actionDelete(ParameterBag $params)
    {
        $post = $this->assertViewablePost($params->post_id);
        $error = null;
        if (XF::isApiCheckingPermissions() && !$post->canDelete($error)) {
            return $this->noPermission($error);
        }

        $post->delete();

        return $this->apiSuccess();
    }

    /**
     * @param \Truonglv\Groups\Entity\Post $post
     * @param int $page
     * @return array
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function getCommentDataPaginated(\Truonglv\Groups\Entity\Post $post, $page = 1)
    {
        $limit = $this->filter('limit', '?uint');
        $perPage = $limit === null ? $this->getDefaultCommentsPerPage() : min($limit, $this->getDefaultCommentsPerPage());

        $finder = App::commentRepo()->findCommentsForView(
            $post->getCommentContentType(),
            $post->post_id
        );
        $finder->with('api');
        $this->applyCommentFilters($finder);

        $loadedIds = $this->getFilterLoadedIds();
        $total = null;

        if (count($loadedIds) === 0) {
            $finder->limitByPage($page, $perPage);

            $total = $finder->total();
            $this->assertValidApiPage($page, $perPage, $total);
        } else {
            $finder->limit($perPage);
        }

        $comments = $finder->fetch();
        if (XF::isApiCheckingPermissions()) {
            $comments = $comments->filterViewable();
        }

        App::attachmentRepo()->addAttachmentsToContent($comments, App::CONTENT_TYPE_COMMENT);
        App::commentRepo()->addRecentRepliesIntoComments($comments);

        if ($page === 1) {
            $comments = $comments->filter(function (Comment $comment) use ($post) {
                return $comment->comment_id !== $post->first_comment_id;
            });
        }

        $apiResults = [
            'comments' => $comments->toApiResults(Entity::VERBOSITY_VERBOSE)
        ];
        if ($total === null) {
            $apiResults['hasMore'] = $comments->count() > 0;
        } else {
            $apiResults['pagination'] = $this->getPaginationData($comments, $page, $perPage, $total);
        }

        return $apiResults;
    }

    /**
     * @param \Truonglv\Groups\Finder\Comment $finder
     * @return void
     */
    protected function applyCommentFilters(\Truonglv\Groups\Finder\Comment $finder)
    {
        $loadedIds = $this->getFilterLoadedIds();
        if (count($loadedIds) > 0) {
            $finder->where('comment_id', '<>', $loadedIds);
        }
    }

    /**
     * @return array
     */
    protected function getFilterLoadedIds()
    {
        $loadedIds = $this->filter('loaded_ids', '?str');
        if ($loadedIds === null) {
            return [];
        }

        $loadedIds = explode(',', $loadedIds);
        $loadedIds = array_map('intval', $loadedIds);

        return $loadedIds;
    }

    /**
     * @return int
     */
    protected function getDefaultCommentsPerPage()
    {
        return (int) App::getOption('commentsPerPage');
    }

    /**
     * @param mixed $postId
     * @param string $with
     * @return \Truonglv\Groups\Entity\Post
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewablePost($postId, $with = 'api')
    {
        /** @var \Truonglv\Groups\Entity\Post $post */
        $post = $this->assertViewableApiRecord('Truonglv\Groups:Post', $postId, $with);

        return $post;
    }
}
