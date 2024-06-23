<?php

namespace XenAddons\Showcase\Service\ItemUpdate;

use XF\Service\AbstractNotifier;
use XenAddons\Showcase\Entity\ItemUpdate;

class Notifier extends AbstractNotifier
{
	/**
	 * @var ItemUpdate
	 */
	protected $update;

	public function __construct(\XF\App $app, ItemUpdate $update)
	{
		parent::__construct($app);

		$this->update = $update;
	}

	public static function createForJob(array $extraData)
	{
		$update = \XF::app()->find('XenAddons\Showcase:ItemUpdate', $extraData['updateId']);
		if (!$update)
		{
			return null;
		}

		return \XF::service('XenAddons\Showcase:ItemUpdate\Notifier', $update);
	}

	protected function getExtraJobData()
	{
		return [
			'updateId' => $this->update->item_update_id
		];
	}

	protected function loadNotifiers()
	{
		$notifiers = [
			'mention' => $this->app->notifier('XenAddons\Showcase:ItemUpdate\Mention', $this->update)
		];

		$notifiers['ItemWatch'] = $this->app->notifier('XenAddons\Showcase:ItemUpdate\ItemWatch', $this->update);


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
			function() { return $this->update->canView(); }
		);
	}
}