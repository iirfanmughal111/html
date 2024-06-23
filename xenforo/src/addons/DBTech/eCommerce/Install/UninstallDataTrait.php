<?php

namespace DBTech\eCommerce\Install;

/**
 * @property \XF\AddOn\AddOn addOn
 * @property \XF\App app
 *
 * @method \XF\Db\AbstractAdapter db()
 * @method \XF\Db\SchemaManager schemaManager()
 */
trait UninstallDataTrait
{
	/**
	 * Methods MUST start at step 4, as steps 1-3 are reserved by the core
	 */
	
	protected function runMiscCleanUp(): void
	{
		// Get rid of change logs
		$this->db()->delete('xf_change_log', "content_type LIKE 'dbtech_ecommerce_%'");
		$this->db()->delete('xf_change_log', "field LIKE 'dbtech_ecommerce_%'");
		
		$this->db()->delete('xf_purchasable', "purchasable_type_id = ?", ['dbtech_ecommerce_order']);
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function uninstallStep4()
	{
		$map = [
			'dbtechEcommerce%',
			'dbtech_ecommerce_license_field_title.%',
			'dbtech_ecommerce_license_field_desc.%',
		];
		$this->deletePhrases($map);
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function uninstallStep5()
	{
		$map = [
			'dbtech_ecommerce_coupon_title.%',
		];
		$this->deletePhrases($map);
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function uninstallStep6()
	{
		$map = [
			'dbtech_ecommerce_product_desc.%',
		];
		$this->deletePhrases($map);
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function uninstallStep7()
	{
		$map = [
			'dbtech_ecommerce_product_tag.%',
		];
		$this->deletePhrases($map);
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function uninstallStep8()
	{
		$map = [
			'dbtech_ecommerce_product_version.%',
		];
		$this->deletePhrases($map);
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function uninstallStep9()
	{
		$map = [
			'dbtech_ecommerce_discount_title.%',
		];
		$this->deletePhrases($map);
	}
}