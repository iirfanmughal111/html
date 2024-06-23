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
trait Upgrade1069970Trait
{
	/**
	 *
	 */
	public function upgrade1060070Step1()
	{
		$validateSetting = $this->db()->fetchOne("
			SELECT option_value
			FROM xf_option
			WHERE option_id = 'dbtechEcommerceValidateBillingCountry'
		");

		$addressSetting = [
			'required' => true,
			'onlyPaid' => true,
			'validate' => $validateSetting
		];

		$this->query("
			UPDATE xf_option
			SET option_value = ?
			WHERE option_id = 'dbtechEcommerceAddress'
		", json_encode($addressSetting));
	}
}