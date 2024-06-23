<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Pub\Controller;

use XF;
use XF\Mvc\Reply\View;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\ControllerPlugin\Delete;
use XF\Mvc\Reply\AbstractReply;
use XF\Pub\Controller\AbstractController;

class Post extends AbstractController
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

        /** @var \Truonglv\Groups\Entity\Post|null $post */
        $post = $reply->getParam('post');
        if ($post !== null && $post->Group !== null) {
            $reply->setContainerKey('tlg-group-' . $post->Group->group_id);
            $reply->setContentKey('tlg-group-post-' . $post->post_id);
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        $post = $this->assertPostViewable($params->post_id);

        $commentFinder = App::commentRepo()->findCommentsForView(
            $post->getCommentContentType(),
            $post->post_id
        );
        $commentFinder->with('full');

        $commentsPerPage = App::getOption('commentsPerPage');
        $page = $this->filterPage();

        $this->assertValidPage($page, $commentsPerPage, $post->comment_count, 'group-posts', $post);

        $comments = $commentFinder
            ->limitByPage($page, $commentsPerPage)
            ->fetch();
        if ($page > 1) {
            /** @var \Truonglv\Groups\Entity\Comment $firstComment */
            $firstComment = $this->em()->find('Truonglv\Groups:Comment', $post->first_comment_id, 'full');

            App::attachmentRepo()->addAttachmentsToContent(
                $this->em()->getBasicCollection([$firstComment->comment_id => $firstComment]),
                App::CONTENT_TYPE_COMMENT
            );
            $post->hydrateRelation('FirstComment', $firstComment);
        } else {
            $comments = $comments->filter(function ($comment) use ($post) {
                return $comment->comment_id !== $post->first_comment_id;
            });
        }

        App::commentRepo()->addRecentRepliesIntoComments($comments);
        $post->setLatestComments($comments);

        $viewParams = [
            'post' => $post,
            'group' => $post->Group,
            'page' => $page,
            'perPage' => $commentsPerPage,
            'total' => $post->comment_count
        ];

        return $this->view(
            'Truonglv\Groups:Post\View',
            'tlg_post_view',
            $viewParams
        );
    }

    public function actionComment(ParameterBag $params)
    {
        $post = $this->assertPostViewable($params->post_id);

        /** @var \Truonglv\Groups\ControllerPlugin\Comment $commentPlugin */
        $commentPlugin = $this->plugin('Truonglv\Groups:Comment');
        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $post->Group;

        return $commentPlugin->actionComment($post, $group);
    }

    public function actionEdit(ParameterBag $params)
    {
        $post = $this->assertPostViewable($params->post_id);

        if ($this->isPost()) {
            /** @var mixed $editor */
            $editor = $this->service('Truonglv\Groups:Comment\Editor', $post->FirstComment);

            /** @var \Truonglv\Groups\ControllerPlugin\Comment $commentPlugin */
            $commentPlugin = $this->plugin('Truonglv\Groups:Comment');
            /** @var \Truonglv\Groups\Entity\Comment $comment */
            $comment = $commentPlugin->saveCommentProcess(
                $editor,
                $post->Group !== null && $post->Group->canUploadAndManageAttachments()
            );

            $post->hydrateRelation('FirstComment', $comment);
            $withData = (bool) $this->filter('_xfWithData', 'bool');
            $inlineEdit = (bool) $this->filter('_xfInlineEdit', 'bool');
            if ($withData && $inlineEdit) {
                /** @var \XF\Repository\Attachment $attachmentRepo */
                $attachmentRepo = $this->repository('XF:Attachment');
                $comments = $this->em()->getBasicCollection([
                    $comment->comment_id => $comment
                ]);

                $attachmentRepo->addAttachmentsToContent(
                    $comments,
                    App::CONTENT_TYPE_COMMENT
                );
                $posts = $this->em()->getBasicCollection([
                    $post->post_id => $post
                ]);
                App::postRepo()->addLatestCommentsIntoPosts($posts);

                $viewParams = [
                    'post' => $post
                ];

                $replier = $this->view('Truonglv\Groups:Post\Edit', 'tlg_post_item', $viewParams);
                $replier->setJsonParams([
                    'message' => XF::phrase('your_changes_have_been_saved')
                ]);

                return $replier;
            }

            return $this->redirect($this->buildLink('group-posts', $post));
        }

        $attachmentData = null;
        if ($post->Group !== null && $post->Group->canUploadAndManageAttachments()) {
            /** @var \XF\Repository\Attachment $attachmentRepo */
            $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentData = $attachmentRepo->getEditorData(
                App::CONTENT_TYPE_COMMENT,
                $post->FirstComment
            );
        }

        $viewParams = [
            'post' => $post,
            'attachmentData' => $attachmentData,
            'quickEdit' => $this->filter('_xfWithData', 'bool'),
            'group' => $post->Group
        ];

        return $this->view('Truonglv\Group:Post\Edit', 'tlg_post_edit', $viewParams);
    }

    public function actionToggleSticky(ParameterBag $params)
    {
        $post = $this->assertPostViewable($params->post_id);
        if (!$post->canStickUnstick($error)) {
            return $this->noPermission($error);
        }

        $totalStickies = $this->finder('Truonglv\Groups:Post')
            ->where('group_id', $post->group_id)
            ->where('sticky', 1)
            ->total();
        $maxStickies = App::getOption('maxPostStickies');
        if (!$post->sticky && $totalStickies >= $maxStickies) {
            return $this->error(XF::phrase('tlg_too_many_sticky_posts_unstick_some_and_try_again'));
        }

        $post->sticky = !$post->sticky;
        $post->save();

        return $this->redirect($this->buildLink('groups', $post->Group));
    }

    public function actionDelete(ParameterBag $params)
    {
        $post = $this->assertPostViewable($params->post_id);
        if (!$post->canDelete($error)) {
            return $this->noPermission($error);
        }

        /** @var Delete $delete */
        $delete = $this->plugin('XF:Delete');

        return $delete->actionDelete(
            $post,
            $this->buildLink('group-posts/delete', $post),
            $this->buildLink('group-posts', $post),
            $this->buildLink('groups', $post->Group),
            XF::phrase('tlg_posted_by_x', [
                'name' => $post->User !== null ? $post->User->username : $post->username
            ])
        );
    }

    /**
     * @param int $postId
     * @param string $with
     * @return \Truonglv\Groups\Entity\Post
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertPostViewable($postId, $with = 'full')
    {
        /** @var \Truonglv\Groups\Entity\Post $post */
        $post = $this->assertRecordExists('Truonglv\Groups:Post', $postId, $with);
        if (!$post->canView($error)) {
            throw $this->errorException($error);
        }

        return $post;
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
