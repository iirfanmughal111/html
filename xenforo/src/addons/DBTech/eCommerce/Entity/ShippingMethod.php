<?php

namespace DBTech\eCommerce\Entity;

use MathParser\StdMathParser;
use MathParser\Interpreting\Evaluator;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int|null $shipping_method_id
 * @property string $title
 * @property bool $active
 * @property int $display_order
 * @property string $cost_formula
 *
 * RELATIONS
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ShippingCombination[] $ShippingCombinations
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ShippingMethodShippingZoneMap[] $ShippingZones
 */
class ShippingMethod extends Entity
{
	/**
	 * @param Address $shippingAddress
	 *
	 * @return bool
	 */
	public function isApplicable(Address $shippingAddress): bool
	{
		if (!$shippingAddress)
		{
			return false;
		}
		
		if (!count($this->ShippingCombinations))
		{
			return false;
		}
		
		return $shippingAddress->ApplicableShippingMethods->offsetExists($this->shipping_method_id);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return bool
	 */
	public function isApplicableToProduct(Product $product): bool
	{
		foreach ($this->ShippingZones as $shippingZoneId => $shippingZone)
		{
			if ($product->ShippingZones->offsetExists($shippingZoneId))
			{
				return true;
			}
		}
		
		return false;
	}

	/**
	 * @param OrderItem $item
	 *
	 * @return string
	 * @throws \Exception
	 * @throws \Exception
	 */
	public function getEstimatedShippingCost(OrderItem $item): string
	{
		// Init some variables
		$quantity = 1;
		$cost = $item->base_price;
		$weight = floatval($item->Cost->weight);
		
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		foreach ($item->Order->Items as $orderItem)
		{
			if (
				$orderItem->Product->hasShippingFunctionality()
				&& $orderItem->shipping_method_id == $this->shipping_method_id
				&& $orderItem->order_item_id != $item->order_item_id
			) {
				$quantity++;
				$cost += $orderItem->base_price;
				$weight += floatval($orderItem->Cost->weight);
			}
		}
		
		// Make sure it's only using two decimals
		$weight = sprintf("%.2f", $weight);
		
		$parser = new StdMathParser();
		$AST = $parser->parse($this->cost_formula);
		
		$evaluator = new Evaluator();
		$evaluator->setVariables([
			'q' => $quantity, // Total quantity of items using this shipping method
			'c' => $cost, // Total base cost of items using this shipping method
			'w' => $weight, // Total weight of items using this shipping method
		]);
		
		return $AST->accept($evaluator);
	}

	/**
	 * @param Order $order
	 *
	 * @return string
	 * @throws \Exception
	 * @throws \Exception
	 */
	public function calculateShippingCost(Order $order): string
	{
		// Init some variables
		$quantity = 0;
		$cost = 0.00;
		$weight = 0.00;
		
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		foreach ($order->Items as $orderItem)
		{
			if (
				$orderItem->Product->hasShippingFunctionality()
				&& $orderItem->shipping_method_id == $this->shipping_method_id
			) {
				$quantity++;
				$cost += $orderItem->base_price;
				$weight += floatval($orderItem->Cost->weight);
			}
		}
		
		// Make sure it's only using two decimals
		$weight = sprintf("%.2f", $weight);
		
		$parser = new StdMathParser();
		$AST = $parser->parse($this->cost_formula);
		
		if ($AST === null)
		{
			throw new \LogicException("Shipping method formula could not be parsed.");
		}
		
		$evaluator = new Evaluator();
		$evaluator->setVariables([
			'q' => $quantity, // Total quantity of items using this shipping method
			'c' => $cost, // Total base cost of items using this shipping method
			'w' => $weight, // Total weight of items using this shipping method
		]);
		
		return $AST->accept($evaluator);
	}
	
	public function rebuildShippingCombination()
	{
		if (!$this->shipping_method_id)
		{
			throw new \LogicException("Shipping method must be saved first");
		}
		
		/** @var \DBTech\eCommerce\Repository\ShippingCombination $combinationRepo */
		$combinationRepo = $this->repository('DBTech\eCommerce:ShippingCombination');
		$combinationRepo->updateShippingCombinationsForShippingMethod($this);
	}
	
	/**
	 * @param $costFormula
	 *
	 * @return bool
	 */
	protected function verifyCostFormula(&$costFormula): bool
	{
		if (!strlen($costFormula))
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_cost_formula'), 'cost_formula');
			return false;
		}
		
		try
		{
			$parser = new StdMathParser();
			$AST = $parser->parse($costFormula);
			
			if ($AST === null)
			{
				throw new \LogicException("Shipping method formula could not be parsed.");
			}
			
			// The variable values don't matter, as we're only testing validity
			$evaluator = new Evaluator();
			$evaluator->setVariables([
				'q' => 15,
				'c' => 15,
				'w' => 15,
			]);
			
			$AST->accept($evaluator);
		}
		catch (\Exception $e)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_cost_formula'), 'cost_formula');
			return false;
		}
		
		return true;
	}
	
	/**
	 *
	 */
	protected function _postSave()
	{
		if (
			$this->isInsert()
			|| $this->isChanged('cost_formula')
		) {
			$this->rebuildShippingCombination();
		}
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	protected function _postDelete()
	{
		/** @var \DBTech\eCommerce\Entity\CountryShippingZoneMap $mappedShippingZones */
		foreach ($this->ShippingZones as $mappedShippingZones)
		{
			$mappedShippingZones->delete();
			$mappedShippingZones->ShippingZone->rebuildShippingMethodCache();
		}
		
		$this->db()->update('xf_dbtech_ecommerce_order_item', [
			'shipping_method_id' => 0
		], 'shipping_method_id = ?', $this->shipping_method_id);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_shipping_method';
		$structure->shortName = 'DBTech\eCommerce:ShippingMethod';
		$structure->primaryKey = 'shipping_method_id';
		$structure->columns = [
			'shipping_method_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'title' => ['type' => self::STR, 'maxLength' => 100,
						'required' => 'please_enter_valid_title'
			],
			'active' => ['type' => self::BOOL, 'default' => true],
			'display_order' => ['type' => self::UINT, 'default' => 10],
			'cost_formula' => ['type' => self::STR, 'required' => true]
		];
		$structure->getters = [];
		$structure->relations = [
			'ShippingCombinations' => [
				'entity' => 'DBTech\eCommerce:ShippingCombination',
				'type' => self::TO_MANY,
				'conditions' => 'shipping_method_id',
				'primary' => true,
				'cascadeDelete' => true
			],
			'ShippingZones' => [
				'entity' => 'DBTech\eCommerce:ShippingMethodShippingZoneMap',
				'type' => self::TO_MANY,
				'conditions' => 'shipping_method_id',
				'key' => 'shipping_zone_id',
				'primary' => true
			]
		];

		return $structure;
	}
}