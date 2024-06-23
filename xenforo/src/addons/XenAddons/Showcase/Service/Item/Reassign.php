<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class Reassign extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
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
		$item = $this->item;
		$oldUser = $item->User;
		$reassigned = ($item->user_id != $newUser->user_id);

		$item->user_id = $newUser->user_id;
		$item->username = $newUser->username;
		$item->save();

		if ($reassigned)
		{
			if($this->alert)
			{
				if ($oldUser && \XF::visitor()->user_id != $oldUser->user_id)
				{
					/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
					$itemRepo = $this->repository('XenAddons\Showcase:Item');
					$itemRepo->sendModeratorActionAlert(
						$this->item, 'reassign_from', $this->alertReason, ['to' => $newUser->username], $oldUser
					);
				}
	
				if (\XF::visitor()->user_id != $newUser->user_id)
				{
					/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
					$itemRepo = $this->repository('XenAddons\Showcase:Item');
					$itemRepo->sendModeratorActionAlert(
						$this->item, 'reassign_to', $this->alertReason, [], $newUser
					);
				}
			}
			
			if ($item->Discussion)
			{
				$this->reassignThread($item->Discussion, $newUser);
			}
		}

		return $reassigned;
	}
	
	protected function reassignThread(\XF\Entity\Thread $thread, \XF\Entity\User $newUser)
	{
		$thread->user_id = $newUser->user_id;
		$thread->username = $newUser->username;
		$thread->save();
	
		$firstPost = $thread->FirstPost;
		$firstPost->user_id = $newUser->user_id;
		$firstPost->username = $newUser->username;
		$firstPost->save();
	
		$thread->rebuildFirstPostInfo();
		$thread->rebuildLastPostInfo();
		$thread->save();
	
		$thread->Forum->rebuildLastPost();
		$thread->Forum->save();
	}
}