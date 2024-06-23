<?php

namespace XenAddons\Showcase\FindNew;

use XF\Entity\FindNew;
use XF\FindNew\AbstractHandler;

class Item extends AbstractHandler
{
	public function getRoute()
	{
		return 'whats-new/showcase-items';
	}

	public function getPageReply(
		\XF\Mvc\Controller $controller, FindNew $findNew, array $results, $page, $perPage
	)
	{
		$canInlineMod = false;

		/** @var \XenAddons\Showcase\Entity\Item $item */
		foreach ($results AS $item)
		{
			if ($item->canUseInlineModeration())
			{
				$canInlineMod = true;
				break;
			}
		}

		$viewParams = [
			'findNew' => $findNew,

			'page' => $page,
			'perPage' => $perPage,

			'items' => $results,
			'canInlineMod' => $canInlineMod
		];
		return $controller->view('XenAddons\Showcase:WhatsNew\Items', 'xa_sc_whats_new_items', $viewParams);
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

		$watched = $request->filter('watched', 'bool');
		if ($watched && $visitor->user_id)
		{
			$filters['watched'] = true;
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
			->where('item_state', '<>', 'deleted')
			->orderByDate();

		$this->applyFilters($itemFinder, $filters);

		$items = $itemFinder->fetch($maxResults);
		$items = $this->filterResults($items);

		return $items->keys();
	}

	public function getPageResultsEntities(array $ids)
	{
		$visitor = \XF::visitor();

		$ids = array_map('intval', $ids);

		$itemFinder = \XF::finder('XenAddons\Showcase:Item')
			->where('item_id', $ids)
			->with('fullCategory')
			->with('Category.Permissions|' . $visitor->permission_combination_id)
			->orderByDate();

		return $itemFinder->fetch();
	}

	protected function filterResults(\XF\Mvc\Entity\AbstractCollection $results)
	{
		$visitor = \XF::visitor();

		return $results->filter(function(\XenAddons\Showcase\Entity\Item $item) use($visitor)
		{
			return ($item->canView() && !$visitor->isIgnoring($item->user_id));
		});
	}

	protected function applyFilters(\XenAddons\Showcase\Finder\Item $item, array $filters)
	{
		$visitor = \XF::visitor();

		if (!empty($filters['unread']))
		{
			$item->unreadOnly($visitor->user_id);
		}
		else if (sizeof($filters) != 1)
		{
			$item->where('last_update', '>', \XF::$time - (86400 * \XF::options()->readMarkingDataLifetime));
		}

		if (!empty($filters['watched']))
		{
			$item->watchedOnly($visitor->user_id);
		}

		if (!empty($filters['own']))
		{
			$item->where('user_id', $visitor->user_id);
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
		return $visitor->canViewShowcaseItems();
	}
}