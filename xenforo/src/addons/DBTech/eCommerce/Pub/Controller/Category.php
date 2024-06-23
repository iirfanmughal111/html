<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\ParameterBag;

/**
 * Class Category
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class Category extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$visitor->canViewDbtechEcommerceProducts($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		if (!\XF::options()->dbtechEcommerceAddressCountry)
		{
			throw $this->errorException(\XF::phrase('dbtech_ecommerce_setup_missing_business_details'));
		}
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionIndex(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$category = $this->assertViewableCategory($params->category_id, $this->getCategoryViewExtraWith());

		/** @var \DBTech\eCommerce\ControllerPlugin\Overview $overviewPlugin */
		$overviewPlugin = $this->plugin('DBTech\eCommerce:Overview');

		$categoryParams = $overviewPlugin->getCategoryListData($category);

		/** @var \XF\Tree $categoryTree */
		$categoryTree = $categoryParams['categoryTree'];
		$descendants = $categoryTree->getDescendants($category->category_id);

		$sourceCategoryIds = array_keys($descendants);
		$sourceCategoryIds[] = $category->category_id;

		// for any contextual widget
		$category->cacheViewableDescendents($descendants);

		$listParams = $overviewPlugin->getCoreListData($sourceCategoryIds);

		$this->assertValidPage(
			$listParams['page'],
			$listParams['perPage'],
			$listParams['total'],
			'dbtech-ecommerce/categories',
			$category
		);
		$this->assertCanonicalUrl($this->buildLink(
			'dbtech-ecommerce/categories',
			$category,
			['page' => $listParams['page']]
		));

		$viewParams = [
			'category' => $category,
			'pendingApproval' => $this->filter('pending_approval', 'bool')
		];
		$viewParams += $categoryParams + $listParams;

		return $this->view('DBTech\eCommerce:Category\View', 'dbtech_ecommerce_category_view', $viewParams);
	}
	
	/**
	 * @return array
	 */
	protected function getCategoryViewExtraWith(): array
	{
		$extraWith = [];
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$extraWith[] = 'Watch|' . $userId;
		}

		return $extraWith;
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionFilters(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);

		/** @var \DBTech\eCommerce\ControllerPlugin\Overview $overviewPlugin */
		$overviewPlugin = $this->plugin('DBTech\eCommerce:Overview');

		return $overviewPlugin->actionFilters($category);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionWatch(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);
		if (!$category->canWatch($error))
		{
			return $this->noPermission($error);
		}

		$visitor = \XF::visitor();

		if ($this->isPost())
		{
			if ($this->filter('stop', 'bool'))
			{
				$action = 'delete';
				$config = [];
			}
			else
			{
				$action = 'watch';
				$config = $this->filter([
					'notify_on' => 'str',
					'send_alert' => 'bool',
					'send_email' => 'bool',
					'include_children' => 'bool'
				]);
			}

			/** @var \DBTech\eCommerce\Repository\CategoryWatch $watchRepo */
			$watchRepo = $this->repository('DBTech\eCommerce:CategoryWatch');
			$watchRepo->setWatchState($category, $visitor, $action, $config);

			$redirect = $this->redirect($this->buildLink('dbtech-ecommerce/categories', $category));
			$redirect->setJsonParam('switchKey', $action == 'delete' ? 'watch' : 'unwatch');
			return $redirect;
		}
		
		$viewParams = [
			'category' => $category,
			'isWatched' => !empty($category->Watch[$visitor->user_id])
		];
		return $this->view('DBTech\eCommerce:Category\Watch', 'dbtech_ecommerce_category_watch', $viewParams);
	}

	/**
	 * @param int|null $categoryId
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\Category
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableCategory(?int $categoryId, array $extraWith = []): \DBTech\eCommerce\Entity\Category
	{
		if (!$categoryId)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_category_not_found')));
		}

		$visitor = \XF::visitor();

		$extraWith[] = 'Permissions|' . $visitor->permission_combination_id;

		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->em()->find('DBTech\eCommerce:Category', $categoryId, $extraWith);
		if (!$category)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_category_not_found')));
		}

		if (!$category->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		return $category;
	}
	
	/**
	 * @param array $activities
	 *
	 * @return array|bool
	 */
	public static function getActivityDetails(array $activities)
	{
		return self::getActivityDetailsForContent(
			$activities,
			\XF::phrase('dbtech_ecommerce_viewing_product_category'),
			'category_id',
			function (array $ids): array
			{
				$categories = \XF::em()->findByIds(
					'DBTech\eCommerce:Category',
					$ids,
					['Permissions|' . \XF::visitor()->permission_combination_id]
				);

				$router = \XF::app()->router('public');
				$data = [];

				foreach ($categories->filterViewable() AS $id => $category)
				{
					$data[$id] = [
						'title' => $category->title,
						'url' => $router->buildLink('dbtech-ecommerce/categories', $category)
					];
				}

				return $data;
			}
		);
	}
}