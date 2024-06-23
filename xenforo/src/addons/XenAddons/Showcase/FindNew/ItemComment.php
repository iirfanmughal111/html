<?php

namespace XenAddons\Showcase\FindNew;

use XF\Entity\FindNew;
use XF\FindNew\AbstractHandler;
use XF\Mvc\Entity\ArrayCollection;

class ItemComment extends AbstractHandler
{
	public function getRoute()
	{
		return 'whats-new/showcase-comments';
	}

	public function getPageReply(
		\XF\Mvc\Controller $controller, FindNew $findNew, array $results, $page, $perPage
	)
	{
		$viewParams = [
			'findNew' => $findNew,

			'page' => $page,
			'perPage' => $perPage,

			'items' => $results
		];
		
		return $controller->view('XenAddons\Showcase:WhatsNew\ItemComments', 'xa_sc_whats_new_item_comments', $viewParams);
	}

	public function getFiltersFromInput(\XF\Http\Request $request)
	{
		$filters = [];

		$visitor = \XF::visitor();

		$unread = $request->filter('unread', 'bool');
		if ($unread && $visitor->user_id)
		{
			$filters['unread'] = true;
		}

		$own = $request->filter('own', 'bool');
		if ($own && $visitor->user_id)
		{
			$filters['own'] = true;
		}

		return $filters;
	}

	public function getDefaultFilters()
	{
		$visitor = \XF::visitor();

		if ($visitor->user_id)
		{
			return ['unread' => true];
		}
		else
		{
			return [];
		}
	}

	public function getResultIds(array $filters, $maxResults)
	{
		$visitor = \XF::visitor();

		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = \XF::finder('XenAddons\Showcase:Item')
			->with(['Category'])
			->with('Category.Permissions|' . $visitor->permission_combination_id)
			->where('last_comment_date', '>', 0)
			->where('item_state', '<>', 'deleted')
			->where('Category.allow_comments', 1)
			->orderByDate('last_comment_date');

		$this->applyFilters($itemFinder, $filters);
		
		$items = $itemFinder->fetch($maxResults);
		$items = $this->filterResults($items)->toArray();

		$merged = $this->mergeAndSortItems($items);
		
		return array_keys($merged);
	}

	protected function mergeAndSortItems(array $items)
	{
		$merged = [];
		foreach ($items AS $itemId => $item)
		{
			$merged[$itemId] = $item;
		}

		uasort($merged, function($itemA, $itemB)
		{
			return ($itemB['last_comment_date'] - $itemA['last_comment_date']);
		});
		return $merged;
	}

	public function getPageResultsEntities(array $ids)
	{
		$itemIds = [];

		foreach ($ids AS $id)
		{
			$itemIds[] = $id;
		}

		$itemIds = array_map('intval', $itemIds);

		$visitor = \XF::visitor();

		$itemFinder = \XF::finder('XenAddons\Showcase:Item')
			->with(['Category', 'CoverImage'])
			->with('Category.Permissions|' . $visitor->permission_combination_id)
			->with('CommentRead|' . $visitor->user_id)
			->with('LastComment', true)
			->with('LastCommenter')
			->where('item_id', $itemIds)
			->orderByDate('last_comment_date');

		$items = $itemFinder->fetch()->toArray();

		$merged = $this->mergeAndSortItems($items);
		$merged = new ArrayCollection($merged);
		
		return $merged;
	}

	protected function filterResults(\XF\Mvc\Entity\AbstractCollection $results)
	{
		$visitor = \XF::visitor();

		return $results->filter(function(\XF\Mvc\Entity\Entity $entity) use($visitor)
		{
			/** @var \XenAddons\Showcase\Entity\Item $entity */
			return ($entity->canView() && !$visitor->isIgnoring($entity->user_id));
		});
	}

	/**
	 * @param \XenAddons\Showcase\Finder\Item $finder
	 *
	 * @param array $filters
	 */
	protected function applyFilters(\XF\Mvc\Entity\Finder $finder, array $filters)
	{
		$visitor = \XF::visitor();

		if (!empty($filters['unread']))
		{
			$finder->withUnreadCommentsOnly($visitor->user_id);
		}
		else if (sizeof($filters) != 1)
		{
			$finder->where('last_comment_date', '>', \XF::$time - (86400 * \XF::options()->readMarkingDataLifetime));
		}
	}

	public function getResultsPerPage()
	{
		return \XF::options()->xaScItemsPerPage;
	}

	public function isAvailable()
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor->canViewShowcaseItems()
			&& $visitor->canViewShowcaseComments();;
	}
}