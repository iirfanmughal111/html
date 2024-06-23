<?php

namespace XenAddons\Showcase\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

class ItemsQueue extends AbstractPlugin
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

	public function getItemsQueueData(array $sourceCategoryIds)
	{
		$itemRepo = $this->getItemRepo();

		$itemFinder = $itemRepo->findItemsForItemsQueue($sourceCategoryIds);

		$filters = $this->getItemFilterInput();
		$this->applyItemFilters($itemFinder, $filters);
		
		$page = $this->filterPage();
		$perPage = $this->options()->xaScItemsPerPage;
		
		$itemFinder->limitByPage($page, $perPage);
		
		$items = $itemFinder->fetch()->filterViewable();
		$totalItems = $itemFinder->total();

		if (!empty($filters['creator_id']))
		{
			$creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
		}
		else
		{
			$creatorFilter = null;
		}
		
		return [
			'items' => $items,
			'filters' => $filters,
			'creatorFilter' => $creatorFilter,

			'total' => $totalItems,
			'page' => $page,
			'perPage' => $perPage,
		];
	}

	public function applyItemFilters(\XenAddons\Showcase\Finder\Item $itemFinder, array $filters)
	{
		if (!empty($filters['state']))
		{
			switch ($filters['state'])
			{
				case 'draft':
					$itemFinder->where('item_state', 'draft');
					break;
		
				case 'awaiting':
					$itemFinder->where('item_state', 'awaiting');
					break;
			}
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

	public function getItemFilterInput()
	{
		$filters = [];

		$input = $this->filter([
			'state' => 'str',
			'title' => 'str',
			'term' => 'str',
			'location' => 'str',	
			'prefix_id' => 'uint',
			'creator' => 'str',
			'creator_id' => 'uint',
			'last_days' => 'int',
			'order' => 'str',
			'direction' => 'str'
		]);
		
		if ($input['state'] && ($input['state'] == 'draft' || $input['state'] == 'awaiting'))
		{
			$filters['state'] = $input['state'];
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

			$defaultOrder = 'last_update';
			
			$defaultDir = 'desc';

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
			'create_date' => 'create_date',
			'last_update' => 'last_update',
			'title' => 'title'
		];
	}

	public function actionFilters()
	{
		$filters = $this->getItemFilterInput();
		
		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink('showcase/item-queue', null, $filters));
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
		
		$showLocationFilter = false;
		if ($this->options()->xaScGoogleMapsJavaScriptApiKey && $this->options()->xaScGoogleMapsGeocodingApiKey)
		{
			if ($applicableCategories) 
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

		$defaultOrder = 'last_update';
		
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
			'prefixesGrouped' => $prefixes->groupBy('prefix_group_id'),
			'filters' => $filters,
			'creatorFilter' => $creatorFilter,
			'showLocationFilter' => $showLocationFilter
		];
		return $this->view('XenAddons\Showcase:ItemQueueFilters', 'xa_sc_item_queue_filters', $viewParams);
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