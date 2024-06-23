<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;

/**
 * @api-group Categories
 */
class Category extends AbstractLoggableEndpoint
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\PrintableException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		parent::preDispatchController($action, $params);
	
		$this->assertApiScopeByRequestMethod('dbtech_ecommerce_category', ['delete' => 'delete']);
	}
	
	/**
	 * @api-desc Gets information about the specified category
	 *
	 * @api-out Category $category
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGet(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$category = $this->assertViewableCategory($params->category_id);

		if ($this->filter('with_products', 'bool'))
		{
			$this->assertApiScope('dbtech_ecommerce_product:read');
			$productData = $this->getProductsInCategoryPaginated($category, $this->filterPage());
		}
		else
		{
			$productData = [];
		}

		$result = [
			'category' => $category->toApiResult(Entity::VERBOSITY_VERBOSE)
		];
		$result += $productData;

		return $this->apiResult($result);
	}

	/**
	 * @api-desc Gets a page of products from the specified category.
	 *
	 * @api-see self::getProductsInCategoryPaginated()
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGetProducts(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$this->assertApiScope('dbtech_ecommerce_product:read');

		$category = $this->assertViewableCategory($params->category_id);

		$productData = $this->getProductsInCategoryPaginated($category, $this->filterPage());

		return $this->apiResult($productData);
	}

	/**
	 * @api-in int $page
	 *
	 * @api-out Product[] $products Products on this page
	 * @api-out pagination $pagination Pagination information
	 *
	 * @param \DBTech\eCommerce\Entity\Category $category
	 * @param int $page
	 * @param null $perPage
	 *
	 * @return array
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function getProductsInCategoryPaginated(\DBTech\eCommerce\Entity\Category $category, $page = 1, $perPage = null): array
	{
		$perPage = intval($perPage);
		if ($perPage <= 0)
		{
			$perPage = $this->options()->dbtechEcommerceProductsPerPage;
		}

		$finder = $this->setupProductFinder($category, $filters, $sort);
		$total = $finder->total();

		$this->assertValidApiPage($page, $perPage, $total);

		/** @var \DBTech\eCommerce\Entity\Product[]|\XF\Mvc\Entity\AbstractCollection $products */
		$products = $finder->fetch();
		if (\XF::isApiCheckingPermissions())
		{
			$products = $products->filterViewable();
		}

		$productResults = $products->toApiResults(Entity::VERBOSITY_VERBOSE);
		$this->adjustProductListApiResults($category, $productResults);

		return [
			'products' => $productResults,
			'pagination' => $this->getPaginationData($productResults, $page, $perPage, $total)
		];
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 * @param array $filters List of filters that have been applied from input
	 * @param array|null $sort If array, sort that has been applied from input
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 */
	protected function setupProductFinder(\DBTech\eCommerce\Entity\Category $category, &$filters = [], &$sort = null): \DBTech\eCommerce\Finder\Product
	{
		$finder = $this->repository('DBTech\eCommerce:Product')->findProductsForApi($category);

		/** @var \DBTech\eCommerce\Api\ControllerPlugin\Product $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Api:Product');

		$filters = $plugin->applyProductListFilters($finder);
		$sort = $plugin->applyProductListSort($finder);

		return $finder;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 * @param \XF\Api\Result\EntityResultInterface $result
	 */
	protected function adjustProductListApiResults(\DBTech\eCommerce\Entity\Category $category, \XF\Api\Result\EntityResultInterface $result)
	{
		$result->skipRelation('Category');
	}

	/**
	 * @api-desc Updates the specified category
	 *
	 * @api-see DBTech\eCommerce\Api\ControllerPlugin\Category::setupCategorySave()
	 *
	 * @api-out Category $category The updated category information
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 * @throws \XF\PrintableException
	 */
	public function actionPost(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$this->assertAdminPermission('dbtechEcomCategory');

		$category = $this->assertViewableCategory($params->category_id);

		/** @var \DBTech\eCommerce\Api\ControllerPlugin\Category $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Api:Category');

		$form = $plugin->setupCategorySave($category);
		$form->run();

		return $this->apiSuccess([
			'category' => $category->toApiResult()
		]);
	}

	/**
	 * @api-desc Deletes the specified category.
	 *
	 * @api-in bool $delete_children If true, child categories will be deleted. Otherwise, they will be connected to this category's parent.
	 *
	 * @api-out bool $success
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$this->assertAdminPermission('resourceManager');

		$category = $this->assertViewableCategory($params->category_id);

		/** @var \XF\Api\ControllerPlugin\CategoryTree $plugin */
		$plugin = $this->plugin('XF:Api:CategoryTree');

		return $plugin->actionDelete($category);
	}

	/**
	 * @param int $id
	 * @param string|array $with
	 *
	 * @return \DBTech\eCommerce\Entity\Category|\XF\Mvc\Entity\Entity
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableCategory($id, $with = 'api')
	{
		return $this->assertViewableApiRecord('DBTech\eCommerce:Category', $id, $with);
	}
}