<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractPrefix;

/**
 * Class ProductPrefix
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductPrefix extends AbstractPrefix
{
	/**
	 * @return string
	 */
	protected function getRegistryKey(): string
	{
		return 'dbtEcPrefixes';
	}
	
	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:ProductPrefix';
	}
}