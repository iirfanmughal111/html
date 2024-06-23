<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property string $country_code
 * @property string $name
 * @property string $native_name
 * @property string $iso_code
 * @property float $sales_tax_rate
 *
 * RELATIONS
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\Address[] $Addresses
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ShippingCombination[] $ShippingCombinations
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\CountryShippingZoneMap[] $ShippingZones
 */
class Country extends Entity
{
	/**
	 * @return float
	 */
	public function getSalesTaxRate(): float
	{
		return floatval($this->sales_tax_rate != -1.000
			? $this->sales_tax_rate
			: $this->app()->options()->dbtechEcommerceSalesTax['globalDefault']);
	}
	
	/**
	 *
	 */
	public function rebuildShippingCombination()
	{
		if (!$this->country_code)
		{
			throw new \LogicException("Country must be saved first");
		}
		
		/** @var \DBTech\eCommerce\Repository\ShippingCombination $combinationRepo */
		$combinationRepo = $this->repository('DBTech\eCommerce:ShippingCombination');
		$combinationRepo->updateShippingCombinationsForCountry($this);
	}
	
	/**
	 *
	 */
	protected function _postSave()
	{
		if ($this->isInsert())
		{
			$this->rebuildShippingCombination();
		}
	}

	/**
	 * @throws \XF\PrintableException
	 * @throws \XF\Db\Exception
	 */
	protected function _postDelete()
	{
		$this->db()->query("
			UPDATE xf_dbtech_ecommerce_address
			SET country_code = ''
			WHERE country_code = ?
		", $this->country_code);
		
		/** @var \DBTech\eCommerce\Entity\CountryShippingZoneMap $mappedShippingZones */
		foreach ($this->ShippingZones as $mappedShippingZones)
		{
			$mappedShippingZones->delete();
			$mappedShippingZones->ShippingZone->rebuildCountryCache();
		}
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_country';
		$structure->shortName = 'DBTech\eCommerce:Country';
		$structure->primaryKey = 'country_code';
		$structure->columns = [
			'country_code' => ['type' => self::STR, 'maxLength' => 2],
			'name' => ['type' => self::STR, 'maxLength' => 255, 'required' => true],
			'native_name' => ['type' => self::STR, 'maxLength' => 255, 'required' => true],
			'iso_code' => ['type' => self::STR, 'maxLength' => 3],
			'sales_tax_rate' => ['type' => self::FLOAT, 'default' => -1]
		];
		$structure->behaviors = [];
		$structure->getters = [
			'sales_tax_rate'
		];
		$structure->relations = [
			'Addresses' => [
				'entity' => 'DBTech\eCommerce:Address',
				'type' => self::TO_MANY,
				'conditions' => 'country_code'
			],
			'ShippingCombinations' => [
				'entity' => 'DBTech\eCommerce:ShippingCombination',
				'type' => self::TO_MANY,
				'conditions' => 'country_code',
				'primary' => true,
				'cascadeDelete' => true
			],
			'ShippingZones' => [
				'entity' => 'DBTech\eCommerce:CountryShippingZoneMap',
				'type' => self::TO_MANY,
				'conditions' => 'country_code'
			]
		];

		return $structure;
	}
}