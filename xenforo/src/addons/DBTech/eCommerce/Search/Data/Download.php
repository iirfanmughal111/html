<?php

namespace DBTech\eCommerce\Search\Data;

use XF\Mvc\Entity\Entity;
use XF\Search\Data\AbstractData;
use XF\Search\IndexRecord;
use XF\Search\MetadataStructure;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\Search\Data
 */
class Download extends AbstractData
{
	/**
	 * @param bool $forView
	 *
	 * @return array
	 */
	public function getEntityWith($forView = false): array
	{
		$get = ['Product', 'Product.Category'];
		if ($forView)
		{
			$get[] = 'Product.User';

			$visitor = \XF::visitor();
			$get[] = 'Product.Permissions|' . $visitor->permission_combination_id;
			$get[] = 'Product.Category.Permissions|' . $visitor->permission_combination_id;
		}

		return $get;
	}
	
	/**
	 * @param Entity $entity
	 *
	 * @return null|IndexRecord
	 */
	public function getIndexData(Entity $entity): ?IndexRecord
	{
		/** @var \DBTech\eCommerce\Entity\Download $entity */

		if (!$entity->Product || !$entity->Product->Category)
		{
			return null;
		}

		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $entity->Product;

		$index = IndexRecord::create('dbtech_ecommerce_download', $entity->download_id, [
			'title' => $entity->title,
			'message' => $entity->change_log,
			'date' => $entity->release_date,
			'user_id' => $product->user_id,
			'discussion_id' => $entity->product_id,
			'metadata' => $this->getMetaData($entity)
		]);

		if (!$entity->isVisible())
		{
			$index->setHidden();
		}

		return $index;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Download $entity
	 *
	 * @return array
	 */
	protected function getMetaData(\DBTech\eCommerce\Entity\Download $entity): array
	{
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $entity->Product;

		$metadata = [
			'prodcat' => $product->product_category_id,
			'product' => $product->product_id
		];
		if ($product->prefix_id)
		{
			$metadata['prodprefix'] = $product->prefix_id;
		}
		
		return $metadata;
	}
	
	/**
	 * @param MetadataStructure $structure
	 */
	public function setupMetadataStructure(MetadataStructure $structure)
	{
		$structure->addField('prodcat', MetadataStructure::INT);
		$structure->addField('product', MetadataStructure::INT);
		$structure->addField('prodprefix', MetadataStructure::INT);
	}
	
	/**
	 * @param Entity $entity
	 *
	 * @return mixed|null
	 */
	public function getResultDate(Entity $entity)
	{
		return $entity->release_date;
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 *
	 * @return array
	 */
	public function getTemplateData(Entity $entity, array $options = []): array
	{
		return [
			'download' => $entity,
			'product' => $entity->Product,
			'options' => $options
		];
	}
}