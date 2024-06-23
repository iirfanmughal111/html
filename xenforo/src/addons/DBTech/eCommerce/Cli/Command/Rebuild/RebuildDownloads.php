<?php

namespace DBTech\eCommerce\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

/**
 * Class RebuildDownloads
 *
 * @package DBTech\eCommerce\Cli\Command\Rebuild
 */
class RebuildDownloads extends AbstractRebuildCommand
{
	/**
	 * @return string
	 */
	protected function getRebuildName(): string
	{
		return 'dbtech-ecommerce-downloads';
	}

	/**
	 * @return string
	 */
	protected function getRebuildDescription(): string
	{
		return 'Rebuilds download counters.';
	}

	/**
	 * @return string
	 */
	protected function getRebuildClass(): string
	{
		return 'DBTech\eCommerce:Download';
	}
}