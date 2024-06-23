<?php

namespace XenAddons\Showcase\Service\Page;

use XF\Service\AbstractNotifier;
use XenAddons\Showcase\Entity\ItemPage;

class Notifier extends AbstractNotifier
{
	/**
	 * @var ItemPage
	 */
	protected $page;

	public function __construct(\XF\App $app, ItemPage $page)
	{
		parent::__construct($app);

		$this->page = $page;
	}

	public static function createForJob(array $extraData)
	{
		$page = \XF::app()->find('XenAddons\Showcase:ItemPage', $extraData['pageId']);
		if (!$page)
		{
			return null;
		}

		return \XF::service('XenAddons\Showcase:Page\Notifier', $page);
	}

	protected function getExtraJobData()
	{
		return [
			'pageId' => $this->page->page_id
		];
	}

	protected function loadNotifiers()
	{
		$notifiers = [];
		
		$notifiers['itemWatch'] = $this->app->notifier('XenAddons\Showcase:Page\ItemWatch', $this->page);

		return $notifiers;
	}

	protected function loadExtraUserData(array $users)
	{
		return;
	}

	protected function canUserViewContent(\XF\Entity\User $user)
	{
		return \XF::asVisitor(
			$user,
			function() { return $this->page->canView(); }
		);
	}
}