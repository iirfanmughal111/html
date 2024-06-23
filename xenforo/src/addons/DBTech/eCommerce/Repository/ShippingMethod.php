<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class ShippingMethod extends Repository
{
	/**
	 * @return Finder
	 */
	public function findShippingMethodsForList(): Finder
	{
		return $this->finder('DBTech\eCommerce:ShippingMethod')->order(['display_order', 'title'], 'asc');
	}
	
	/**
	 * @return array|\XF\Mvc\Entity\ArrayCollection
	 */
	public function getShippingMethodTitlePairs()
	{
		return $this->findShippingMethodsForList()->fetch()->pluckNamed('title', 'shipping_method_id');
	}
	
	
	
	/**
	 * @param bool $includeEmpty
	 * @param bool $includeAll
	 *
	 * @return array
	 */
	public function getShippingMethodSelectData(bool $includeEmpty = true, bool $includeAll = false)
	{
		$choices = [];
		if ($includeEmpty)
		{
			$choices = [
				'' => \XF::phrase('(none)')
			];
		}
		if ($includeAll)
		{
			$choices = [
				'-1' => \XF::phrase('(all)')
			];
		}
		
		return $choices + $this->getShippingMethodTitlePairs();
	}
}