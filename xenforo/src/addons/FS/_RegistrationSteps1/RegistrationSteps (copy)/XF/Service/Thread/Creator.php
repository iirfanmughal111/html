<?php

namespace FS\RegistrationSteps\XF\Service\Thread;

class Creator extends XFCP_Creator
{
    protected $review_for;

    public function setReview_for($review_for)
    {
        $this->thread->review_for = $review_for;
        
    }

}