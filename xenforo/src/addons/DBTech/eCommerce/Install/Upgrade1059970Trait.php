<?php

namespace DBTech\eCommerce\Install;

use XF\Db\Schema\Alter;

/**
 * @property \XF\AddOn\AddOn addOn
 * @property \XF\App app
 *
 * @method \XF\Db\AbstractAdapter db()
 * @method \XF\Db\SchemaManager schemaManager()
 * @method \XF\Db\Schema\Column addOrChangeColumn($table, $name, $type = null, $length = null)
 */
trait Upgrade1059970Trait
{
	/**
	 *
	 */
	public function upgrade1050170Step1()
	{
		$this->renamePhrases([
			'dbtech_ecommerce_outdated' => 'dbtech_ecommerce_update_available',
		]);
	}

	/**
	 *
	 */
	public function upgrade1050370Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_dbtech_ecommerce_address', function (Alter $table)
		{
			$table->addColumn('is_default', 'tinyint', 1)
				->setDefault(0)
				->after('sales_tax_id')
			;
		});
	}

	/**
	 *
	 */
	public function upgrade1050470Step1()
	{
		$sm = $this->schemaManager();

		$sm->alterTable('xf_dbtech_ecommerce_address', function (Alter $table)
		{
			$table->addColumn('is_default', 'tinyint', 1)
				->setDefault(0)
				->after('sales_tax_id')
			;
		});
	}

	/**
	 * @param $previousVersion
	 * @param array $stateChanges
	 */
	protected function postUpgrade1050370($previousVersion, array &$stateChanges)
	{
		if ($previousVersion && $previousVersion < 1050370)
		{
			/** @var \DBTech\eCommerce\Repository\GeoIp $geoIpRepo */
			$geoIpRepo = \XF::repository('DBTech\eCommerce:GeoIp');
			$geoIpRepo->geoIpUpdate();
		}
	}

	/**
	 * @param $previousVersion
	 * @param array $stateChanges
	 */
	protected function postUpgrade1050670($previousVersion, array &$stateChanges)
	{
		if ($previousVersion && $previousVersion < 1050670)
		{
			/** @var \DBTech\eCommerce\Repository\GeoIp $geoIpRepo */
			$geoIpRepo = \XF::repository('DBTech\eCommerce:GeoIp');
			$geoIpRepo->geoIpUpdate();
		}
	}
}