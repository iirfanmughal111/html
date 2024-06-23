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
trait Upgrade2029970Trait
{
	/**
	 *
	 */
	public function upgrade2020032Step1(): void
	{
		$this->applyTables();
	}

	/**
	 *
	 */
	public function upgrade2020032Step2(): void
	{
		$defaultValue = [
			'enabled' => true,
			'digital' => 'buyer',
			'physical' => 'seller',
			'globalDefault' => 0.000,
			'includeTax' => true,
			'enableVat' => true,
			'apiKey' => '',
			'enhancedVatValidation' => false,
		];

		$this->query("
			UPDATE xf_option
			SET default_value = ?
			WHERE option_id = 'dbtechEcommerceSalesTax'
		", json_encode($defaultValue));

		$salesTaxSetting = json_decode($this->db()->fetchOne("
			SELECT option_value
			FROM xf_option
			WHERE option_id = 'dbtechEcommerceSalesTax'
		"), true);

		$update = false;
		foreach (array_keys($defaultValue) AS $key)
		{
			if (!isset($salesTaxSetting[$key]))
			{
				$update = true;
				$salesTaxSetting[$key] = $defaultValue[$key];
			}
		}

		if ($update)
		{
			$this->query("
				UPDATE xf_option
				SET option_value = ?
				WHERE option_id = 'dbtechEcommerceSalesTax'
			", json_encode($salesTaxSetting));
		}
	}

	/**
	 *
	 */
	public function upgrade2020033Step1(): void
	{
		$this->installStep5();
	}

	/**
	 * @param bool $applied
	 * @param int|null $previousVersion
	 *
	 * @return bool
	 */
	protected function applyPermissionsUpgrade2029970(bool &$applied, ?int $previousVersion = null): bool
	{
		if (!$previousVersion || $previousVersion < 2020032)
		{
			$this->applyGlobalPermission('dbtechEcommerce', 'contentVote', 'dbtechEcommerce', 'rate');
			$this->applyContentPermission('dbtechEcommerce', 'contentVote', 'dbtechEcommerce', 'rate');

			$applied = true;
		}

		return $applied;
	}
}