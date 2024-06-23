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
trait Upgrade1009970Trait
{
	/**
	 *
	 */
	public function upgrade1000032Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_order', function (Alter $table)
		{
			$table->addColumn('shipping_cost', 'decimal', '10,2')->after('automatic_discounts');
		});
		
		$sm->alterTable('xf_dbtech_ecommerce_order_item', function (Alter $table)
		{
			$table->addColumn('shipping_method_id', 'int')->after('coupon_id');
		});
		
		$sm->alterTable('xf_dbtech_ecommerce_product', function (Alter $table)
		{
			$table->addColumn('shipping_zones', 'blob')->after('product_filters');
		});
		
		$sm->alterTable('xf_dbtech_ecommerce_product_cost', function (Alter $table)
		{
			$table->addColumn('product_type', 'enum')->values(['digital', 'physical'])->setDefault('digital')->after('product_id');
			$table->addColumn('title', 'varchar', 100)->after('product_type');
			$table->addColumn('stock', 'int')->after('cost_amount');
			$table->addColumn('weight', 'decimal', '10,2')->setDefault('0.00')->after('stock');
		});
	}
	
	/**
	 *
	 */
	public function upgrade1000032Step2()
	{
		$sm = $this->schemaManager();
		
		$tables = $this->getTables();
		foreach ([
					 'xf_dbtech_ecommerce_country_shipping_zone_map',
					 'xf_dbtech_ecommerce_shipping_combination',
					 'xf_dbtech_ecommerce_shipping_method',
					 'xf_dbtech_ecommerce_shipping_method_shipping_zone_map',
					 'xf_dbtech_ecommerce_shipping_zone',
					 'xf_dbtech_ecommerce_shipping_zone_product_map'
				 ] AS $tableName)
		{
			$sm->createTable($tableName, $tables[$tableName]);
		}
	}
	
	/**
	 *
	 */
	public function upgrade1000032Step3()
	{
		$db = $this->db();
		
		$db->beginTransaction();
		
		$db->update('xf_dbtech_ecommerce_address', ['country_code' => ''], "country_code = '0'");
		$db->update('xf_dbtech_ecommerce_product', ['shipping_zones' => 'a:0:{}'], null);
		
		$db->commit();
	}
	
	/**
	 *
	 */
	public function upgrade1000170Step1()
	{
		$sm = $this->schemaManager();
		
		$sm->alterTable('xf_dbtech_ecommerce_coupon', function (Alter $table)
		{
			$table->addColumn('maximum_products', 'int')->setDefault(0)->after('minimum_products');
			$table->addColumn('maximum_cart_value', 'decimal', '10,2')->setDefault('0.00')->after('minimum_cart_value');
		});
	}
}