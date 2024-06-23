<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;
use XenAddons\Showcase\Entity\ItemReplyBan;
use XF\Entity\User;

class ReplyBan extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var Item
	 */
	protected $item;

	/**
	 * @var ItemReplyBan
	 */
	protected $replyBan;

	/**
	 * @var User
	 */
	protected $user;

	protected $alert = false;

	public function __construct(\XF\App $app, Item $item, User $user)
	{
		parent::__construct($app);

		$this->item = $item;
		$this->user = $user;

		$replyBan = $this->em()->findOne('XenAddons\Showcase:ItemReplyBan', [
			'item_id' => $item->item_id,
			'user_id' => $user->user_id
		]);
		if (!$replyBan)
		{
			$replyBan = $this->em()->create('XenAddons\Showcase:ItemReplyBan');
			$replyBan->item_id = $item->item_id;
			$replyBan->user_id = $user->user_id;
		}

		$replyBan->ban_user_id = \XF::visitor()->user_id;

		$this->replyBan = $replyBan;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setExpiryDate($unit, $value = null)
	{
		if (is_int($unit))
		{
			$value = $unit;
			$expiryDate = $value;
		}
		else
		{
			if (!$value)
			{
				$value = 1;
			}
			$expiryDate = min(
				pow(2,32) - 1, strtotime("+$value $unit")
			);
		}
		$this->replyBan->expiry_date = $expiryDate;
	}

	public function setSendAlert($alert)
	{
		$this->alert = (bool)$alert;
	}

	public function setReason($reason = null)
	{
		if ($reason !== null)
		{
			$this->replyBan->reason = $reason;
		}
	}

	protected function finalSetup()
	{
	}

	protected function _validate()
	{
		$this->finalSetup();

		$this->replyBan->preSave();
		$errors = $this->replyBan->getErrors();

		if ($this->user->is_staff)
		{
			$errors['is_staff'] = \XF::phrase('staff_members_cannot_be_reply_banned');
		}

		return $errors;
	}

	protected function _save()
	{
		$replyBan = $this->replyBan;
		$replyBan->save();

		$this->app->logger()->logModeratorAction('sc_item', $this->item, 'reply_ban', [
			'name' => $replyBan->User->username,
			'reason' => $replyBan->reason
		]);

		$this->sendAlert();

		return $replyBan;
	}

	protected function sendAlert()
	{
		$item = $this->item;
		$replyBan = $this->replyBan;

		if ($item->item_state == 'visible' && $this->alert)
		{
			$extra = [
				'reason' => $replyBan->reason,
				'expiry' => $replyBan->expiry_date
			];

			/** @var \XF\Repository\UserAlert $alertRepo */
			$alertRepo = $this->repository('XF:UserAlert');
			$alertRepo->alert(
				$replyBan->User,
				0, '',
				'sc_item', $item->item_id,
				'reply_ban', $extra
			);
		}
	}
}