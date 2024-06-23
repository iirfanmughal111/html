<?php

namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

/**
 * Class Overview
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class Overview extends AbstractPlugin
{
	/**
	 * @param \DBTech\eCommerce\Entity\Category|null $category
	 *
	 * @return array
	 */
	public function getCategoryListData(?\DBTech\eCommerce\Entity\Category $category = null): array
	{
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->getViewableCategories();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

		return [
			'categories' => $categories,
			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
	}

	/**
	 * @param array $sourceCategoryIds
	 * @param \DBTech\eCommerce\Entity\Category|null $category
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	public function getCoreListData(array $sourceCategoryIds, ?\DBTech\eCommerce\Entity\Category $category = null): array
	{
		$productRepo = $this->getProductRepo();

		$allowOwnPending = is_callable([$this->controller, 'hasContentPendingApproval'])
			? $this->controller->hasContentPendingApproval()
			: true;

		$productFinder = $productRepo->findProductsForOverviewList($sourceCategoryIds, [
			'allowOwnPending' => $allowOwnPending
		]);

		$filters = $this->getProductFilterInput();
		$this->applyProductFilters($productFinder, $filters);

		$totalProducts = $productFinder->total();

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceProductsPerPage;

		$productFinder->limitByPage($page, $perPage);
		$products = $productFinder->fetch()->filterViewable();
		
		if (!empty($filters['owner_id']))
		{
			$ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
		}
		else
		{
			$ownerFilter = null;
		}
		
		if (!empty($filters['product_fields']))
		{
			/** @var \DBTech\eCommerce\Entity\Product $tmp */
			$tmp = $this->em->create('DBTech\eCommerce:Product');
			$set = $tmp->getProductFields();
			
			$productFieldFilter = [];
			foreach ($filters['product_fields'] as $key => $value)
			{
				$set->set($key, $value, 'admin', true);
				
				$definition = $set->getDefinition($key);
				
				$productFieldFilter[$key] = [
					'title' => $definition->title,
					'definition' => $definition,
					'value' => $tmp->product_fields[$key]
				];
			}
		}
		else
		{
			$productFieldFilter = null;
		}
		
		if (!empty($filters['platform']))
		{
			$applicableCategories = $this->getCategoryRepo()->getViewableCategories($category);
			$platformFilter = null;
			foreach ($applicableCategories as $applicableCategory)
			{
				if (isset($applicableCategory['product_filters'][$filters['platform']]))
				{
					$platformFilter = $applicableCategory['product_filters'][$filters['platform']];
					break;
				}
			}
			
			if ($category && $platformFilter === null)
			{
				foreach ($category['product_filters'] as $platformId => $platform)
				{
					if (isset($category['product_filters'][$filters['platform']]))
					{
						$platformFilter = $category['product_filters'][$filters['platform']];
						break;
					}
				}
			}
		}
		else
		{
			$platformFilter = null;
		}

		$canInlineMod = false;
		foreach ($products AS $product)
		{
			/** @var \DBTech\eCommerce\Entity\Product $product */
			if ($product->canUseInlineModeration())
			{
				$canInlineMod = true;
				break;
			}
		}

		return [
			'products' => $products,
			'filters' => $filters,
			'ownerFilter' => $ownerFilter,
			'platformFilter' => $platformFilter,
			'productFieldFilter' => $productFieldFilter,
			'canInlineMod' => $canInlineMod,

			'total' => $totalProducts,
			'page' => $page,
			'perPage' => $perPage
		];
	}
	
	/**
	 * @param \DBTech\eCommerce\Finder\Product $productFinder
	 * @param array $filters
	 */
	public function applyProductFilters(\DBTech\eCommerce\Finder\Product $productFinder, array $filters)
	{
		if (!empty($filters['prefix_id']))
		{
			$productFinder->where('prefix_id', (int)$filters['prefix_id']);
		}

		if (!empty($filters['type']))
		{
			switch ($filters['type'])
			{
				case 'free':
					$productFinder->where('is_paid', 0);
					break;
				
				case 'paid':
					$productFinder->where('is_paid', 1);
					break;
				
				case 'on_sale':
					$productFinder->where('Sale.sale_type', '!=', '');
					break;
			}
		}
		
		if (!empty($filters['owner_id']))
		{
			$productFinder->where('user_id', (int)$filters['owner_id']);
		}
		
		if (!empty($filters['product_fields']))
		{
			foreach ($filters['product_fields'] as $fieldId => $value)
			{
				$fieldAssociations = $this->finder('DBTech\eCommerce:ProductFieldValue');
				if (\is_array($value))
				{
					$idColumnName = $fieldAssociations->columnSqlName('field_id');
					$valueColumnName = $fieldAssociations->columnSqlName('field_value');

					$parts = [];
					foreach ($value AS $part)
					{
						$parts[] = $valueColumnName . ' LIKE \'%s:' . \strlen($part) . ':"'. \XF::db()->escapeString($part) . '"%\'';
					}
					{
						$fieldAssociations->whereSql('(' . $idColumnName . ' = ' . $fieldAssociations->quote($fieldId) . ' AND (' . implode(' OR ', $parts) . '))');
//						$fieldAssociations->where([
//							'field_id' => $fieldId,
//							'field_value' => $fieldAssociations->expression(implode(' OR ', $parts))
//						]);
					}
				}
				else
				{
					$fieldAssociations->where([
						'field_id' => $fieldId,
						'field_value' => $value
					]);
				}

				$productIds = $fieldAssociations
					->fetch()
					->pluckNamed('product_id', 'product_id')
				;
				if ($productIds)
				{
					$productFinder->where(
						'product_id',
						$productIds
					);
				}
				else
				{
					$productFinder->whereImpossible();
				}
			}
		}
		
		if (!empty($filters['platform']))
		{
			$filterAssociations = $this->finder('DBTech\eCommerce:ProductFilterMap')
				->where('filter_id', $filters['platform']);
			
			$productFinder->where('product_id', $filterAssociations->fetch()->pluckNamed('product_id', 'product_id'));
		}

		$sorts = $this->getAvailableProductSorts();

		if (!empty($filters['order']) && isset($sorts[$filters['order']]))
		{
			$productFinder->order('is_featured', 'desc');
			if ($sorts[$filters['order']] == '__random__')
			{
				$productFinder->order($productFinder->expression('RAND()'));
			}
			else
			{
				$productFinder->order($sorts[$filters['order']], $filters['direction']);
			}
		}
	}
	
	/**
	 * @return array
	 */
	public function getProductFilterInput(): array
	{
		$filters = [];

		$input = $this->filter([
			'prefix_id' => 'uint',
			'type' => 'str',
			'owner' => 'str',
			'owner_id' => 'uint',
			'product_fields' => 'array',
			'platform' => 'str',
			'order' => 'str',
			'direction' => 'str'
		]);

		if ($input['prefix_id'])
		{
			$filters['prefix_id'] = $input['prefix_id'];
		}

		if ($input['type'] && in_array($input['type'], ['free', 'paid', 'on_sale']))
		{
			$filters['type'] = $input['type'];
		}

		if ($input['owner_id'])
		{
			$filters['owner_id'] = $input['owner_id'];
		}
		elseif ($input['owner'])
		{
			$user = $this->em()->findOne('XF:User', ['username' => $input['owner']]);
			if ($user)
			{
				$filters['owner_id'] = $user->user_id;
			}
		}
		
		if ($input['platform'])
		{
			$filters['platform'] = $input['platform'];
		}
		
		if ($input['product_fields'])
		{
			$filters['product_fields'] = $input['product_fields'];
		}

		$sorts = $this->getAvailableProductSorts();

		if ($input['order'] && isset($sorts[$input['order']]))
		{
			if (!in_array($input['direction'], ['asc', 'desc']))
			{
				$input['direction'] = 'desc';
			}

			$defaultOrder = $this->options()->dbtechEcommerceListDefaultOrder ?: 'last_update';
			$defaultDir = $defaultOrder == 'title' ? 'asc' : 'desc';

			if ($input['order'] != $defaultOrder || $input['direction'] != $defaultDir)
			{
				$filters['order'] = $input['order'];
				$filters['direction'] = $input['direction'];
			}
		}

		return $filters;
	}

	/**
	 * @return string[]
	 */
	public function getAvailableProductSorts(): array
	{
		// maps [name of sort] => field in/relative to Product entity
		return [
			'last_update' => 'last_update',
			'creation_date' => 'creation_date',
			'rating_weighted' => 'rating_weighted',
			'download_count' => 'download_count',
			'title' => 'title',
			'random' => '__random__'
		];
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Category|null $category
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \Exception
	 */
	public function actionFilters(?\DBTech\eCommerce\Entity\Category $category = null)
	{
		$filters = $this->getProductFilterInput();

		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink(
				$category ? 'dbtech-ecommerce/categories' : 'dbtech-ecommerce',
				$category,
				$filters
			));
		}

		if (!empty($filters['owner_id']))
		{
			$ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
		}
		else
		{
			$ownerFilter = null;
		}
		
		$applicableCategories = $this->getCategoryRepo()->getViewableCategories($category);
		$applicableCategoryIds = $applicableCategories->keys();
		if ($category)
		{
			$applicableCategoryIds[] = $category->category_id;
		}

		$availablePrefixIds = $this->repository('DBTech\eCommerce:CategoryPrefix')->getPrefixIdsInContent($applicableCategoryIds);
		$prefixes = $this->repository('DBTech\eCommerce:ProductPrefix')->findPrefixesForList()
			->where('prefix_id', $availablePrefixIds)
			->fetch();
		
		/** @var \DBTech\eCommerce\Entity\Product $tmp */
		$tmp = $this->em->create('DBTech\eCommerce:Product');
		$set = $tmp->getProductFields();
		
		if (!empty($filters['product_fields']))
		{
			foreach ($filters['product_fields'] as $key => $value)
			{
				$set->set($key, $value, 'admin', true);
			}
		}
		
		$onlyInclude = [];
		
		/** @var \XF\CustomField\Definition $fieldDefinition */
		foreach ($set->getDefinitionSet()->getFieldDefinitions() as $fieldDefinition)
		{
			if ($fieldDefinition->filterable)
			{
				$onlyInclude[] = $fieldDefinition->field_id;
			}
		}

		$defaultOrder = $this->options()->dbtechEcommerceListDefaultOrder ?: 'last_update';
		$defaultDir = $defaultOrder == 'title' ? 'asc' : 'desc';

		if (empty($filters['order']))
		{
			$filters['order'] = $defaultOrder;
		}
		if (empty($filters['direction']))
		{
			$filters['direction'] = $defaultDir;
		}
		
		$platformFilter = [];
		foreach ($applicableCategories as $applicableCategory)
		{
			foreach ($applicableCategory['product_filters'] as $platformId => $platform)
			{
				$platformFilter[$platformId] = $platform;
			}
		}
		
		if ($category)
		{
			foreach ($category['product_filters'] as $platformId => $platform)
			{
				$platformFilter[$platformId] = $platform;
			}
		}
		asort($platformFilter);
		
		$viewParams = [
			'category' => $category,
			'prefixesGrouped' => $prefixes->groupBy('prefix_group_id'),
			'filters' => $filters,
			'ownerFilter' => $ownerFilter,
			'platformFilter' => $platformFilter,
			'set' => $set,
			'onlyInclude' => $onlyInclude,
		];
		return $this->view('DBTech\eCommerce:Filters', 'dbtech_ecommerce_filters', $viewParams);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Category|\XF\Mvc\Entity\Repository
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('DBTech\eCommerce:Category');
	}
}