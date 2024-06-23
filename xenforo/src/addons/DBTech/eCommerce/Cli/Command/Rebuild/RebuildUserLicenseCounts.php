<?php

namespace DBTech\eCommerce\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

/**
 * Class RebuildUserLicenseCounts
 *
 * @package DBTech\eCommerce\Cli\Command\Rebuild
 */
class RebuildUserLicenseCounts extends AbstractRebuildCommand
{
	/**
	 * @return string
	 */
	protected function getRebuildName(): string
	{
		return 'dbtech-ecommerce-user-license-counts';
	}

	/**
	 * @return string
	 */
	protected function getRebuildDescription(): string
	{
		return 'Rebuilds license related user counters.';
	}

	/**
	 * @return string
	 */
	protected function getRebuildClass(): string
	{
		return 'DBTech\eCommerce:UserLicenseCount';
	}
}