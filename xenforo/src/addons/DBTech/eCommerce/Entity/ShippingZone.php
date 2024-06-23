<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int|null $shipping_zone_id
 * @property string $title
 * @property bool $active
 * @property int $display_order
 * @property array $countries
 * @property array $shipping_methods
 *
 * RELATIONS
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ShippingZoneProductMap[] $Products
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ShippingCombination[] $ShippingCombinations
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\CountryShippingZoneMap[] $Countries
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ShippingMethodShippingZoneMap[] $ShippingMethods
 */
class ShippingZone extends Entity
{
	/**
	 * @return bool
	 */
	public function rebuildCounters(): bool
	{
		// This is a placeholder function
		// 	return true if you have extended this function
		return false;
	}
	
	/**
	 *
	 */
	public function rebuildShippingCombination()
	{
		if (!$this->shipping_zone_id)
		{
			throw new \LogicException("Shipping zone must be saved first");
		}
		
		/** @var \DBTech\eCommerce\Repository\ShippingCombination $combinationRepo */
		$combinationRepo = $this->repository('DBTech\eCommerce:ShippingCombination');
		$combinationRepo->updateShippingCombinationsForShippingZone($this);
	}
	
	/**
	 *
	 */
	public function rebuildCountryCache()
	{
		$this->repository('DBTech\eCommerce:CountryShippingZoneMap')
			->rebuildShippingZoneAssociationCache([$this->shipping_zone_id])
		;
	}
	
	/**
	 *
	 */
	public function rebuildShippingMethodCache()
	{
		$this->repository('DBTech\eCommerce:ShippingMethodShippingZoneMap')
			->rebuildShippingZoneAssociationCache([$this->shipping_zone_id])
		;
	}
	
	/**
	 *
	 */
	protected function _postSave()
	{
		if (
			$this->isInsert()
			|| $this->isChanged('countries')
			|| $this->isChanged('shipping_methods')
		) {
			$this->rebuildShippingCombination();
		}
		
		if (
			$this->isInsert()
			|| $this->isChanged('countries')
			|| $this->isChanged('shipping_methods')
		) {
			$this->rebuildShippingCombination();
		}
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	protected function _postDelete()
	{
		/** @var \DBTech\eCommerce\Entity\ShippingZoneProductMap $mappedProduct */
		foreach ($this->Products as $mappedProduct)
		{
			$mappedProduct->delete();
			$mappedProduct->Product->rebuildShippingZoneCache();
		}
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_shipping_zone';
		$structure->shortName = 'DBTech\eCommerce:ShippingZone';
		$structure->primaryKey = 'shipping_zone_id';
		$structure->columns = [
			'shipping_zone_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'title' => ['type' => self::STR, 'maxLength' => 100,
						'required' => 'please_enter_valid_title'
			],
			'active' => ['type' => self::BOOL, 'default' => true],
			'display_order' => ['type' => self::UINT, 'default' => 10],
			'countries' => ['type' => self::JSON_ARRAY, 'default' => []],
			'shipping_methods' => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->getters = [];
		$structure->relations = [
			'Products' => [
				'entity' => 'DBTech\eCommerce:ShippingZoneProductMap',
				'type' => self::TO_MANY,
				'conditions' => 'shipping_zone_id',
				'primary' => true,
				'with' => [
					'Product'
				]
			],
			'ShippingCombinations' => [
				'entity' => 'DBTech\eCommerce:ShippingCombination',
				'type' => self::TO_MANY,
				'conditions' => 'shipping_zone_id',
				'primary' => true,
				'cascadeDelete' => true
			],
			'Countries' => [
				'entity' => 'DBTech\eCommerce:CountryShippingZoneMap',
				'type' => self::TO_MANY,
				'conditions' => 'shipping_zone_id',
				'primary' => true,
				'cascadeDelete' => true,
				'with' => [
					'Country'
				]
			],
			'ShippingMethods' => [
				'entity' => 'DBTech\eCommerce:ShippingMethodShippingZoneMap',
				'type' => self::TO_MANY,
				'conditions' => 'shipping_zone_id',
				'primary' => true,
				'cascadeDelete' => true,
				'with' => [
					'ShippingMethod'
				]
			]
		];

		return $structure;
	}
}