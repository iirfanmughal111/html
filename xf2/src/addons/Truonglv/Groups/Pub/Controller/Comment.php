<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Pub\Controller;

use XF;
use function count;
use LogicException;
use XF\Mvc\Reply\View;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\ControllerPlugin\Delete;
use XF\ControllerPlugin\Report;
use XF\Mvc\Reply\AbstractReply;
use XF\Service\AbstractService;
use XF\Pub\Controller\AbstractController;

class Comment extends AbstractController
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

        /** @var \Truonglv\Groups\Entity\Comment|null $comment */
        $comment = $reply->getParam('comment');
        if ($comment !== null && $comment->Group !== null) {
            $reply->setContainerKey('tlg-group-' . $comment->Group->group_id);
            $reply->setContentKey('tlg-group-comment-' . $comment->comment_id);
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        $comment = App::assertionPlugin($this)->assertCommentViewable($params);

        return $this->redirectPermanently($this->getCommentLink($comment));
    }

    public function actionLoader(ParameterBag $params)
    {
        $this->assertPostOnly();
        $comment = App::assertionPlugin($this)->assertCommentViewable($params);

        $loadedCommentIds = $this->filter('loaded', 'array-uint');

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

        $comments = $finder->fetch(10);
        if ($comment->parent_id === 0) {
            App::commentRepo()->addRecentRepliesIntoComments($comments);
        }

        App::attachmentRepo()->addAttachmentsToContent($comments, App::CONTENT_TYPE_COMMENT);

        $loadedCommentIds = array_merge($loadedCommentIds, $comments->keys());

        $viewParams = [
            'comment' => $comment,
            'comments' => $comments,
        ];

        $replier = $this->view('Truonglv\Groups:Comment\Loader', 'tlg_comment_list', $viewParams);
        $replier->setJsonParam('loaded', $loadedCommentIds);
        $replier->setJsonParam('hasMore', $finder->total() > 10);

        return $replier;
    }

    public function actionReact(ParameterBag $params)
    {
        $comment = App::assertionPlugin($this)->assertCommentViewable($params);

        /** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
        $reactionPlugin = $this->plugin('XF:Reaction');

        return $reactionPlugin->actionReactSimple($comment, 'group-comments');
    }

    public function actionReactions(ParameterBag $params)
    {
        $comment = App::assertionPlugin($this)->assertCommentViewable($params);
        /** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
        $reactionPlugin = $this->plugin('XF:Reaction');

        return $reactionPlugin->actionReactions(
            $comment,
            'group-comments/reactions',
            null,
            $comment->Group !== null ? $comment->Group->getBreadcrumbs() : []
        );
    }

    public function actionReply(ParameterBag $params)
    {
        $this->assertPostOnly();

        $comment = App::assertionPlugin($this)->assertCommentViewable($params);
        $error = null;
        if (!$comment->canReply($error)) {
            return $this->noPermission();
        }

        /** @var mixed $replier */
        $replier = $this->service('Truonglv\Groups:Comment\Replier', $comment);
        $newComment = $this->saveCommentProcess($replier, $comment);

        $replier->sendNotifications();

        $viewParams = [
            'comment' => $newComment
        ];

        return $this->view(
            'Truonglv\Groups:Comment\Reply',
            'tlg_comment_item',
            $viewParams
        );
    }

    public function actionEdit(ParameterBag $params)
    {
        $comment = App::assertionPlugin($this)->assertCommentViewable($params);
        if (!$comment->canEdit($error)) {
            return $this->noPermission($error);
        }

        /** @var mixed $content */
        $content = $comment->getContent();
        if ($comment->comment_id === $content->first_comment_id) {
            if ($content instanceof \Truonglv\Groups\Entity\Post) {
                return $this->rerouteController('Truonglv\Groups:Post', 'edit', [
                    'post_id' => $content->post_id
                ]);
            } elseif ($content instanceof \Truonglv\Groups\Entity\Event) {
                return $this->rerouteController('Truonglv\Groups:Event', 'edit', [
                    'event_id' => $content->event_id
                ]);
            }

            throw new LogicException('Must be implemented! $contentType=' . $comment->content_type);
        }

        if ($this->isPost()) {
            /** @var mixed $editor */
            $editor = $this->service('Truonglv\Groups:Comment\Editor', $comment);
            /** @var \Truonglv\Groups\Entity\Comment $comment */
            $comment = $this->saveCommentProcess($editor, $comment);

            $withData = (bool) $this->filter('_xfWithData', 'bool');
            $inlineEdit = (bool) $this->filter('_xfInlineEdit', 'bool');
            if ($withData && $inlineEdit) {
                /** @var \XF\Repository\Attachment $attachmentRepo */
                $attachmentRepo = $this->repository('XF:Attachment');
                $comments = $this->em()->getBasicCollection([
                    $comment->comment_id => $comment
                ]);

                App::commentRepo()->addRecentRepliesIntoComments($comments);
                $attachmentRepo->addAttachmentsToContent(
                    $comments,
                    App::CONTENT_TYPE_COMMENT
                );

                $viewParams = [
                    'comment' => $comment,
                    'content' => $comment->getContent()
                ];

                $reply = $this->view(
                    'Truonglv\Groups:Comment\Edit',
                    'tlg_comment_item',
                    $viewParams
                );

                $reply->setJsonParams([
                    'message' => XF::phrase('your_changes_have_been_saved')
                ]);

                return $reply;
            }

            return $this->redirect($this->getCommentLink($comment));
        }

        $attachmentData = $comment->getAttachmentEditorData();

        $viewParams = [
            'comment' => $comment,
            'attachmentData' => $attachmentData,
            'quickEdit' => $this->filter('_xfWithData', 'bool'),
            'group' => $comment->Group
        ];

        return $this->view(
            'Truonglv\Groups:Comment\Edit',
            'tlg_comment_edit',
            $viewParams
        );
    }

    public function actionDelete(ParameterBag $params)
    {
        $comment = App::assertionPlugin($this)->assertCommentViewable($params);
        if (!$comment->canDelete($error)) {
            return $this->noPermission($error);
        }

        $baseRoute = $comment->getContentBaseLink();
        if ($comment->isFirstComment()) {
            return $this->redirect($this->buildLink($baseRoute . '/delete', $comment->Content), '');
        }

        /** @var Delete $deletePlugin */
        $deletePlugin = $this->plugin('XF:Delete');

        return $deletePlugin->actionDelete(
            $comment,
            $this->buildLink('group-comments/delete', $comment),
            $this->buildLink('group-comments', $comment),
            $this->buildLink($baseRoute, $comment->Content),
            $this->app()->stringFormatter()->snippetString($comment->message, 100)
        );
    }

    public function actionReport(ParameterBag $params)
    {
        $comment = App::assertionPlugin($this)->assertCommentViewable($params);
        $error = null;
        if (!$comment->canReport($error)) {
            return $this->noPermission($error);
        }

        /** @var Report $reportPlugin */
        $reportPlugin = $this->plugin('XF:Report');

        return $reportPlugin->actionReport(
            App::CONTENT_TYPE_COMMENT,
            $comment,
            $this->buildLink('group-comments/report', $comment),
            $this->buildLink('group-comments', $comment)
        );
    }

    public function actionIp(ParameterBag $params)
    {
        $comment = App::assertionPlugin($this)->assertCommentViewable($params);
        if ($comment->Group === null) {
            return $this->noPermission();
        }
        $breadcrumbs = $comment->Group->getBreadcrumbs();

        /** @var \XF\ControllerPlugin\Ip $ipPlugin */
        $ipPlugin = $this->plugin('XF:Ip');

        return $ipPlugin->actionIp($comment, $breadcrumbs);
    }

    /**
     * @param AbstractService $service
     * @param \Truonglv\Groups\Entity\Comment $comment
     * @return \XF\Mvc\Entity\Entity
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function saveCommentProcess($service, \Truonglv\Groups\Entity\Comment $comment)
    {
        /** @var \Truonglv\Groups\ControllerPlugin\Comment $commentPlugin */
        $commentPlugin = $this->plugin('Truonglv\Groups:Comment');

        return $commentPlugin->saveCommentProcess(
            $service,
            $comment->canUploadAndManageAttachments()
        );
    }

    /**
     * @param \Truonglv\Groups\Entity\Comment $comment
     * @return string
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function getCommentLink(\Truonglv\Groups\Entity\Comment $comment)
    {
        $content = $comment->Content;
        if ($content === null) {
            throw $this->errorException($this->noPermission());
        }

        if ($comment->parent_id > 0 && $comment->ParentComment !== null) {
            return $this->getCommentLink($comment->ParentComment);
        }

        $commentsPerPage = App::getOption('commentsPerPage');
        $totalBefore = App::commentFinder()
            ->inContent($comment->content_type, $comment->content_id)
            ->where('parent_id', $comment->parent_id)
            ->where('comment_date', '<=', $comment->comment_date)
            ->total();

        $page = ceil($totalBefore / $commentsPerPage);
        $link = $this->buildLink(
            $comment->getContentBaseLink(),
            $content,
            ['page' => ($page > 1) ? $page : null]
        );

        return $link . '#js-comment-' . $comment->comment_id;
    }

    /**
     * @param array $activities
     * @return bool|\XF\Phrase
     */
    public static function getActivityDetails(array $activities)
    {
        return XF::phrase('tlg_viewing_group');
    }
}
