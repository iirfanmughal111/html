<?php

namespace Z61\Classifieds\Notifier\Listing;


use XF\Notifier\AbstractNotifier;
use XF\Repository\UserAlert;
use Z61\Classifieds\Entity\Feedback;
use Z61\Classifieds\Entity\Listing;

class FeedbackGiven extends AbstractNotifier
{
    /** @var Feedback $feedback */
    private $feedback;

    public function __construct(\XF\App $app, Feedback $feedback)
    {
        parent::__construct($app);

        $this->feedback = $feedback;
    }

    public function canNotify(\XF\Entity\User $user)
    {
        return ($this->feedback->isVisible() && $user->user_id != $this->feedback->to_user_id) && $this->feedback->canView($user);
    }

    public function sendAlert(\XF\Entity\User $user)
    {
        $feedback = $this->feedback;

        return $this->basicAlert($user, $feedback->from_user_id, $feedback->from_username, 'classifieds_listing', $feedback->listing_id, 'feedback_given', [
            'title' => $feedback->Listing->title,
            'feedback_id' => $feedback->feedback_id
        ]);
    }


}