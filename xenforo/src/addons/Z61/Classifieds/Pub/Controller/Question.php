<?php

namespace Z61\Classifieds\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Question extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();

        if (!$visitor->canViewClassifieds($error))
        {
            throw $this->exception($this->noPermission($error));
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);

        return $this->redirectToQuestion($question);
    }

    public function actionPreview(ParameterBag $params)
    {
        $this->assertPostOnly();

        $question = $this->assertViewableQuestion($params->question_id);

        $editor = $this->setupQuestionEdit($question);
        if (!$editor->validate($errors))
        {
            return $this->error($errors);
        }

        $question = $editor->getQuestion();

        $attachments = [];
        $tempHash = $this->filter('attachment_hash', 'str');

        if ($question->Listing->Category && $question->Listing->Category->canUploadAndManageQuestionImages())
        {
            /** @var \XF\Repository\Attachment $attachmentRepo */
            $attachmentRepo = $this->repository('XF:Attachment');
            $attachmentData = $attachmentRepo->getEditorData('classifieds_question', $question, $tempHash);
            $attachments = $attachmentData['attachments'];
        }

        return $this->plugin('XF:BbCodePreview')->actionPreview(
            $question->message, 'classifieds_question', $question->User, $attachments, $question->Listing->canViewQuestionImages()
        );
    }

    /**
     * @param \Z61\Classifieds\Entity\Question $question
     *
     * @return \Z61\Classifieds\Service\Question\Edit
     */
    protected function setupQuestionEdit(\Z61\Classifieds\Entity\Question $question)
    {
        /** @var \Z61\Classifieds\Service\Question\Edit $editor */
        $editor = $this->service('Z61\Classifieds:Question\Edit', $question);

        if ($question->canEditSilently())
        {
            $silentEdit = $this->filter('silent', 'bool');
            if ($silentEdit)
            {
                $editor->logEdit(false);
                if ($this->filter('clear_edit', 'bool'))
                {
                    $question->last_edit_date = 0;
                }
            }
        }

        $editor->setMessage($this->plugin('XF:Editor')->fromInput('message'));

        if ($this->filter('author_alert', 'bool') && $question->canSendModeratorActionAlert())
        {
            $editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
        }

        if ($question->Listing->Category->canUploadAndManageQuestionImages())
        {
            $editor->setAttachmentHash($this->filter('attachment_hash', 'str'));
        }

        return $editor;
    }

    public function actionEdit(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);
        if (!$question->canEdit($error))
        {
            return $this->noPermission($error);
        }

        $category = $question->Listing->Category;

        if ($this->isPost())
        {
            $editor = $this->setupQuestionEdit($question);

            if (!$editor->validate($errors))
            {
                return $this->error($errors);
            }

            $question = $editor->save();

            if ($this->filter('_xfWithData', 'bool') && $this->filter('_xfInlineEdit', 'bool'))
            {
                $viewParams = [
                    'question' => $question,
                    'item' => $question->Listing,
                    'category' => $question->Listing->Category,
                ];
                $question = $this->view('Z61\Classifieds:Question\EditNewQuestion', 'z61_classifieds_question_edit_new_question', $viewParams);
                $question->setJsonParam('message', \XF::phrase('your_changes_have_been_saved'));
                return $question;
            }
            else
            {
                return $this->redirectToQuestion($question);
            }
        }
        else
        {
            if ($category && $category->canUploadAndManageQuestionImages())
            {
                /** @var \XF\Repository\Attachment $attachmentRepo */
                $attachmentRepo = $this->repository('XF:Attachment');
                $attachmentData = $attachmentRepo->getEditorData('classifieds_question', $question);
            }
            else
            {
                $attachmentData = null;
            }

            $viewParams = [
                'question' => $question,
                'item' => $question->Listing,
                'category' => $question->Listing->Category,
                'attachmentData' => $attachmentData,
                'quickEdit' => $this->responseType() == 'json'
            ];
            return $this->view('Z61\Classifieds:Question\Edit', 'z61_classifieds_question_edit', $viewParams);
        }
    }
    
    public function actionReassign(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);
        if (!$question->canReassign($error))
        {
            return $this->noPermission($error);
        }

        if ($this->isPost())
        {
            $user = $this->em()->findOne('XF:User', ['username' => $this->filter('username', 'str')]);
            if (!$user)
            {
                return $this->error(\XF::phrase('requested_user_not_found'));
            }

            $canTargetView = \XF::asVisitor($user, function() use ($question)
            {
                return $question->canView();
            });
            if (!$canTargetView)
            {
                return $this->error(\XF::phrase('z61_classifieds_new_owner_must_be_able_to_view_this_question'));
            }

            /** @var \Z61\Classifieds\Service\Question\Reassign $reassigner */
            $reassigner = $this->service('Z61\Classifieds:Question\Reassign', $question);

            if ($this->filter('alert', 'bool'))
            {
                $reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
            }

            $reassigner->reassignTo($user);

            return $this->redirect($this->buildLink('classifieds/question', $question));
        }
        else
        {
            $viewParams = [
                'question' => $question,
                'item' => $question->Listing,
                'category' => $question->Listing->Category
            ];
            return $this->view('Z61\Classifieds:Question\Reassign', 'z61_classifieds_question_reassign', $viewParams);
        }
    }

    public function actionDelete(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);
        if (!$question->canDelete('soft', $error))
        {
            return $this->noPermission($error);
        }

        if ($this->isPost())
        {
            $type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
            $reason = $this->filter('reason', 'str');

            if (!$question->canDelete($type, $error))
            {
                return $this->noPermission($error);
            }

            /** @var \Z61\Classifieds\Service\Question\Deleter $deleter */
            $deleter = $this->service('Z61\Classifieds:Question\Deleter', $question);

            if ($this->filter('author_alert', 'bool') && $question->canSendModeratorActionAlert())
            {
                $deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
            }

            $deleter->delete($type, $reason);

            return $this->redirect(
                $this->getDynamicRedirect($this->buildLink('classifieds', $question->Listing), false)
            );
        }
        else
        {
            $viewParams = [
                'question' => $question,
                'item' => $question->Listing
            ];
            return $this->view('Z61\Classifieds:Question\Delete', 'z61_classifieds_question_delete', $viewParams);
        }
    }

    public function actionUndelete(ParameterBag $params)
    {
        $this->assertValidCsrfToken($this->filter('t', 'str'));

        $question = $this->assertViewableQuestion($params->question_id);
        if (!$question->canUndelete($error))
        {
            return $this->noPermission($error);
        }

        if ($question->question_state == 'deleted')
        {
            $question->question_state = 'visible';
            $question->save();
        }

        return $this->redirect($this->buildLink('classifieds/question', $question));
    }

    public function actionIp(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);

        $item = $question->Listing;
        $breadcrumbs = $item->Category->getBreadcrumbs();

        /** @var \XF\ControllerPlugin\Ip $ipPlugin */
        $ipPlugin = $this->plugin('XF:Ip');
        return $ipPlugin->actionIp($question, $breadcrumbs);
    }

    public function actionReport(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);
        if (!$question->canReport($error))
        {
            return $this->noPermission($error);
        }

        /** @var \XF\ControllerPlugin\Report $reportPlugin */
        $reportPlugin = $this->plugin('XF:Report');
        return $reportPlugin->actionReport(
            'classifieds_question', $question,
            $this->buildLink('classifieds/question/report', $question),
            $this->buildLink('classifieds/question', $question)
        );
    }

    public function actionHistory(ParameterBag $params)
    {
        return $this->rerouteController('XF:EditHistory', 'index', [
            'content_type' => 'classifieds_question',
            'content_id' => $params->question_id
        ]);
    }

    public function actionReact(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);

        /** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
        $reactionPlugin = $this->plugin('XF:Reaction');
        return $reactionPlugin->actionReactSimple($question, 'classifieds/question');
    }

    public function actionReactions(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);

        $item = $question->Listing;
        $breadcrumbs = $item->Category->getBreadcrumbs();
        $title = \XF::phrase('z61_classifieds_members_who_have_reacted_to_question_by_x', ['user' => $question->username]);

        /** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
        $reactionPlugin = $this->plugin('XF:Reaction');
        return $reactionPlugin->actionReactions(
            $question,
            'classifieds/question/reactions',
            $title, $breadcrumbs
        );
    }

    public function actionWarn(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);

        if (!$question->canWarn($error))
        {
            return $this->noPermission($error);
        }

        $item = $question->Listing;
        $breadcrumbs = $item->Category->getBreadcrumbs();

        /** @var \XF\ControllerPlugin\Warn $warnPlugin */
        $warnPlugin = $this->plugin('XF:Warn');
        return $warnPlugin->actionWarn(
            'classifieds_question', $question,
            $this->buildLink('classifieds/question/warn', $question),
            $breadcrumbs
        );
    }

    /**
     * @param \Z61\Classifieds\Entity\Question $question
     *
     * @return \Z61\Classifieds\Service\QuestionReply\Creator
     */
    protected function setupQuestionReply(\Z61\Classifieds\Entity\Question $question)
    {
        $message = $this->plugin('XF:Editor')->fromInput('message');

        /** @var \Z61\Classifieds\Service\QuestionReply\Creator $creator */
        $creator = $this->service('Z61\Classifieds:QuestionReply\Creator', $question);
        $creator->setContent($message);

        return $creator;
    }

    protected function finalizeQuestionReply(\Z61\Classifieds\Service\QuestionReply\Creator $creator)
    {
        $creator->sendNotifications();
    }

    public function actionAddReply(ParameterBag $params)
    {
        $this->assertPostOnly();

        $question = $this->assertViewableQuestion($params->question_id);
        if (!$question->canReply($error))
        {
            return $this->noPermission($error);
        }

        $creator = $this->setupQuestionReply($question);
        $creator->checkForSpam();

        if (!$creator->validate($errors))
        {
            return $this->error($errors);
        }
        $this->assertNotFlooding('post');
        $reply = $creator->save();

        $this->finalizeQuestionReply($creator);

        if ($this->filter('_xfWithData', 'bool') && $this->request->exists('last_date') && $question->canView())
        {
            $questionRepo = $this->getQuestionRepo();

            $lastDate = $this->filter('last_date', 'uint');

            /** @var \XF\Mvc\Entity\Finder $questionReplyList */
            $questionReplyList = $questionRepo->findNewestRepliesForQuestion($question, $lastDate);
            $questionReplies = $questionReplyList->fetch();

            // put the posts into oldest-first order
            $questionReplies = $questionReplies->reverse(true);

            $viewParams = [
                'question' => $question,
                'questionReplies' => $questionReplies
            ];
            $view = $this->view('Z61\Classifieds:Question\NewQuestionReplies', 'z61_classifieds_question_new_replies', $viewParams);
            $view->setJsonParam('lastDate', $questionReplies->last()->reply_date);
            return $view;
        }
        else
        {
            return $this->redirect($this->buildLink('classifieds/question-reply', $reply));
        }
    }

    public function actionLoadPrevious(ParameterBag $params)
    {
        $question = $this->assertViewableQuestion($params->question_id);

        $repo = $this->getQuestionRepo();

        $replies = $repo->findQuestionReplies($question)
            ->forFullView()
            ->where('reply_date', '<', $this->filter('before', 'uint'))
            ->order('reply_date', 'DESC')
            ->limit(20)
            ->fetch()
            ->reverse();

        if ($replies->count())
        {
            $firstReplyDate = $replies->first()->reply_date;

            $moreRepliesFinder = $repo->findQuestionReplies($question)
                ->where('reply_date', '<', $firstReplyDate);

            $loadMore = ($moreRepliesFinder->total() > 0);
        }
        else
        {
            $firstReplyDate = 0;
            $loadMore = false;
        }

        $viewParams = [
            'question' => $question,
            'replies' => $replies,
            'firstReplyDate' => $firstReplyDate,
            'loadMore' => $loadMore
        ];
        return $this->view('Z61\Classifieds:Question\LoadPrevious', 'z61_classifieds_question_replies', $viewParams);
    }

    protected function redirectToQuestion(\Z61\Classifieds\Entity\Question $question)
    {
        $item = $question->Listing;

        $newerFinder = $this->getQuestionRepo()->findQuestionsInListing($item);
        $newerFinder->where('question_date', '>', $question->question_date);
        $totalNewer = $newerFinder->total();

        $perPage = $this->options()->xaRmsQuestionsPerPage;
        $page = ceil(($totalNewer + 1) / $perPage);

        if ($page > 1)
        {
            $params = ['page' => $page];
        }
        else
        {
            $params = [];
        }

        return $this->redirect(
            $this->buildLink('classifieds/questions', $item, $params)
            . '#item-question-' . $question->question_id
        );
    }

    /**
     * @param $questionId
     * @param array $extraWith
     *
     * @return \Z61\Classifieds\Entity\Question
     *
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableQuestion($questionId, array $extraWith = [])
    {
        $visitor = \XF::visitor();

        $extraWith[] = 'Listing';
        $extraWith[] = 'Listing.User';
        $extraWith[] = 'Listing.Category';
        $extraWith[] = 'Listing.Category.Permissions|' . $visitor->permission_combination_id;

        /** @var \Z61\Classifieds\Entity\Question $question */
        $question = $this->em()->find('Z61\Classifieds:Question', $questionId, $extraWith);
        if (!$question)
        {
            throw $this->exception($this->notFound(\XF::phrase('z61_classifieds_requested_question_not_found')));
        }

        if (!$question->canView($error))
        {
            throw $this->exception($this->noPermission($error));
        }

        return $question;
    }

    /**
     * @return \Z61\Classifieds\Repository\Question
     */
    protected function getQuestionRepo()
    {
        return $this->repository('Z61\Classifieds:Question');
    }

    public static function getActivityDetails(array $activities)
    {
        \XF::phrase('z61_classifieds_viewing_listings');
    }
}