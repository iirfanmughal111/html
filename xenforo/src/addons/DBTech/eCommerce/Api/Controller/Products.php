<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;

/**
 * @api-group Products
 */
class Products extends AbstractLoggableEndpoint
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
		
		$this->assertApiScopeByRequestMethod('dbtech_ecommerce_product');
	}
	
	/**
	 * @api-desc Gets the list of products.
	 *
	 * @api-in int $page
	 * @api-in bool $all Whether to fetch all products on a single page.
	 * @api-in array $platforms Only fetch products matching these platforms.
	 *
	 * @api-out Product[] $products Products on this page
	 * @api-out pagination $pagination Pagination information
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGet(): \XF\Api\Mvc\Reply\ApiResult
	{
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceProductsPerPage;

		$finder = $this->setupProductFinder();
		
		if (!$this->filter('all', 'bool'))
		{
			$finder->limitByPage($page, $perPage);
		}
		
		$platformFilters = $this->filter('platforms', 'array-str');
		if ($platformFilters)
		{
			$filterAssociations = $this->finder('DBTech\eCommerce:ProductFilterMap')
				->where('filter_id', $platformFilters)
			;
			
			$finder->where('product_id', $filterAssociations->fetch()
				->pluckNamed('product_id', 'product_id'));
		}
		
		$total = $finder->total();
		
		if (!$this->filter('all', 'bool'))
		{
			$this->assertValidApiPage($page, $perPage, $total);
		}

		$products = $finder->fetch();

		if (\XF::isApiCheckingPermissions())
		{
			$products = $products->filterViewable();
		}

		return $this->apiResult([
			'products' => $products->toApiResults(Entity::VERBOSITY_NORMAL, [
				'with_latest_version' => true
			]),
			'pagination' => $this->getPaginationData($products, $page, $perPage, $total)
		]);
	}
	
	/**
	 * @api-desc Gets the list of purchased products.
	 *
	 * @api-in array $category_ids Only fetch products within these categories.
	 * @api-in array $platforms Only fetch products matching these platforms.
	 *
	 * @api-out Product[] $products All purchased products
	 *
	 * @api-error you_have_not_purchased_any_licenses_yet Triggered if the user has not purchased any licenses.
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\Error
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGetPurchased()
	{
		$this->assertApiScopeByRequestMethod('dbtech_ecommerce_license');
		$this->assertRegisteredUser();
		
		$finder = $this->setupProductFinder();
		$finder->with('fullCategory');
		
		$categoryIds = $this->filter('category_ids', 'array-uint');
		if ($categoryIds)
		{
			// if we have viewable category IDs, we likely have those permissions
			$finder->where('product_category_id', $categoryIds);
		}
		else
		{
			$finder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		$platformFilters = $this->filter('platforms', 'array-str');
		if ($platformFilters)
		{
			$filterAssociations = $this->finder('DBTech\eCommerce:ProductFilterMap')
				->where('filter_id', $platformFilters)
			;
			
			$finder->where('product_id', $filterAssociations->fetch()
				->pluckNamed('product_id', 'product_id'));
		}
		
		/** @var \DBTech\eCommerce\Finder\License $licenseFinder */
		$licenseFinder = $this->finder('DBTech\eCommerce:License');
		$licenseFinder->where('user_id', \XF::visitor()->user_id)
			->where('license_state', 'visible')
			->setDefaultOrder('purchase_date', 'desc')
		;
		
		/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
		$licenses = $licenseFinder->fetch();
		$licenses = $this->repository('DBTech\eCommerce:License')->filterLicensesForApiResponse($licenses);
		
		$finder->where('product_id', $licenses->pluckNamed('product_id', 'product_id'));
		
		$products = $finder->fetch();
		
		if (\XF::isApiCheckingPermissions())
		{
			$products = $products->filterViewable();
		}
		
		if (!$products->count())
		{
			return $this->apiError(
				\XF::phrase('api_error.dbtech_ecommerce_you_have_not_purchased_any_licenses_yet'),
				'you_have_not_purchased_any_licenses_yet',
				[],
				402
			);
		}
		
		return $this->apiResult([
			'products' => $products->toApiResults(Entity::VERBOSITY_NORMAL, [
				'with_latest_version' => true
			])
		]);
	}

	/**
	 * @param array $filters List of filters that have been applied from input
	 * @param array|null $sort If array, sort that has been applied from input
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 */
	protected function setupProductFinder(&$filters = [], &$sort = null): \DBTech\eCommerce\Finder\Product
	{
		$repo = $this->repository('DBTech\eCommerce:Product');
		$finder = $repo->findProductsForApi();

		/** @var \DBTech\eCommerce\Api\ControllerPlugin\Product $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Api:Product');

		$filters = $plugin->applyProductListFilters($finder);
		$sort = $plugin->applyProductListSort($finder);

		return $finder;
	}
}