<?php

namespace XenAddons\Showcase\XF\Pub\Controller;

class Watched extends XFCP_Watched
{
	public function actionShowcaseItems()  
	{
		$this->setSectionContext('xa_showcase');

		$page = $this->filterPage();
		$perPage = $this->options()->xaScItemsPerPage;

		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $this->repository('XenAddons\Showcase:Item');
		$finder = $itemRepo->findItemsForWatchedList();

		$total = $finder->total();
		$items = $finder->limitByPage($page, $perPage)->fetch();

		$viewParams = [
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			'items' => $items->filterViewable()
		];
		return $this->view('XenAddons\Showcase:Watched\Items', 'xa_sc_watched_items', $viewParams);
	}

	public function actionShowcaseItemsManage()
	{
		$this->setSectionContext('xa_showcase');

		if (!$state = $this->filter('state', 'str'))
		{
			return $this->redirect($this->buildLink('watched/sc-items')); 
		}

		if ($this->isPost())
		{
			/** @var \XenAddons\Showcase\Repository\ItemWatch $itemWatchRepo */
			$itemWatchRepo = $this->repository('XenAddons\Showcase:ItemWatch');

			if ($action = $this->getShowcaseItemWatchActionConfig($state, $items))
			{
				$itemWatchRepo->setWatchStateForAll(\XF::visitor(), $action, $items);
			}

			return $this->redirect($this->buildLink('watched/showcase-items')); 
		}
		else
		{
			$viewParams = [
				'state' => $state
			];
			return $this->view('XenAddons\Showcase:Watched\ItemsManage', 'watched_sc_items_manage', $viewParams);  
		}
	}

	public function actionShowcaseItemsUpdate()
	{
		$this->assertPostOnly();
		$this->setSectionContext('xa_showcase');

		/** @var \XenAddons\Showcase\Repository\ItemWatch $watchRepo */
		$watchRepo = $this->repository('XenAddons\Showcase:ItemWatch');

		$inputAction = $this->filter('watch_action', 'str');
		$action = $this->getShowcaseItemWatchActionConfig($inputAction, $config);

		if ($action)
		{
			$ids = $this->filter('ids', 'array-uint');
			$items = $this->em()->findByIds('XenAddons\Showcase:Item', $ids);
			$visitor = \XF::visitor();

			/** @var \XenAddons\Showcase\Entity\Item $item */
			foreach ($items AS $item)
			{
				$watchRepo->setWatchState($item, $visitor, $action, $config);
			}
		}

		return $this->redirect(
			$this->getDynamicRedirect($this->buildLink('watched/showcase-items'))
		);
	}

	protected function getShowcaseItemWatchActionConfig($inputAction, array &$config = null)
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

	public function actionShowcaseCategories()
	{
		$this->setSectionContext('xa_showcase');

		$watchedFinder = $this->finder('XenAddons\Showcase:CategoryWatch');
		$watchedCategories = $watchedFinder->where('user_id', \XF::visitor()->user_id)
			->keyedBy('category_id')
			->fetch();

		/** @var \XenAddons\Showcase\Repository\Category $categoryRepo */
		$categoryRepo = $this->repository('XenAddons\Showcase:Category');
		$categories = $categoryRepo->getViewableCategories();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

		$viewParams = [
			'watchedCategories' => $watchedCategories,

			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
		return $this->view('XenAddons\Showcase:Watched\Categories', 'xa_sc_watched_categories', $viewParams);
	}

	public function actionShowcaseCategoriesUpdate()
	{
		$this->assertPostOnly();
		$this->setSectionContext('xa_showcase');

		/** @var \XenAddons\Showcase\Repository\CategoryWatch $watchRepo */
		$watchRepo = $this->repository('XenAddons\Showcase:CategoryWatch');

		$inputAction = $this->filter('watch_action', 'str');
		$action = $this->getShowcaseCategoryWatchActionConfig($inputAction, $config);

		if ($action)
		{
			$visitor = \XF::visitor();

			$ids = $this->filter('ids', 'array-uint');
			$categories = $this->em()->findByIds('XenAddons\Showcase:Category', $ids);

			/** @var \XenAddons\Showcase\Entity\Category $category */
			foreach ($categories AS $category)
			{
				$watchRepo->setWatchState($category, $visitor, $action, $config);
			}
		}

		return $this->redirect(
			$this->getDynamicRedirect($this->buildLink('watched/showcase-categories'))
		);
	}

	protected function getShowcaseCategoryWatchActionConfig($inputAction, array &$config = null)
	{
		$config = [];

		$parts = explode(':', $inputAction, 2);

		$inputAction = $parts[0];
		$boolSwitch = isset($parts[1]) ? ($parts[1] == 'on') : false;

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
	
	// Showcase Series Watch
	
	public function actionShowcaseSeries()
	{
		$this->setSectionContext('xa_showcase');
	
		$watchedFinder = $this->finder('XenAddons\Showcase:SeriesWatch');
		$watchedSeries = $watchedFinder->where('user_id', \XF::visitor()->user_id)
			->keyedBy('series_id')
			->fetch();
	
		$page = $this->filterPage();
		$perPage = $this->options()->xaScSeriesPerPage;
	
		/** @var \XenAddons\Showcase\Repository\Series $seriesRepo */
		$seriesRepo = $this->repository('XenAddons\Showcase:Series');
		$finder = $seriesRepo->findSeriesForWatchedList();
	
		$total = $finder->total();
		$series = $finder->limitByPage($page, $perPage)->fetch();
	
		$viewParams = [
			'watchedSeries' => $watchedSeries,
		
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			'series' => $series->filterViewable(),
		];
	
		return $this->view('XenAddons\Showcase:Watched\Series', 'xa_sc_watched_series', $viewParams);
	}
	
	public function actionShowcaseSeriesUpdate()
	{
		$this->assertPostOnly();
		$this->setSectionContext('xa_showcase');
	
		/** @var \XenAddons\Showcase\Repository\SeriesWatch $watchRepo */
		$watchRepo = $this->repository('XenAddons\Showcase:SeriesWatch');
	
		$inputAction = $this->filter('watch_action', 'str');
		$action = $this->getShowcaseSeriesWatchActionConfig($inputAction, $config);
	
		if ($action)
		{
			$visitor = \XF::visitor();
	
			$ids = $this->filter('ids', 'array-uint');
			$series = $this->em()->findByIds('XenAddons\Showcase:SeriesItem', $ids);
	
			/** @var \XenAddons\Showcase\Entity\SeriesItem $seriesItem */
			foreach ($series AS $seriesItem)
			{
				$watchRepo->setWatchState($seriesItem, $visitor, $action, $config);
			}
		}
	
		return $this->redirect(
			$this->getDynamicRedirect($this->buildLink('watched/showcase-item-series'))
		);
	}
	
	protected function getShowcaseSeriesWatchActionConfig($inputAction, array &$config = null)
	{
		$config = [];
	
		$parts = explode(':', $inputAction, 2);
	
		$inputAction = $parts[0];
		$boolSwitch = isset($parts[1]) ? ($parts[1] == 'on') : false;
	
		switch ($inputAction)
		{
			case 'send_email':
			case 'send_alert':
				$config = [$inputAction => $boolSwitch];
				return 'update';
	
			case 'delete':
				return 'delete';
	
			default:
				return null;
		}
	}	
	
}