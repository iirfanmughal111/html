<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\PrintableException;

class SeriesPart extends Repository
{
	public function findPartsInSeries(\XenAddons\Showcase\Entity\SeriesItem $series, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\SeriesPart $finder */
		$finder = $this->finder('XenAddons\Showcase:SeriesPart');
		$finder->inSeries($series, $limits)
			->where('Item.item_state', 'visible')
			->setDefaultOrder('display_order', 'asc');

		return $finder;
	}
	
	public function findPartsInSeriesManageSeries(\XenAddons\Showcase\Entity\SeriesItem $series, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\SeriesPart $finder */
		$finder = $this->finder('XenAddons\Showcase:SeriesPart');
		$finder->inSeries($series, $limits)
			->setDefaultOrder('display_order', 'asc');
	
		return $finder;
	}

	public function findPartsInSeriesDeleteSeries(\XenAddons\Showcase\Entity\SeriesItem $series, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\SeriesPart $finder */
		$finder = $this->finder('XenAddons\Showcase:SeriesPart');
		$finder->inSeries($series, $limits)
			->setDefaultOrder('display_order', 'asc');
	
		return $finder;
	}
}