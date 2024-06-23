<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractWidget;

class ShowcaseStatistics extends AbstractWidget
{
	public function render()
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!method_exists($visitor, 'canViewShowcaseItems') || !$visitor->canViewShowcaseItems())
		{
			return '';
		}

		$simpleCache = $this->app->simpleCache();
		
		$viewParams = [
			'statsCache' => $simpleCache['XenAddons/Showcase']['statisticsCache']
		];
		return $this->renderer('xa_sc_widget_showcase_statistics', $viewParams);
	}

	public function getOptionsTemplate()
	{
		return;
	}
}