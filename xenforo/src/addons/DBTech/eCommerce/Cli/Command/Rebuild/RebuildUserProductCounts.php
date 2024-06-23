<?php

namespace DBTech\eCommerce\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

/**
 * Class RebuildUserProductCounts
 *
 * @package DBTech\eCommerce\Cli\Command\Rebuild
 */
class RebuildUserProductCounts extends AbstractRebuildCommand
{
	/**
	 * @return string
	 */
	protected function getRebuildName(): string
	{
		return 'dbtech-ecommerce-user-product-counts';
	}
	
	/**
	 * @return string
	 */
	protected function getRebuildDescription(): string
	{
		return 'Rebuilds product related user counters.';
	}
	
	/**
	 * @return string
	 */
	protected function getRebuildClass(): string
	{
		return 'DBTech\eCommerce:UserProductCount';
	}
}