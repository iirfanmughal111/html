<?php

namespace XenAddons\Showcase\EmailStop;

use XF\EmailStop\AbstractHandler;

class Item extends AbstractHandler
{
	public function getStopOneText(\XF\Entity\User $user, $contentId)
	{
		/** @var \XenAddons\Showcase\Entity\Item|null $item */
		$item = \XF::em()->find('XenAddons\Showcase:Item', $contentId);
		$canView = \XF::asVisitor(
			$user,
			function() use ($item) { return $item && $item->canView(); }
		);

		if ($canView)
		{
			return \XF::phrase('stop_notification_emails_from_x', ['title' => $item->title]);
		}
		else
		{
			return null;
		}
	}

	public function getStopAllText(\XF\Entity\User $user)
	{
		return \XF::phrase('xa_sc_stop_notification_emails_from_all_items');
	}

	public function stopOne(\XF\Entity\User $user, $contentId)
	{
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = \XF::em()->find('XenAddons\Showcase:Item', $contentId);
		if ($item)
		{
			/** @var \XenAddons\Showcase\Repository\ItemWatch $itemWatchRepo */
			$itemWatchRepo = \XF::repository('XenAddons\Showcase:ItemWatch');
			$itemWatchRepo->setWatchStateForAll($user, 'update', ['email_subscribe' => 0]);
		}
	}

	public function stopAll(\XF\Entity\User $user)
	{
		/** @var \XenAddons\Showcase\Repository\ItemWatch $itemWatchRepo */
		$itemWatchRepo = \XF::repository('XenAddons\Showcase:ItemWatch');
		$itemWatchRepo->setWatchStateForAll($user, 'update', ['email_subscribe' => 0]);

		/** @var \XenAddons\Showcase\Repository\CategoryWatch $categoryWatchRepo */
		$categoryWatchRepo = \XF::repository('XenAddons\Showcase:CategoryWatch');
		$categoryWatchRepo->setWatchStateForAll($user, 'update', ['send_email' => 0]);
	}
}