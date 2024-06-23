<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractFieldMap;

/**
 * Class OrderFieldMap
 *
 * @package DBTech\eCommerce\Repository
 */
class OrderFieldMap extends AbstractFieldMap
{
	/**
	 * @return string
	 */
	protected function getMapEntityIdentifier(): string
	{
		return 'DBTech\eCommerce:OrderFieldMap';
	}
	
	/**
	 * @param \XF\Entity\AbstractField $field
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	protected function getAssociationsForField(\XF\Entity\AbstractField $field)
	{
		return $field->getRelation('OrderFields');
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
			$product->field_cache = $cache[$product->product_id];
			$product->saveIfChanged();
		}
	}
}