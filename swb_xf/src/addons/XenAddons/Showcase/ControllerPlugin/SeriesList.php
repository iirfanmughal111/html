<?php

namespace XenAddons\Showcase\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

class SeriesList extends AbstractPlugin
{
	public function getCategoryListData(\XenAddons\Showcase\Entity\Category $category = null)
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
	
	public function getseriesListData()
	{
		$seriesRepo = $this->getSeriesRepo();
		
		$allowOwnPending = true;  // Series owners will be able to see their pending Showcase Series on the "Series Index" page.

		$seriesFinder = $seriesRepo->findSeriesForSeriesList([
			'allowOwnPending' => $allowOwnPending
		]);

		$filters = $this->getSeriesFilterInput();
		$this->applySeriesFilters($seriesFinder, $filters);
		
		// Featured Series are not fetched if any filters are applied!
		if (!$filters && $featuredLimit = $this->options()->xaScFeaturedSeriesLimit)
		{
			$featuredSeries = $seriesRepo->findFeaturedSeries()
				->fetch($featuredLimit)
				->filterViewable();
			
			if ($featuredSeries && $this->options()->xaScExcludeFeaturedSeriesFromListing)
			{
				$excludeSeriesIds = $featuredSeries->pluckNamed('series_id');
				$seriesFinder->where('series_id', '<>', $excludeSeriesIds);
			}
		}
		else
		{
			$featuredSeries = $this->em()->getEmptyCollection();
		}		

		$page = $this->filterPage();
		$perPage = $this->options()->xaScSeriesPerPage;

		$seriesFinder->limitByPage($page, $perPage);
		
		$series = $seriesFinder->fetch()->filterViewable();
		$totalSeries = $seriesFinder->total();

		if (!empty($filters['creator_id']))
		{
			$creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
		}
		else
		{
			$creatorFilter = null;
		}
		
		$canInlineMod = false;
		foreach ($series AS $serieItem)
		{
			/** @var \XenAddons\Showcase\Entity\Series $serieItem */
			if ($serieItem->canUseInlineModeration())
			{
				$canInlineMod = true;
				break;
			}
		}

		return [
			'series' => $series,
			'filters' => $filters,
			'creatorFilter' => $creatorFilter,
			'canInlineMod' => $canInlineMod,

			'total' => $totalSeries,
			'page' => $page,
			'perPage' => $perPage,

			'featuredSeries' => $featuredSeries
		];
	}

	public function applySeriesFilters(\XenAddons\Showcase\Finder\SeriesItem $seriesFinder, array $filters)
	{
		if (!empty($filters['featured']))
		{
			$seriesFinder->where('Featured.feature_date', '>', 0);
		}
		
		if (!empty($filters['has_items']))
		{
			$seriesFinder->where('item_count', '>', 0);
		}
		
		if (!empty($filters['community']))
		{
			$seriesFinder->where('community_series', '=', 1);
		}
			
		if (!empty($filters['title']))
		{
			$seriesFinder->where(
				$seriesFinder->columnUtf8('title'),
				'LIKE', $seriesFinder->escapeLike($filters['title'], '%?%'));  
		}
		
		if (!empty($filters['term']))
		{
			$seriesFinder->whereOr(
				[$seriesFinder->columnUtf8('title'), 'LIKE', $seriesFinder->escapeLike($filters['term'], '%?%')],
				[$seriesFinder->columnUtf8('description'), 'LIKE', $seriesFinder->escapeLike($filters['term'], '%?%')],
				[$seriesFinder->columnUtf8('message'), 'LIKE', $seriesFinder->escapeLike($filters['term'], '%?%')]
			);
		}
		
		if (!empty($filters['creator_id']))
		{
			$seriesFinder->where('user_id', intval($filters['creator_id']));
		}
		
		if (!empty($filters['state']))
		{
			switch ($filters['state'])
			{
				case 'visible':
					$seriesFinder->where('series_state', 'visible');
					break;
		
				case 'moderated':
					$seriesFinder->where('series_state', 'moderated');
					break;
		
				case 'deleted':
					$seriesFinder->where('series_state', 'deleted');
					break;
			}
		}		

		$sorts = $this->getAvailableSeriesSorts();

		if (!empty($filters['order']) && isset($sorts[$filters['order']]))
		{
			$seriesFinder->order($sorts[$filters['order']], $filters['direction']);
		}
	}

	public function getSeriesFilterInput()
	{
		$filters = [];

		$input = $this->filter([
			'featured' => 'bool',
			'has_items' => 'bool',
			'community' => 'bool',
			'title' => 'str',	
			'term' => 'str',
			'creator' => 'str',
			'creator_id' => 'uint',
			'state' => 'str',
			'order' => 'str',
			'direction' => 'str'
		]);
		
		if ($input['featured'])
		{
			$filters['featured'] = true;
		}
		
		if ($input['has_items'])
		{
			$filters['has_items'] = true;
		}
		
		if ($input['community'])
		{
			$filters['community'] = true;
		}

		if ($input['title'])
		{
			$filters['title'] = $input['title'];
		}
		
		if ($input['term'])
		{
			$filters['term'] = $input['term'];
		}
		
		if ($input['creator_id'])
		{
			$filters['creator_id'] = $input['creator_id'];
		}
		else if ($input['creator'])
		{
			$user = $this->em()->findOne('XF:User', ['username' => $input['creator']]);
			if ($user)
			{
				$filters['creator_id'] = $user->user_id;
			}
		}

		if ($input['state'] && ($input['state'] == 'visible' || $input['state'] == 'moderated' || $input['state'] == 'deleted'))
		{
			$filters['state'] = $input['state'];
		}
		
		$sorts = $this->getAvailableSeriesSorts();

		if ($input['order'] && isset($sorts[$input['order']]))
		{
			if (!in_array($input['direction'], ['asc', 'desc']))
			{
				$input['direction'] = 'desc';
			}

			$defaultOrder = 'last_part_date';
			$defaultDir = 'desc';

			if ($input['order'] != $defaultOrder || $input['direction'] != $defaultDir)
			{
				$filters['order'] = $input['order'];
				$filters['direction'] = $input['direction'];
			}
		}

		return $filters;
	}

	public function getAvailableSeriesSorts()
	{
		// maps [name of sort] => field in/relative to Series entity
		return [
			'last_part_date' => 'last_part_date',
			'create_date' => 'create_date',
			'item_count' => 'item_count',
			'view_count' => 'view_count',
			'watch_count' => 'watch_count',
			'title' => 'title'
		];
	}

	public function actionFilters()
	{
		$filters = $this->getSeriesFilterInput();

		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink('showcase/series', null, $filters));
		}

		if (!empty($filters['creator_id']))
		{
			$creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
		}
		else
		{
			$creatorFilter = null;
		}

		$defaultOrder = 'last_part_date';
		$defaultDir = 'desc';

		if (empty($filters['order']))
		{
			$filters['order'] = $defaultOrder;
		}
		if (empty($filters['direction']))
		{
			$filters['direction'] = $defaultDir;
		}

		$viewParams = [
			'filters' => $filters,
			'creatorFilter' => $creatorFilter,
		];
		return $this->view('XenAddons\Showcase\Series:Filters', 'xa_sc_series_list_filters', $viewParams);
	}

	public function actionFeatured()
	{
		$finder = $this->getSeriesRepo()->findFeaturedSeries();
		$finder->order('Featured.feature_date', 'desc');

		$series = $finder->fetch()->filterViewable();

		$canInlineMod = false;
		foreach ($series AS $seriesItem)
		{
			/** @var \XenAddons\Showcase\\Entity\SeriesItem $seriesItem */
			if ($seriesItem->canUseInlineModeration())
			{
				$canInlineMod = true;
				break;
			}
		}
		
		$viewParams = [
			'series' => $series,
			'canInlineMod' => $canInlineMod
		];
		return $this->view('XenAddons\Showcase\Series:Featured', 'xa_sc_series_list_featured', $viewParams);
	}
	
	
	/**
	 * @return \XenAddons\Showcase\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XenAddons\Showcase:Category');
	}

	/**
	 * @return \XenAddons\Showcase\Repository\Series
	 */
	protected function getSeriesRepo()
	{
		return $this->repository('XenAddons\Showcase:Series');
	}
}