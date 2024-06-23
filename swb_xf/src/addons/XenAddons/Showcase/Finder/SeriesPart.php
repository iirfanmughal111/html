<?php

namespace XenAddons\Showcase\Finder;

use XF\Mvc\Entity\Finder;

class SeriesPart extends Finder
{
	public function inSeries(\XenAddons\Showcase\Entity\SeriesItem $series, array $limits = [])
	{
		$limits = array_replace([
			
		], $limits);

		$this->where('series_id', $series->series_id);

		return $this;
	}
	
	public function byUser(\XF\Entity\User $user)
	{
		$this->where('user_id', $user->user_id);
	
		return $this;
	}	
	
	public function forTOC()
	{
		$visitor = \XF::visitor();
	
		$this->with('Item');
	
		return $this;
	}
}