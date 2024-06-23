<?php

namespace XenAddons\Showcase\Service\Page;

use XenAddons\Showcase\Entity\ItemPage;

class Reassign extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\ItemPage
	 */
	protected $page;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemPage $page)
	{
		parent::__construct($app);
		$this->page = $page;
	}

	public function getItemPage()
	{
		return $this->page;
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
		$page = $this->page;
		$oldUser = $page->User;
		$reassigned = ($page->user_id != $newUser->user_id);

		$page->user_id = $newUser->user_id;
		$page->username = $newUser->username;
		$page->save();

		if ($reassigned && $page->isVisible() && $this->alert) 
		{
			if ($oldUser && \XF::visitor()->user_id != $oldUser->user_id)
			{	
				/** @var \XenAddons\Showcase\Repository\ItemPage $pageRepo */
				$pageRepo = $this->repository('XenAddons\Showcase:ItemPage');
				$pageRepo->sendModeratorActionAlert(
					$this->page, 'reassign_from', $this->alertReason, ['to' => $newUser->username], $oldUser
				);
			}
			
			if (\XF::visitor()->user_id != $newUser->user_id)
			{
				/** @var \XenAddons\Showcase\Repository\ItemPage $pageRepo */
				$pageRepo = $this->repository('XenAddons\Showcase:ItemPage');
				$pageRepo->sendModeratorActionAlert(
					$this->page, 'reassign_to', $this->alertReason, [], $newUser
				);
			}
		}

		return $reassigned;
	}
}