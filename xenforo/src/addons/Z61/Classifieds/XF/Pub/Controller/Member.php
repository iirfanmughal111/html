<?php

namespace Z61\Classifieds\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use Z61\Classifieds\Entity\Feedback;
use Z61\Classifieds\XF\Entity\User;

class Member extends XFCP_Member
{
    //region Feedback
    public function actionFeedbackView(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);
        if (!\XF::visitor()->canViewClassifiedsFeedback())
        {
            return $this->noPermission();
        }
        $feedbackId = $this->filter('feedback_id', 'int');

        if ($feedback = $this->assertFeedbackExists($feedbackId))
        {
            if (!$feedback->ToUser->user_id == $user->user_id)
            {
                return $this->noPermission();
            }

            return $this->view('Z61\Classifieds:Feedback\View', 'z61_classifieds_feedback_view', [
                'feedback' => $feedback,
            ]);
            return $this->redirect($this->getDynamicRedirect($this->buildLink('classifieds')));
        }
        return '';
    }
    public function actionFeedback(ParameterBag $params)
    {
        /** @var User $user */
        $user = $this->assertViewableUser($params->user_id);
        if (!\XF::visitor()->canViewClassifiedsFeedback())
        {
            return $this->noPermission();
        }
        $feedbackId = $this->filter('feedback_id', 'int');

        if ($feedbackId && $feedback = $this->assertFeedbackExists($feedbackId))
        {
            return $this->rerouteController(__CLASS__, 'feedbackView');
        }
        $page = $this->filterPage();
        $perPage = $this->options()->z61ClassifiedsFeedbackPerPage;
        $feedbackFinder = $user->getRelationFinder('ClassifiedsFeedback');

        $this->assertValidPage($page, $perPage, $feedbackFinder->total(), 'members/feedback', $user);

        $feedbackFinder->limitByPage($page, $perPage);

        $feedback = $feedbackFinder->fetch();


        return $this->view('Z61\Classifieds:Member/Feedback', 'z61_classifieds_member_feedback', [
            'user' => $user,
            'feedback' => $feedback
        ]);
    }

    public function actionFeedbackEdit(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);
        $feedback = $this->assertFeedbackExists($this->filter('feedback_id', 'int'));

        if (!$feedback->canEdit())
        {
            return $this->noPermission();
        }

        if ($feedback->to_user_id != $user->user_id)
        {
            return $this->notFound();
        }

        $saveLink = $this->buildLink('members/feedback/save', $user, ['feedback_id' => $feedback->feedback_id]);

        $viewParams = [
            'feedback' => $feedback,
            'user' => $user,
            'saveLink' => $saveLink
        ];

        return $this->view('Z61\Classifieds:Feedback\Edit', 'z61_classifieds_feedback_edit', $viewParams);
    }

    public function actionFeedbackSave(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);
        $feedback = $this->assertFeedbackExists($this->filter('feedback_id', 'int'));

        if (!$feedback->canEdit())
        {
            return $this->noPermission();
        }

        $message = $this->plugin('XF:Editor')->fromInput('message');

        $feedback->bulkSet([
            'rating' => $this->filter('rating', 'str'),
            'feedback' => $message
        ]);

        $feedback->saveIfChanged();

        return $this->redirect($this->buildLink('members', $user) . '#feedback');
    }

    public function actionFeedbackDelete(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);

        $feedback = $this->assertFeedbackExists($this->filter('feedback_id', 'int'));

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $feedback,
            $this->buildLink('members/feedback/delete', $user, ['feedback_id' => $feedback->feedback_id]),
            $this->buildLink('members/feedback/edit', $user, ['feedback_id' => $feedback->feedback_id]),
            $this->buildLink('notices'),
            $this->app->stringFormatter()->snippetString($feedback->feedback, 150), 'z61_classifieds_feedback_delete_confirm'
        );
    }
    //endregion

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return Feedback
     */
    protected function assertFeedbackExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Z61\Classifieds:Feedback', $id, $with, $phraseKey);
    }

}