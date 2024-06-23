<?php

namespace XenAddons\Showcase\Service\ItemRatingReply;

use XenAddons\Showcase\Entity\ItemRatingReply;

class Reassign extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\ItemRatingReply
	 */
	protected $reply;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemRatingReply $reply)
	{
		parent::__construct($app);
		$this->reply = $reply;
	}

	public function getReply()
	{
		return $this->reply;
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
		$reply = $this->reply;
		$oldUser = $reply->User;
		$reassigned = ($reply->user_id != $newUser->user_id);

		$reply->user_id = $newUser->user_id;
		$reply->username = $newUser->username;
		$reply->save();

		if ($reassigned && $reply->isVisible() && $this->alert) 
		{
			if ($oldUser && \XF::visitor()->user_id != $oldUser->user_id)
			{	
				/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
				$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
				$ratingRepo->sendReplyModeratorActionAlert(
					$this->reply, 'reassign_from', $this->alertReason, ['to' => $newUser->username], $oldUser
				);
			}
			
			if (\XF::visitor()->user_id != $newUser->user_id)
			{
				/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
				$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
				$ratingRepo->sendReplyModeratorActionAlert(
					$this->reply, 'reassign_to', $this->alertReason, [], $newUser
				);
			}
		}

		return $reassigned;
	}
}