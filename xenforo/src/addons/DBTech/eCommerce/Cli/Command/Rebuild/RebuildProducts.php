<?php

namespace DBTech\eCommerce\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

/**
 * Class RebuildProducts
 *
 * @package DBTech\eCommerce\Cli\Command\Rebuild
 */
class RebuildProducts extends AbstractRebuildCommand
{
	/**
	 * @return string
	 */
	protected function getRebuildName(): string
	{
		return 'dbtech-ecommerce-products';
	}
	
	/**
	 * @return string
	 */
	protected function getRebuildDescription(): string
	{
		return 'Rebuilds product counters.';
	}
	
	/**
	 * @return string
	 */
	protected function getRebuildClass(): string
	{
		return 'DBTech\eCommerce:Product';
	}
}