<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Pub\Controller;

/**
 * Class Watched
 *
 * @package DBTech\eCommerce\XF\Pub\Controller
 */
class Watched extends XFCP_Watched
{
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 */
	public function actionEcommerceProducts()
	{
		$this->setSectionContext('dbtechEcommerce');

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceProductsPerPage;

		/** @var \DBTech\eCommerce\Repository\Product $productRepo */
		$productRepo = $this->repository('DBTech\eCommerce:Product');
		$finder = $productRepo->findProductsForWatchedList();

		$total = $finder->total();
		$products = $finder->limitByPage($page, $perPage)->fetch();

		$viewParams = [
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			'products' => $products->filterViewable()
		];
		return $this->view('DBTech\eCommerce:Watched\Products', 'dbtech_ecommerce_watched_products', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 */
	public function actionEcommerceProductsManage()
	{
		$this->setSectionContext('dbtechEcommerce');

		if (!$state = $this->filter('state', 'str'))
		{
			return $this->redirect($this->buildLink('watched/ecommerce-products'));
		}

		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Repository\ProductWatch $productWatchRepo */
			$productWatchRepo = $this->repository('DBTech\eCommerce:ProductWatch');

			if ($action = $this->getEcommerceProductWatchActionConfig($state, $updates))
			{
				$productWatchRepo->setWatchStateForAll(\XF::visitor(), $action, $updates);
			}

			return $this->redirect($this->buildLink('watched/ecommerce-products'));
		}
		
		$viewParams = [
			'state' => $state
		];
		return $this->view('DBTech\eCommerce:Watched\ProductsManage', 'dbtech_ecommerce_watched_products_manage', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionEcommerceProductsUpdate()
	{
		$this->assertPostOnly();
		$this->setSectionContext('dbtechEcommerce');

		/** @var \DBTech\eCommerce\Repository\ProductWatch $watchRepo */
		$watchRepo = $this->repository('DBTech\eCommerce:ProductWatch');

		$inputAction = $this->filter('watch_action', 'str');
		$action = $this->getEcommerceProductWatchActionConfig($inputAction, $config);

		if ($action)
		{
			$ids = $this->filter('ids', 'array-uint');
			$products = $this->em()->findByIds('DBTech\eCommerce:Product', $ids);
			$visitor = \XF::visitor();

			/** @var \DBTech\eCommerce\Entity\Product $product */
			foreach ($products AS $product)
			{
				$watchRepo->setWatchState($product, $visitor, $action, $config);
			}
		}

		return $this->redirect(
			$this->getDynamicRedirect($this->buildLink('watched/ecommerce-products'))
		);
	}
	
	/**
	 * @param $inputAction
	 * @param array|null $config
	 *
	 * @return null|string
	 */
	protected function getEcommerceProductWatchActionConfig($inputAction, array &$config = null)
	{
		$config = [];

		switch ($inputAction)
		{
			case 'email_subscribe:on':
				$config = ['email_subscribe' => 1];
				return 'update';

			case 'email_subscribe:off':
				$config = ['email_subscribe' => 0];
				return 'update';

			case 'delete':
				return 'delete';

			default:
				return null;
		}
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \LogicException
	 */
	public function actionEcommerceCategories()
	{
		$this->setSectionContext('dbtechEcommerce');

		$watchedFinder = $this->finder('DBTech\eCommerce:CategoryWatch');
		$watchedCategories = $watchedFinder->where('user_id', \XF::visitor()->user_id)
			->keyedBy('category_id')
			->fetch();

		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = $this->repository('DBTech\eCommerce:Category');
		$categories = $categoryRepo->getViewableCategories();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

		$viewParams = [
			'watchedCategories' => $watchedCategories,

			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
		return $this->view('DBTech\eCommerce:Watched\Categories', 'dbtech_ecommerce_watched_categories', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionEcommerceCategoriesUpdate()
	{
		$this->assertPostOnly();
		$this->setSectionContext('dbtechEcommerce');

		/** @var \DBTech\eCommerce\Repository\CategoryWatch $watchRepo */
		$watchRepo = $this->repository('DBTech\eCommerce:CategoryWatch');

		$inputAction = $this->filter('watch_action', 'str');
		$action = $this->getEcommerceCategoryWatchActionConfig($inputAction, $config);

		if ($action)
		{
			$visitor = \XF::visitor();

			$ids = $this->filter('ids', 'array-uint');
			$categories = $this->em()->findByIds('DBTech\eCommerce:Category', $ids);

			/** @var \DBTech\eCommerce\Entity\Category $category */
			foreach ($categories AS $category)
			{
				$watchRepo->setWatchState($category, $visitor, $action, $config);
			}
		}

		return $this->redirect(
			$this->getDynamicRedirect($this->buildLink('watched/ecommerce-categories'))
		);
	}
	
	/**
	 * @param $inputAction
	 * @param array|null $config
	 *
	 * @return null|string
	 */
	protected function getEcommerceCategoryWatchActionConfig($inputAction, array &$config = null)
	{
		$config = [];

		$parts = explode(':', $inputAction, 2);

		$inputAction = $parts[0];
		$boolSwitch = isset($parts[1]) && $parts[1] == 'on';

		switch ($inputAction)
		{
			case 'send_email':
			case 'send_alert':
			case 'include_children':
				$config = [$inputAction => $boolSwitch];
				return 'update';

			case 'delete':
				return 'delete';

			default:
				return null;
		}
	}
}