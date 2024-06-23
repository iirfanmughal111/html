<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class Delete extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	/**
	 * @var \XF\Entity\User|null
	 */
	protected $user;

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

	public function delete($type, $reason = '', $convertToThread = false)
	{
		$user = $this->user ?: \XF::visitor();
		$wasVisible = $this->item->isVisible();

		if ($type == 'soft')
		{
			$result = $this->item->softDelete($reason, $user);
		}
		else
		{
			$result = $this->item->delete();
		}

		if (!$convertToThread)
		{
			$this->updateItemThread();
		}

		if ($result && $wasVisible && $this->alert && $this->item->user_id != $user->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
			$itemRepo = $this->repository('XenAddons\Showcase:Item');
			$itemRepo->sendModeratorActionAlert($this->item, 'delete', $this->alertReason);
		}

		return $result;
	}

	protected function updateItemThread()
	{
		$item = $this->item;
		$thread = $item->Discussion;
		if (!$thread)
		{
			return;
		}

		$asUser = $item->User ?: $this->repository('XF:User')->getGuestUser($item->username);

		\XF::asVisitor($asUser, function() use ($thread)
		{
			$replier = $this->setupItemThreadReply($thread);
			if ($replier && $replier->validate())
			{
				$existingLastPostDate = $replier->getThread()->last_post_date;

				$post = $replier->save();
				$this->afterItemThreadReplied($post, $existingLastPostDate);

				\XF::runLater(function() use ($replier)
				{
					$replier->sendNotifications();
				});
			}
		});
	}

	protected function setupItemThreadReply(\XF\Entity\Thread $thread)
	{
		/** @var \XF\Service\Thread\Replier $replier */
		$replier = $this->service('XF:Thread\Replier', $thread);
		$replier->setIsAutomated();
		$replier->setMessage($this->getThreadReplyMessage(), false);

		return $replier;
	}

	protected function getThreadReplyMessage()
	{
		$item = $this->item;

		$phrase = \XF::phrase('xa_sc_item_thread_delete', [
			'title' => $item->title_,
			'description' => $item->description_,
			'username' => $item->User ? $item->User->username : $item->username
		]);

		return $phrase->render('raw');
	}

	protected function afterItemThreadReplied(\XF\Entity\Post $post, $existingLastPostDate)
	{
		$thread = $post->Thread;

		if (\XF::visitor()->user_id)
		{
			if ($post->Thread->getVisitorReadDate() >= $existingLastPostDate)
			{
				$this->repository('XF:Thread')->markThreadReadByVisitor($thread);
			}
				
			$this->repository('XF:ThreadWatch')->autoWatchThread($thread, \XF::visitor(), false);
		}
	}
}