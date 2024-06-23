<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class ShippingZone extends Repository
{
	/**
	 * @return Finder
	 */
	public function findShippingZonesForList(): Finder
	{
		return $this->finder('DBTech\eCommerce:ShippingZone')->order(['display_order', 'title'], 'asc');
	}
	
	/**
	 * @return array|\XF\Mvc\Entity\ArrayCollection
	 */
	public function getShippingZoneTitlePairs()
	{
		return $this->findShippingZonesForList()->fetch()->pluckNamed('title', 'shipping_zone_id');
	}
}