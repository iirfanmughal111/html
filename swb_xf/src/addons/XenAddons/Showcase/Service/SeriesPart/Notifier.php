<?php

namespace XenAddons\Showcase\Service\SeriesPart;

use XF\Service\AbstractNotifier;
use XenAddons\Showcase\Entity\SeriesPart;

class Notifier extends AbstractNotifier
{
	/**
	 * @var SeriesPart
	 */
	protected $seriesPart;

	public function __construct(\XF\App $app, SeriesPart $seriesPart)
	{
		parent::__construct($app);

		$this->seriesPart = $seriesPart;
	}

	public static function createForJob(array $extraData)
	{
		$seriesPart = \XF::app()->find('XenAddons\Showcase:SeriesPart', $extraData['partId']);
		if (!$seriesPart)
		{
			return null;
		}

		return \XF::service('XenAddons\Showcase:SeriesPart\Notifier', $seriesPart);
	}

	protected function getExtraJobData()
	{
		return [
			'partId' => $this->seriesPart->series_part_id
		];
	}

	protected function loadNotifiers()
	{
		$notifiers = [];

		$notifiers['seriesWatch'] = $this->app->notifier('XenAddons\Showcase:SeriesPart\SeriesWatch', $this->seriesPart);

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
			function() { return $this->seriesPart->canView(); }
		);
	}
}