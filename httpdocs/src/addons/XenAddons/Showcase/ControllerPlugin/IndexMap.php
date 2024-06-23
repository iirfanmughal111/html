<?php

namespace XenAddons\Showcase\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

use function in_array;

class IndexMap extends AbstractPlugin
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

	public function getIndexMapData(array $sourceCategoryIds)
	{
		$itemRepo = $this->getItemRepo();
		
		$itemFinder = $itemRepo->findItemsForIndexMap($sourceCategoryIds);

		$filters = $this->getItemFilterInput();
		$this->applyItemFilters($itemFinder, $filters);
		
		$markerLimit = $this->options()->xaScIndexFullPageMapOptions['marker_limit'] ?: 2500;
		
		$items = $itemFinder->fetch($markerLimit)->filterViewable();

		$mapItems = $this->em()->getEmptyCollection();

		if ($items)
		{
			foreach ($items AS $itemKey => $item)
			{
				if ($item->location && $item->location_data)
				{
					$mapItems[$itemKey] = $item;
				}
			}
		}
		
		if (!empty($filters['creator_id']))
		{
			$creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
		}
		else
		{
			$creatorFilter = null;
		}
		
		return [
			'mapItems' => $mapItems,
			
			'filters' => $filters,
			'creatorFilter' => $creatorFilter
		];
	}

	public function applyItemFilters(\XenAddons\Showcase\Finder\Item $itemFinder, array $filters)
	{
			if (!empty($filters['featured']))
		{
			$itemFinder->where('Featured.feature_date', '>', 0);
		}
		
		if (!empty($filters['is_rated']))
		{
			$itemFinder->where('rating_count', '>', 0);
		}
		
		if (!empty($filters['has_reviews']))
		{
			$itemFinder->where('review_count', '>', 0);
		}

		if (!empty($filters['has_comments']))
		{
			$itemFinder->where('comment_count', '>', 0);
		}
		
		if (!empty($filters['title']))
		{
			$itemFinder->where($itemFinder->columnUtf8('title'), 'LIKE', $itemFinder->escapeLike($filters['title'], '%?%'));
		}
		
		if (!empty($filters['term']))
		{
			$itemFinder->whereOr(
				[$itemFinder->columnUtf8('title'), 'LIKE', $itemFinder->escapeLike($filters['term'], '%?%')],
				[$itemFinder->columnUtf8('description'), 'LIKE', $itemFinder->escapeLike($filters['term'], '%?%')],
				[$itemFinder->columnUtf8('message'), 'LIKE', $itemFinder->escapeLike($filters['term'], '%?%')],
				[$itemFinder->columnUtf8('message_s2'), 'LIKE', $itemFinder->escapeLike($filters['term'], '%?%')],
				[$itemFinder->columnUtf8('message_s3'), 'LIKE', $itemFinder->escapeLike($filters['term'], '%?%')],
				[$itemFinder->columnUtf8('message_s4'), 'LIKE', $itemFinder->escapeLike($filters['term'], '%?%')],
				[$itemFinder->columnUtf8('message_s5'), 'LIKE', $itemFinder->escapeLike($filters['term'], '%?%')],
				[$itemFinder->columnUtf8('message_s6'), 'LIKE', $itemFinder->escapeLike($filters['term'], '%?%')]
			);
		}
		
		if (!empty($filters['location']))
		{
			$itemFinder->whereOr(
				[$itemFinder->columnUtf8('location'), 'LIKE', $itemFinder->escapeLike($filters['location'], '%?%')],
				[$itemFinder->columnUtf8('location_data'), 'LIKE', $itemFinder->escapeLike($filters['location'], '%?%')]
			);
		}
		
		if (!empty($filters['rating_avg']))
		{
			switch ($filters['rating_avg'])
			{
				case '5':
					$itemFinder->where('rating_avg', '>=', 5);
					break;
		
				case '4':
					$itemFinder->where('rating_avg', '>=', 4);
					break;
						
				case '3':
					$itemFinder->where('rating_avg', '>=', 3);
					break;
		
				case '2':
					$itemFinder->where('rating_avg', '>=', 2);
					break;
			}
		}		
		
		if (!empty($filters['prefix_id']))
		{
			$itemFinder->where('prefix_id', intval($filters['prefix_id']));
		}

		if (!empty($filters['creator_id']))
		{
			$itemFinder->where('user_id', intval($filters['creator_id']));
		}
		
		if (!empty($filters['last_days']))
		{
			if ($filters['last_days'] > 0)
			{
				$itemFinder->where('last_update', '>=', \XF::$time - ($filters['last_days'] * 86400));
			}
		}

		$sorts = $this->getAvailableItemSorts();

		if (!empty($filters['order']) && isset($sorts[$filters['order']]))
		{
			$itemFinder->order($sorts[$filters['order']], $filters['direction']);
		}
		// else the default order has already been applied
	}

	public function getItemFilterInput($itemType = '')
	{
		$filters = [];

		$input = $this->filter([
			'featured' => 'bool',
			'is_rated' => 'bool',
			'has_reviews' => 'bool',
			'has_comments' => 'bool',
			'title' => 'str',	
			'term' => 'str',
			'location' => 'str',
			'rating_avg' => 'int',
			'prefix_id' => 'uint',
			'creator' => 'str',
			'creator_id' => 'uint',
			'last_days' => 'int',
			'order' => 'str',
			'direction' => 'str'
		]);

			if ($input['featured'])
		{
			$filters['featured'] = true;
		}
		
		if ($input['is_rated'])
		{
			$filters['is_rated'] = true;
		}
		
		if ($input['has_reviews'])
		{
			$filters['has_reviews'] = true;
		}

		if ($input['has_comments'])
		{
			$filters['has_comments'] = true;
		}
		
		if ($input['title'])
		{
			$filters['title'] = $input['title'];
		}
		
		if ($input['term'])
		{
			$filters['term'] = $input['term'];
		}
		
		if ($input['location'])
		{
			$filters['location'] = $input['location'];
		}
		
		if ($input['rating_avg'] && ($input['rating_avg'] == 5 || $input['rating_avg'] == 4 || $input['rating_avg'] == 3 || $input['rating_avg'] == 2))
		{
			$filters['rating_avg'] = $input['rating_avg'];
		}
		
		if ($input['prefix_id'])
		{
			$filters['prefix_id'] = $input['prefix_id'];
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
		
		if ($input['last_days'] > 0) 
		{
			if (in_array($input['last_days'], $this->getAvailableDateLimits()))
			{
				$filters['last_days'] = $input['last_days'];
			}
		}		

		$sorts = $this->getAvailableItemSorts();

		if ($input['order'] && isset($sorts[$input['order']]))
		{
			if (!in_array($input['direction'], ['asc', 'desc']))
			{
				$input['direction'] = 'desc';
			}
			
			$defaultOrder = $this->options()->xaScIndexFullPageMapOptions['marker_fetch_order'] ?: 'rating_weighted';
			
			$defaultDir = $defaultOrder == 'title' ? 'asc' : 'desc';

			if ($input['order'] != $defaultOrder || $input['direction'] != $defaultDir)
			{
				$filters['order'] = $input['order'];
				$filters['direction'] = $input['direction'];
			}
		}

		return $filters;
	}
		
	protected function getAvailableDateLimits()
	{
		return [-1, 7, 14, 30, 60, 90, 182, 365];
	}

	public function getAvailableItemSorts()
	{
		return [
			'rating_weighted' => 'rating_weighted',
			'reaction_score' => 'reaction_score',
			'view_count' => 'view_count',
			'create_date' => 'create_date',
			'last_update' => 'last_update',
		];
	}

	public function actionFilters()
	{
		$filters = $this->getItemFilterInput();

		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink('showcase/full-map',	null, $filters));
		}

		if (!empty($filters['creator_id']))
		{
			$creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
		}
		else
		{
			$creatorFilter = null;
		}

		$applicableCategories = $this->getCategoryRepo()->getViewableCategories();
		$applicableCategoryIds = $applicableCategories->keys();

		$availablePrefixIds = $this->repository('XenAddons\Showcase:CategoryPrefix')->getPrefixIdsInContent($applicableCategoryIds);
		$prefixes = $this->repository('XenAddons\Showcase:ItemPrefix')->findPrefixesForList()
			->where('prefix_id', $availablePrefixIds)
			->fetch();
		
		$showRatingFilter = false;
		if ($applicableCategories)
		{
			foreach ($applicableCategories as $_applicableCategory)
			{
				if ($_applicableCategory->allow_ratings)
				{
					$showRatingFilter = true;
				}
			}
		}
		
		$showLocationFilter = false;
		if ($this->options()->xaScGoogleMapsJavaScriptApiKey && $this->options()->xaScGoogleMapsGeocodingApiKey)
		{
			if ($applicableCategories) // Check the sub cats...
			{
				foreach ($applicableCategories as $_applicableCategory)
				{
					if ($_applicableCategory->allow_location)
					{
						$showLocationFilter = true;
					}
				}
			}
		}
		
		$defaultOrder = $this->options()->xaScIndexFullPageMapOptions['marker_fetch_order'] ?: 'rating_weighted';
		$defaultDir = $defaultOrder == 'title' ? 'asc' : 'desc';

		if (empty($filters['order']))
		{
			$filters['order'] = $defaultOrder;
		}
		if (empty($filters['direction']))
		{
			$filters['direction'] = $defaultDir;
		}

		$actionLink = $this->buildLink('showcase/full-map-filters');
		
		$viewParams = [
			'actionLink' => $actionLink,

			'filters' => $filters,
			'creatorFilter' => $creatorFilter,
			
			'showRatingFilter' => $showRatingFilter,
			'showLocationFilter' => $showLocationFilter,
			
			'prefixesGrouped' => $prefixes->groupBy('prefix_group_id'),
		];
		return $this->view('XenAddons\Showcase:Filters', 'xa_sc_map_view_filters', $viewParams);  
	}

	/**
	 * @return \XenAddons\Showcase\Repository\Item
	 */
	protected function getItemRepo()
	{
		return $this->repository('XenAddons\Showcase:Item');
	}

	/**
	 * @return \XenAddons\Showcase\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XenAddons\Showcase:Category');
	}
}