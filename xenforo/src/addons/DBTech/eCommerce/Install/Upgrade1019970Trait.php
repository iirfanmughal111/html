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
trait Upgrade1019970Trait
{
	/**
	 *
	 */
	public function upgrade1010031Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_order', function (Alter $table)
		{
			$table->changeColumn('order_state', 'varchar', 25);
		});
	}
	
	public function upgrade1010054Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_product', function (Alter $table)
		{
			$table->addColumn('temporary_extra_group_ids', 'varbinary', 255)->setDefault('')->after('extra_group_ids');
		});
	}
	
	public function upgrade1010070Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_download', function (Alter $table)
		{
			$table->addColumn('is_unstable', 'tinyint')->setDefault(0)->after('has_bug_fixes');
		});
	}
	
	/**
	 * @param $previousVersion
	 * @param array $stateChanges
	 */
	protected function postUpgrade1010054($previousVersion, array &$stateChanges)
	{
		$this->app->jobManager()->enqueueUnique(
			'dbtechEcommerceRecalculateAmount',
			'DBTech\eCommerce:AmountSpent',
			[],
			false
		);

		$this->app->jobManager()->enqueueUnique(
			'dbtechEcommerceRecalculateLicenses',
			'DBTech\eCommerce:License',
			[],
			false
		);
	}
}