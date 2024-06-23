<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ProductCost
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductCost extends Repository
{
	/**
	 * @return string
	 */
	protected function getEntityIdentifier(): string
	{
		return 'DBTech\eCommerce:ProductCost';
	}
	
	/**
	 * @param int $productId
	 * @param array $costs
	 *
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function updateContentAssociations(int $productId, array $costs)
	{
		$structureData = $this->getStructureData();

		$productCosts = $this->em->findByIds($structureData['entity'], array_keys($costs));
		
		/** @var \DBTech\eCommerce\Entity\ProductCost $productCost */
		foreach ($productCosts AS $productCost)
		{
			if (!isset($costs[$productCost->product_cost_id]))
			{
				// Shouldn't happen but
				continue;
			}

			$cost = $costs[$productCost->product_cost_id];
			$renewalType = isset($cost['renewal_type']) ? $cost['renewal_type'] : 'global';
			$productCost->bulkSet([
				'product_id' => $productId,
				'title' => $cost['title'],
				'description' => $cost['description'],
				'cost_amount' => $cost['cost_amount'],
				'renewal_type' => $renewalType,
				'renewal_amount' => isset($cost['renewal_amount']) && $renewalType !== 'global' ? $cost['renewal_amount'] : null,
				'stock' => $cost['stock'],
				'weight' => $cost['weight'],
				'length_amount' => $cost['length_amount'],
				'length_unit' => $cost['length_unit'],
				'highlighted' => $cost['highlighted']
			]);
			$productCost->save();
		}

		$this->rebuildContentAssociationCache([$productId]);
	}

	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$productIds = array_keys($cache);
		$products = $this->em->findByIds('DBTech\eCommerce:Product', $productIds);
		
		foreach ($products AS $product)
		{
			/** @var \DBTech\eCommerce\Entity\Product $product */
			
			$isPaid = false;
			foreach ($cache[$product->product_id] as $cost)
			{
				if ($cost['cost_amount'] > 0)
				{
					$isPaid = true;
					break;
				}
			}
			
			$product->cost_cache = $cache[$product->product_id];
			$product->is_paid = $isPaid;
			$product->saveIfChanged();
		}
	}

	/**
	 * @param array $contentIds
	 */
	public function rebuildContentAssociationCache(array $contentIds)
	{
		if (!$contentIds)
		{
			return;
		}

		$structureData = $this->getStructureData();

		$newCache = [];

		$costAssociations = $this->finder($structureData['entity'])
			->where('product_id', $contentIds)
			->order('cost_amount', 'ASC');
		foreach ($costAssociations->fetch() AS $cost)
		{
			$key = $cost->get('product_id');
			$newCache[$key][] = $cost->toArray();
		}

		$this->updateAssociationCache($newCache);
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	public function updateUnusedCostData()
	{
		$this->db()->query('
			UPDATE xf_dbtech_ecommerce_product_cost AS product_cost
			LEFT JOIN xf_dbtech_ecommerce_product AS product USING(product_id)
			SET product_cost.product_id = 0, product_cost.creation_date = 0
			WHERE product.product_id IS NULL
		');
	}
	
	/**
	 * @param int|null $cutOff
	 */
	public function deleteUnassociatedCosts(?int $cutOff = null)
	{
		if ($cutOff === null)
		{
			$cutOff = \XF::$time - 86400;
		}
		
		$costs = $this->finder('DBTech\eCommerce:ProductCost')
			->where('creation_date', '<', $cutOff)
			->where('product_id', 0)
			->fetch(1000);
		foreach ($costs AS $cost)
		{
			$cost->delete();
		}
	}

	/**
	 * @return array
	 */
	protected function getStructureData(): array
	{
		$entity = $this->getEntityIdentifier();
		$structure = $this->em->getEntityStructure($entity);

		return [
			'entity' => $entity,
			'table' => $structure->table,
			'key' => $structure->primaryKey
		];
	}
}