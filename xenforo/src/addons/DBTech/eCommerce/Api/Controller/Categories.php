<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\ParameterBag;

/**
 * @api-group Categories
 */
class Categories extends AbstractLoggableEndpoint
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
	 * @api-desc Gets the category tree.
	 *
	 * @api-out array $tree_map A mapping that connects category parent IDs to a list of their child category IDs
	 * @api-out Category[] $categories List of all categories
	 */
	public function actionGet(): \XF\Api\Mvc\Reply\ApiResult
	{
		$repo = $this->getCategoryRepo();
		return $this->getCategoryTreePlugin()->actionGet($repo);
	}
	
	/**
	 * @api-desc Gets a flattened category tree. Traversing this will return a list of categories in the expected order.
	 *
	 * @api-out array $categories_flat An array. Each entry contains keys of "category" and "depth"
	 */
	public function actionGetFlattened(): \XF\Api\Mvc\Reply\ApiResult
	{
		$repo = $this->getCategoryRepo();
		return $this->getCategoryTreePlugin()->actionGetFlattened($repo);
	}

	/**
	 * @api-desc Creates a new category
	 *
	 * @api-see \DBTech\eCommerce\Api\ControllerPlugin\Category::setupCategorySave()
	 * @api-in str $category[title] <req>
	 * @api-in int $category[parent_category_id] <req>
	 *
	 * @api-out Category $category Information about the created category
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
		$this->assertRequiredApiInput(['title', 'parent_category_id']);
		
		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->em()->create('DBTech\eCommerce:Category');
		
		/** @var \DBTech\eCommerce\Api\ControllerPlugin\Category $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Api:Category');
		
		$form = $plugin->setupCategorySave($category);
		$form->run();
		
		return $this->apiSuccess([
			'category' => $category->toApiResult()
		]);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Category
	 */
	protected function getCategoryRepo(): \DBTech\eCommerce\Repository\Category
	{
		return $this->repository('DBTech\eCommerce:Category');
	}

	/**
	 * @return \XF\ControllerPlugin\AbstractPlugin
	 */
	protected function getCategoryTreePlugin(): \XF\ControllerPlugin\AbstractPlugin
	{
		return $this->plugin('XF:Api:CategoryTree');
	}
}