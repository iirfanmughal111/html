<?php

namespace XenAddons\Showcase\Service\Review;

use XenAddons\Showcase\Entity\ItemRating;

class Reassign extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\ItemRating
	 */
	protected $rating;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemRating $rating)
	{
		parent::__construct($app);
		$this->rating = $rating;
	}

	public function getItemRating()
	{
		return $this->rating;
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}

	public function reassignTo(\XF\Entity\User $newUser)
	{
		$rating = $this->rating;
		$oldUser = $rating->User;
		$reassigned = ($rating->user_id != $newUser->user_id);

		$rating->user_id = $newUser->user_id;
		$rating->username = $newUser->username;
		$rating->save();

		if ($reassigned && $rating->isVisible() && $this->alert) 
		{
			if ($oldUser && \XF::visitor()->user_id != $oldUser->user_id)
			{	
				/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
				$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
				$ratingRepo->sendModeratorActionAlert(
					$this->rating, 'reassign_from', $this->alertReason, ['to' => $newUser->username], $oldUser
				);
			}
			
			if (\XF::visitor()->user_id != $newUser->user_id)
			{
				/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
				$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
				$ratingRepo->sendModeratorActionAlert(
					$this->rating, 'reassign_to', $this->alertReason, [], $newUser
				);
			}
		}

		return $reassigned;
	}
}