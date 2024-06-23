<?php

namespace DBTech\eCommerce\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

/**
 * Class RebuildLicenses
 *
 * @package DBTech\eCommerce\Cli\Command\Rebuild
 */
class RebuildLicenses extends AbstractRebuildCommand
{
	/**
	 * @return string
	 */
	protected function getRebuildName(): string
	{
		return 'dbtech-ecommerce-licenses';
	}

	/**
	 * @return string
	 */
	protected function getRebuildDescription(): string
	{
		return 'Rebuilds license counters.';
	}

	/**
	 * @return string
	 */
	protected function getRebuildClass(): string
	{
		return 'DBTech\eCommerce:License';
	}
}