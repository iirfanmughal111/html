<?php

namespace DBTech\eCommerce\Search\Data;

use XF\Mvc\Entity\Entity;
use XF\Search\Data\AbstractData;
use XF\Search\IndexRecord;
use XF\Search\MetadataStructure;
use XF\Search\Query\MetadataConstraint;

/**
 * Class Product
 * @package DBTech\eCommerce\Search\Data
 */
class Product extends AbstractData
{
	/**
	 * @param bool $forView
	 *
	 * @return array
	 */
	public function getEntityWith($forView = false): array
	{
		$get = ['Category'];
		if ($forView)
		{
			$get[] = 'User';
			
			$visitor = \XF::visitor();
			$get[] = 'Permissions|' . $visitor->permission_combination_id;
			$get[] = 'Category.Permissions|' . $visitor->permission_combination_id;
		}
		
		return $get;
	}
	
	/**
	 * @param Entity $entity
	 * @return IndexRecord
	 */
	public function getIndexData(Entity $entity): IndexRecord
	{
		/** @var \DBTech\eCommerce\Entity\Product $entity */

		$index = IndexRecord::create('dbtech_ecommerce_product', $entity->product_id, [
			'title' => $entity->title,
			'message' => $entity->description_full,
			'date' => $entity->creation_date,
			'user_id' => $entity->user_id,
			'discussion_id' => $entity->product_id,
			'metadata' => $this->getMetaData($entity)
		]);

		if (!$entity->isVisible())
		{
			$index->setHidden();
		}
		
		if ($entity->tags)
		{
			$index->indexTags($entity->tags);
		}

		return $index;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $entity
	 *
	 * @return array
	 */
	protected function getMetaData(\DBTech\eCommerce\Entity\Product $entity): array
	{
		$metadata = [
			'prodcat' => $entity->product_category_id,
			'product' => $entity->product_id
		];
		if ($entity->prefix_id)
		{
			$metadata['prodprefix'] = $entity->prefix_id;
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
	 * @return mixed|null
	 */
	public function getResultDate(Entity $entity)
	{
		return $entity->creation_date;
	}

	/**
	 * @param Entity $entity
	 * @param array $options
	 * @return array
	 */
	public function getTemplateData(Entity $entity, array $options = []): array
	{
		return [
			'product' => $entity,
			'options' => $options
		];
	}
	
	/**
	 * @param Entity $entity
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canUseInlineModeration(Entity $entity, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\Entity\Product $entity */
		return $entity->canUseInlineModeration($error);
	}
	
	/**
	 * @return array
	 */
	public function getSearchableContentTypes(): array
	{
		return ['dbtech_ecommerce_product', 'dbtech_ecommerce_download'];
	}
	
	/**
	 * @return array|null
	 */
	public function getSearchFormTab(): ?array
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!method_exists($visitor, 'canViewDbtechEcommerceProducts') || !$visitor->canViewDbtechEcommerceProducts())
		{
			return null;
		}
		
		return [
			'title' => \XF::phrase('dbtech_ecommerce_search_products'),
			'order' => 300
		];
	}
	
	/**
	 * @return null|string
	 */
	public function getSectionContext(): ?string
	{
		return 'dbtech-ecommerce';
	}
	
	/**
	 * @return array
	 */
	public function getSearchFormData(): array
	{
		$prefixListData = $this->getPrefixListData();
		
		return [
			'prefixGroups' => $prefixListData['prefixGroups'],
			'prefixesGrouped' => $prefixListData['prefixesGrouped'],
			
			'categoryTree' => $this->getSearchableCategoryTree()
		];
	}
	
	/**
	 * @return \XF\Tree
	 */
	protected function getSearchableCategoryTree(): \XF\Tree
	{
		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = \XF::repository('DBTech\eCommerce:Category');
		return $categoryRepo->createCategoryTree($categoryRepo->getViewableCategories());
	}
	
	/**
	 * @return array
	 */
	protected function getPrefixListData(): array
	{
		/** @var \DBTech\eCommerce\Repository\ProductPrefix $prefixRepo */
		$prefixRepo = \XF::repository('DBTech\eCommerce:ProductPrefix');
		return $prefixRepo->getPrefixListData();
	}
	
	/**
	 * @param \XF\Search\Query\Query $query
	 * @param \XF\Http\Request $request
	 * @param array $urlConstraints
	 */
	public function applyTypeConstraintsFromInput(\XF\Search\Query\Query $query, \XF\Http\Request $request, array &$urlConstraints)
	{
		$prefixes = $request->filter('c.prefixes', 'array-uint');
		$prefixes = array_unique($prefixes);
		if ($prefixes && reset($prefixes))
		{
			$query->withMetadata('prodprefix', $prefixes);
		}
		else
		{
			unset($urlConstraints['prefixes']);
		}
		
		$categoryIds = $request->filter('c.categories', 'array-uint');
		$categoryIds = array_unique($categoryIds);
		if ($categoryIds && reset($categoryIds))
		{
			if ($request->filter('c.child_categories', 'bool'))
			{
				$categoryTree = $this->getSearchableCategoryTree();
				
				$searchCategoryIds = array_fill_keys($categoryIds, true);
				$categoryTree->traverse(function ($id, $category) use (&$searchCategoryIds)
				{
					if (isset($searchCategoryIds[$id]) || isset($searchCategoryIds[$category->parent_category_id]))
					{
						$searchCategoryIds[$id] = true;
					}
				});
				
				$categoryIds = array_unique(array_keys($searchCategoryIds));
			}
			else
			{
				unset($urlConstraints['child_categories']);
			}
			
			$query->withMetadata('prodcat', $categoryIds);
		}
		else
		{
			unset($urlConstraints['categories'], $urlConstraints['child_categories']);
		}
		
		$includeDownloads = $request->filter('c.include_downloads', 'bool');
		if (!$includeDownloads)
		{
			$query->inType('dbtech_ecommerce_product');
			unset($urlConstraints['include_downloads']);
		}
	}
	
	/**
	 * @param \XF\Search\Query\Query $query
	 * @param bool $isOnlyType
	 *
	 * @return array|MetadataConstraint[]
	 */
	public function getTypePermissionConstraints(\XF\Search\Query\Query $query, $isOnlyType): array
	{
		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = \XF::repository('DBTech\eCommerce:Category');
		
		$with = ['Permissions|' . \XF::visitor()->permission_combination_id];
		$categories = $categoryRepo->findCategoryList(null, $with)->fetch();
		
		$skip = [];
		foreach ($categories AS $category)
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			if (!$category->canView())
			{
				$skip[] = $category->category_id;
			}
		}
		
		if ($skip)
		{
			return [
				new MetadataConstraint('prodcat', $skip, MetadataConstraint::MATCH_NONE)
			];
		}
		
		return [];
	}
	
	/**
	 * @return null|string
	 */
	public function getGroupByType(): ?string
	{
		return 'dbtech_ecommerce_product';
	}
}