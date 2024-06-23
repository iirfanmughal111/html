<?php

namespace DBTech\eCommerce\Install;

/**
 * @property \XF\AddOn\AddOn addOn
 * @property \XF\App app
 *
 * @method \XF\Db\AbstractAdapter db()
 * @method \XF\Db\SchemaManager schemaManager()
 * @method \XF\Db\Schema\Column addOrChangeColumn($table, $name, $type = null, $length = null)
 */
trait Upgrade3009970Trait
{
	/**
	 *
	 */
	public function upgrade3000031Step1()
	{
		$this->applyTables();
	}

	/**
	 * @return void
	 * @throws \XF\Db\Exception
	 */
	public function upgrade3000031Step2()
	{
		$this->db()->query('
			UPDATE xf_dbtech_ecommerce_product_cost AS cost
			LEFT JOIN xf_dbtech_ecommerce_product AS product USING(product_id)
			SET cost.product_type = product.product_type
			WHERE product.product_type IS NOT NULL
		');
	}

	/**
	 * @return void
	 * @throws \XF\Db\Exception
	 */
	public function upgrade3000032Step1()
	{
		$this->db()->query("
			UPDATE xf_dbtech_ecommerce_license
			SET required_user_group_ids = '[]'
			WHERE required_user_group_ids IS NULL
		");
	}

	/**
	 * @return void
	 * @throws \XF\Db\Exception
	 */
	public function upgrade3000033Step1()
	{
		$this->applyTables();
	}

	/**
	 * @return void
	 * @throws \XF\Db\Exception
	 */
	public function upgrade3000033Step2()
	{
		$this->db()->query("
			UPDATE xf_dbtech_ecommerce_license
			SET required_user_group_ids = '[]'
			WHERE required_user_group_ids IS NULL
		");
	}
}