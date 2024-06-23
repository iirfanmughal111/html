<?php

namespace DBTech\eCommerce\Alert;

use XF\Alert\AbstractHandler;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Alert
 */
class Product extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'Parent',
			'Parent.permissionSet',
			'permissionSet'
		];
	}

	/**
	 * @return array
	 */
	public function getOptOutActions(): array
	{
		return [
			'insert',
			'mention',
			'reaction'
		];
	}

	/**
	 * @return int
	 */
	public function getOptOutDisplayOrder(): int
	{
		return 395;
	}
}