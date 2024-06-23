<?php

namespace XenAddons\Showcase\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

class LatestUpdatesList extends AbstractPlugin
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
	
	public function getLatestUpdatesListData(array $sourceCategoryIds)
	{
		$updateRepo = $this->getItemUpdateRepo();
		$updateFinder = $updateRepo->findLatestUpdates($sourceCategoryIds);
		
		$filters = $this->getUpdateFilterInput();
		$this->applyUpdateFilters($updateFinder, $filters);
		
		$page = $this->filterPage();
		$perPage = $this->options()->xaScUpdatesPerPage;
		
		$updateFinder->limitByPage($page, $perPage);
		$updates = $updateFinder->fetch()->filterViewable();
		$updates = $updateRepo->addRepliesToItemUpdates($updates);

		$totalUpdates = $updateFinder->total();
		
		if (!empty($filters['item_owner_id']))
		{
			$itemOwnerFilter = $this->em()->find('XF:User', $filters['item_owner_id']);
		}
		else
		{
			$itemOwnerFilter = null;
		}

		$canInlineModUpdates = false;
		foreach ($updates AS $update)
		{
			if ($update->canUseInlineModeration())
			{
				$canInlineModUpdates = true;
				break;
			}
		}	

		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('sc_update', $updates->keys());

		return [
			'updates' => $updates,
			'filters' => $filters,
			'itemOwnerFilter' => $itemOwnerFilter,
		
			'total' => $totalUpdates,			
			'page' => $page,
			'perPage' => $perPage,
			
			'canInlineModUpdates' => $canInlineModUpdates
		];
	}

	public function applyUpdateFilters(\XenAddons\Showcase\Finder\ItemUpdate $updateFinder, array $filters)
	{
		if (!empty($filters['term']))
		{
			$updateFinder->whereOr(
				[$updateFinder->columnUtf8('title'), 'LIKE', $updateFinder->escapeLike($filters['term'], '%?%')],
				[$updateFinder->columnUtf8('message'), 'LIKE', $updateFinder->escapeLike($filters['term'], '%?%')]
			);
		}
		
		if (!empty($filters['item_owner_id']))
		{
			$updateFinder->where('Item.user_id', intval($filters['item_owner_id']));
		}
		
		if (!empty($filters['last_days']))
		{
			if ($filters['last_days'] > 0)
			{
				$updateFinder->where('update_date', '>=', \XF::$time - ($filters['last_days'] * 86400));
			}
		}
		
		if (!empty($filters['state']))
		{
			switch ($filters['state'])
			{
				case 'visible':
					$updateFinder->where('update_state', 'visible');
					break;
		
				case 'moderated':
					$updateFinder->where('update_state', 'moderated');
					break;
		
				case 'deleted':
					$updateFinder->where('update_state', 'deleted');
					break;
			}
		}

		$sorts = $this->getAvailableUpdateSorts();

		if (!empty($filters['order']) && isset($sorts[$filters['order']]))
		{
			$updateFinder->order($sorts[$filters['order']], $filters['direction']);
		}
		// else the default order has already been applied
	}

	public function getUpdateFilterInput()
	{
		$filters = [];

		$input = $this->filter([
			'term' => 'str',
			'item_owner' => 'str',
			'item_owner_id' => 'uint',
			'last_days' => 'int',
			'state' => 'str',
			'order' => 'str',
			'direction' => 'str'
		]);
		
		if ($input['term'])
		{
			$filters['term'] = $input['term'];
		}
		
		if ($input['item_owner_id'])
		{
			$filters['item_owner_id'] = $input['item_owner_id'];
		}
		else if ($input['item_owner'])
		{
			$user = $this->em()->findOne('XF:User', ['username' => $input['item_owner']]);
			if ($user)
			{
				$filters['item_owner_id'] = $user->user_id;
			}
		}		
		
		if ($input['last_days'] > 0) 
		{
			if (in_array($input['last_days'], $this->getAvailableDateLimits()))
			{
				$filters['last_days'] = $input['last_days'];
			}
		}

		if ($input['state'] && ($input['state'] == 'visible' || $input['state'] == 'moderated' || $input['state'] == 'deleted'))
		{
			$filters['state'] = $input['state'];
		}

		$sorts = $this->getAvailableUpdateSorts();

		if ($input['order'] && isset($sorts[$input['order']]))
		{
			if (!in_array($input['direction'], ['asc', 'desc']))
			{
				$input['direction'] = 'desc';
			}

			$defaultOrder = 'update_date';
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

	public function getAvailableUpdateSorts()
	{
		return [
			'update_date' => 'update_date',
			'reaction_score' => 'reaction_score'
		];
	}

	public function actionFilters()
	{
		$filters = $this->getUpdateFilterInput();

		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink('showcase/latest-updates', null, $filters));
		}
		
		if (!empty($filters['item_owner_id']))
		{
			$itemOwnerFilter = $this->em()->find('XF:User', $filters['item_owner_id']);
		}
		else
		{
			$itemOwnerFilter = null;
		}

		$applicableCategories = $this->getCategoryRepo()->getViewableCategories();
		$applicableCategoryIds = $applicableCategories->keys();
		
		$defaultOrder = 'update_date';
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
			'actionLink' => $this->buildLink('showcase/latest-updates-filters'),

			'updateListType' => 'latestUpdatesList',
			
			'filters' => $filters,
			'itemOwnerFilter' => $itemOwnerFilter
		];
		return $this->view('XenAddons\Showcase:Filters', 'xa_sc_update_list_filters', $viewParams);
	}

	
	/**
	 * @return \XenAddons\Showcase\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XenAddons\Showcase:Category');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\Item
	 */
	protected function getItemRepo()
	{
		return $this->repository('XenAddons\Showcase:Item');
	}

	/**
	 * @return \XenAddons\Showcase\Repository\ItemUpdate
	 */
	protected function getItemUpdateRepo()
	{
		return $this->repository('XenAddons\Showcase:ItemUpdate');
	}
}