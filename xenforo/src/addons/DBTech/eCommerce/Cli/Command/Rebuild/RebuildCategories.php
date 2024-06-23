<?php

namespace DBTech\eCommerce\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

/**
 * Class RebuildCategories
 *
 * @package DBTech\eCommerce\Cli\Command\Rebuild
 */
class RebuildCategories extends AbstractRebuildCommand
{
	/**
	 * @return string
	 */
	protected function getRebuildName(): string
	{
		return 'dbtech-ecommerce-categories';
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
		return 'DBTech\eCommerce:Category';
	}
}