<?php

namespace DBTech\eCommerce\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

/**
 * Class AmountSpent
 *
 * @package DBTech\eCommerce\Cli\Command\Rebuild
 */
class AmountSpent extends AbstractRebuildCommand
{
	/**
	 * @return string
	 */
	protected function getRebuildName(): string
	{
		return 'dbtech-ecommerce-amount-spent';
	}

	/**
	 * @return string
	 */
	protected function getRebuildDescription(): string
	{
		return 'Rebuilds the counter for how much the user has spent.';
	}

	/**
	 * @return string
	 */
	protected function getRebuildClass(): string
	{
		return 'DBTech\eCommerce:AmountSpent';
	}
}