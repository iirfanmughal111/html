<?php

namespace Z61\Classifieds\XF\Admin\Controller;

use Z61\Classifieds\Entity\UserFeedback;

class User extends XFCP_User
{
    protected function userSaveProcess(\XF\Entity\User $user)
    {
        $form = parent::userSaveProcess($user);
            $form->setup(function() use ($user)
            {
                $feedbackData = $this->filter([
                    'classifieds_feedback_total' => 'int',
                    'classifieds_feedback_positive' => 'uint',
                    'classifieds_feedback_negative' => 'uint'
                ]);
                /** @var UserFeedback $feedbackInfo */
                $feedbackInfo = $user->getRelationOrDefault('ClassifiedsFeedbackInfo', true);
                $feedbackInfo->bulkSet([
                    'total' => $feedbackData['classifieds_feedback_total'],
                    'positive' => $feedbackData['classifieds_feedback_positive'],
                    'negative' => $feedbackData['classifieds_feedback_negative'],
                ]);
            });

        return $form;
    }
}