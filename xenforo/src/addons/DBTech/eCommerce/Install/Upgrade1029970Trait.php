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
trait Upgrade1029970Trait
{
	/**
	 *
	 */
	public function upgrade1020031Step1()
	{
		$sm = $this->schemaManager();
		
		$tables = $this->getTables();
		
		$key = 'xf_dbtech_ecommerce_income_stats_daily';
		$sm->createTable($key, $tables[$key]);
		
		$key = 'xf_dbtech_ecommerce_api_request_log';
		$sm->createTable($key, $tables[$key]);
	}
	
	/**
	 *
	 */
	public function upgrade1020031Step2()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_user', function (Alter $table)
		{
			$table->addColumn('dbtech_ecommerce_api_key', 'char', 32)->setDefault('')->after('dbtech_ecommerce_is_distributor');
		});
	}
	
	/**
	 * @throws \XF\Db\Exception
	 */
	public function upgrade1020031Step3()
	{
		$db = $this->db();
		
		$db->query('
			UPDATE `xf_dbtech_ecommerce_product` AS `product`
			SET `discussion_thread_id` = IFNULL(
				(
					SELECT MIN(`discussion_thread_id`)
					FROM `xf_dbtech_ecommerce_download` AS `download`
					WHERE `discussion_thread_id` > 0
						AND `product_id` = `product`.`product_id`
				), 0
			)
			WHERE `discussion_thread_id` = 0
		');
	}
	
	/**
	 *
	 */
	public function upgrade1020032Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_address', function (Alter $table)
		{
			$table->addColumn('email', 'varchar', 120)->nullable(true);
		});
	}
	
	/**
	 * @param bool $applied
	 * @param int|null $previousVersion
	 *
	 * @return bool
	 */
	protected function applyPermissionsUpgrade1029970(bool &$applied, ?int $previousVersion = null): bool
	{
		if (!$previousVersion || $previousVersion < 1020031)
		{
			$this->applyGlobalPermission('dbtechEcommerce', 'viewIncomeStats', 'general', 'viewNode');
			
			$applied = true;
		}
		
		if ($previousVersion < 1020031)
		{
			// Normally you'd do !$previousVersion as well, but I want to base this off of existing permissions
			$this->applyGlobalPermission('dbtechEcommerceAdmin', 'viewAnyIncomeStats', 'dbtechEcommerceAdmin', 'viewLicenses');
			
			$applied = true;
		}
		
		return $applied;
	}
	
	/**
	 * @param $previousVersion
	 * @param array $stateChanges
	 */
	protected function postUpgrade1020470($previousVersion, array &$stateChanges)
	{
		/** @var \DBTech\eCommerce\Repository\GeoIp $geoIpRepo */
		$geoIpRepo = \XF::repository('DBTech\eCommerce:GeoIp');
		$geoIpRepo->geoIpUpdate();
	}
}