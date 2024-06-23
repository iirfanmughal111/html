<?php

namespace XenAddons\Showcase\Service\Review;

use XenAddons\Showcase\Entity\ItemRating;

class Deleter extends \XF\Service\AbstractService
{
	/**
	 * @var ItemRating
	 */
	protected $rating;

	protected $user;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemRating $rating)
	{
		parent::__construct($app);
		$this->setComment($rating);
	}

	public function setComment(ItemRating $rating)
	{
		$this->rating = $rating;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function setUser(\XF\Entity\User $user = null)
	{
		$this->user = $user;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}

	public function delete($type, $reason = '')
	{
		$user = $this->user ?: \XF::visitor();

		$result = null;

		$wasVisible = $this->rating->rating_state == 'visible';

		if ($type == 'soft')
		{
			$result = $this->rating->softDelete($reason, $user);
		}
		else
		{
			$result = $this->rating->delete();
		}

		if ($result && $wasVisible && $this->alert && $this->rating->user_id != $user->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
			$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
			$ratingRepo->sendModeratorActionAlert($this->rating, 'delete', $this->alertReason);
		}

		return $result;
	}
}