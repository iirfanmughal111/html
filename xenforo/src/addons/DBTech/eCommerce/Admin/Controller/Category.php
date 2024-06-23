<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class Category
 * @package DBTech\eCommerce\Admin\Controller
 */
class Category extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomCategory');
	}
	
	/**
	 * @return \XF\ControllerPlugin\AbstractPlugin|\DBTech\eCommerce\ControllerPlugin\CategoryTree
	 */
	protected function getCategoryTreePlugin()
	{
		return $this->plugin('DBTech\eCommerce:CategoryTree');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		return $this->getCategoryTreePlugin()->actionList([
			'permissionContentType' => 'dbtech_ecommerce_category'
		]);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 * @return \XF\Mvc\Reply\View
	 */
	protected function categoryAddEdit(\DBTech\eCommerce\Entity\Category $category): \XF\Mvc\Reply\AbstractReply
	{
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->findCategoryList()->fetch();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		
		if ($category->ThreadForum)
		{
			$threadPrefixes = $category->ThreadForum->getPrefixesGrouped();
		}
		else
		{
			$threadPrefixes = [];
		}
		
		/** @var \DBTech\eCommerce\Repository\ProductPrefix $prefixRepo */
		$prefixRepo = $this->repository('DBTech\eCommerce:ProductPrefix');
		$availablePrefixes = $prefixRepo->findPrefixesForList()->fetch();
		$availablePrefixes = $availablePrefixes->pluckNamed('title', 'prefix_id');
		
		/** @var \DBTech\eCommerce\Repository\ProductField $fieldRepo */
		$fieldRepo = $this->repository('DBTech\eCommerce:ProductField');
		$availableFields = $fieldRepo->findFieldsForList()->fetch();
		
		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = $this->repository('XF:Node');
		
		$viewParams = [
			'forumOptions' => $nodeRepo->getNodeOptionsData(false, 'Forum'),
			'threadPrefixes' => $threadPrefixes,
			'category' => $category,
			'categoryTree' => $categoryTree,
			
			'availablePrefixes' => $availablePrefixes,
			'availableFields' => $availableFields
		];
		return $this->view('DBTech\eCommerce:Category\Edit', 'dbtech_ecommerce_category_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->assertCategoryExists($params['category_id']);
		return $this->categoryAddEdit($category);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		$copyCategoryId = $this->filter('source_category_id', 'uint');
		if ($copyCategoryId)
		{
			$copyCategory = $this->assertCategoryExists($copyCategoryId)->toArray(false);
			foreach ([
				'category_id',
			] as $key)
			{
				unset($copyCategory[$key]);
			}
			
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category = $this->em()->create('DBTech\eCommerce:Category');
			$category->bulkSet($copyCategory);
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category = $this->em()->create('DBTech\eCommerce:Category');
			$category->parent_category_id = $this->filter('parent_category_id', 'uint');
		}
		
		return $this->categoryAddEdit($category);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 * @return FormAction
	 */
	protected function categorySaveProcess(\DBTech\eCommerce\Entity\Category $category): FormAction
	{
		$form = $this->formAction();

		$input = $this->filter([
			'title' => 'str',
			'description' => 'str',
			'display_order' => 'uint',
			'parent_category_id' => 'uint',
			'always_moderate_create' => 'bool',
			'always_moderate_update' => 'bool',
			'thread_node_id' => 'uint',
			'thread_prefix_id' => 'uint',
			'require_prefix' => 'bool',
			'product_update_notify' => 'str',
		]);
		
		$productFilters = $this->filter('product_filter', 'array-str');
		$productFiltersText = $this->filter('product_filter_text', 'array-str');
		$productFiltersCombined = [];
		
		foreach ($productFilters AS $key => $choice)
		{
			if (isset($productFiltersText[$key]) && $productFiltersText[$key] !== '')
			{
				$productFiltersCombined[$choice] = $productFiltersText[$key];
			}
		}
		$input['product_filters'] = $productFiltersCombined;
		
		$form->basicEntitySave($category, $input);
		
		$prefixIds = $this->filter('available_prefixes', 'array-uint');
		$form->complete(function () use ($category, $prefixIds)
		{
			/** @var \DBTech\eCommerce\Repository\CategoryPrefix $repo */
			$repo = $this->repository('DBTech\eCommerce:CategoryPrefix');
			$repo->updateContentAssociations($category->category_id, $prefixIds);
		});
		
		$fieldIds = $this->filter('available_fields', 'array-str');
		$form->complete(function () use ($category, $fieldIds)
		{
			/** @var \DBTech\eCommerce\Repository\CategoryField $repo */
			$repo = $this->repository('DBTech\eCommerce:CategoryField');
			$repo->updateContentAssociations($category->category_id, $fieldIds);
		});

		return $form;
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		$this->assertPostOnly();

		if ($params['category_id'])
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category = $this->assertCategoryExists($params['category_id']);
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category = $this->em()->create('DBTech\eCommerce:Category');
		}

		$this->categorySaveProcess($category)->run();

		return $this->redirect($this->buildLink('dbtech-ecommerce/categories') . $this->buildLinkHash($category->category_id));
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionDelete(ParameterBag $params)
	{
		return $this->getCategoryTreePlugin()->actionDelete($params);
	}
	
	/**
	 * @return mixed
	 */
	public function actionSort()
	{
		return $this->getCategoryTreePlugin()->actionSort();
	}
	
	/**
	 * @return \DBTech\eCommerce\ControllerPlugin\CategoryPermission
	 */
	protected function getCategoryPermissionPlugin(): \DBTech\eCommerce\ControllerPlugin\CategoryPermission
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\CategoryPermission $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:CategoryPermission');
		$plugin->setFormatters('DBTech\eCommerce:Category\Permission%s', 'dbtech_ecommerce_category_permission_%s');
		$plugin->setRoutePrefix('dbtech-ecommerce/categories/permissions');
		
		return $plugin;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionPermissions(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		return $this->getCategoryPermissionPlugin()->actionList($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionPermissionsEdit(ParameterBag $params)
	{
		return $this->getCategoryPermissionPlugin()->actionEdit($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 */
	public function actionPermissionsSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		return $this->getCategoryPermissionPlugin()->actionSave($params);
	}

	/**
	 * @param int|null $id
	 * @param null $with
	 * @param string|null $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Category
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCategoryExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Category
	{
		return $this->assertRecordExists('DBTech\eCommerce:Category', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Category|\XF\Mvc\Entity\Repository
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('DBTech\eCommerce:Category');
	}
}