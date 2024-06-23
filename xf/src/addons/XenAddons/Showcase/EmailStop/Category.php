<?php

namespace XenAddons\Showcase\EmailStop;

use XF\EmailStop\AbstractHandler;

class Category extends AbstractHandler
{
	public function getStopOneText(\XF\Entity\User $user, $contentId)
	{
		/** @var \XenAddons\Showcase\Entity\Category|null $category */
		$category = \XF::em()->find('XenAddons\Showcase:Category', $contentId);
		$canView = \XF::asVisitor(
			$user,
			function() use ($category) { return $category && $category->canView(); }
		);

		if ($canView)
		{
			return \XF::phrase('stop_notification_emails_from_x', ['title' => $category->title]);
		}
		else
		{
			return null;
		}
	}

	public function getStopAllText(\XF\Entity\User $user)
	{
		return \XF::phrase('stop_notification_emails_from_all_categories');
	}

	public function stopOne(\XF\Entity\User $user, $contentId)
	{
		/** @var \XenAddons\Showcase\Entity\Category $category */
		$category = \XF::em()->find('XenAddons\Showcase:Category', $contentId);
		if ($category)
		{
			/** @var \XenAddons\Showcase\Repository\CategoryWatch $categoryWatchRepo */
			$categoryWatchRepo = \XF::repository('XenAddons\Showcase:CategoryWatch');
			$categoryWatchRepo->setWatchState($category, $user, 'update', ['email_subscribe' => false]);
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