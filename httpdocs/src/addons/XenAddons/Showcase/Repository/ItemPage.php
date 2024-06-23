<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\PrintableException;

class ItemPage extends Repository
{
	public function findPagesInItem(\XenAddons\Showcase\Entity\Item $item, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemPage $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemPage');
		$finder->inItem($item, $limits)
			->where('page_state', 'visible')
			->setDefaultOrder('display_order', 'asc');

		return $finder;
	}
	
	public function findPagesInItemManagePages(\XenAddons\Showcase\Entity\Item $item, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemPage $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemPage');
		$finder->inItem($item, $limits)
			->setDefaultOrder('display_order', 'asc');
	
		return $finder;
	}	
	
	public function findItemPagesForUser(\XF\Entity\User $user, array $viewableCategoryIds = null, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemPage $itemPageFinder */
		$itemPageFinder = $this->finder('XenAddons\Showcase:ItemPage');
	
		$itemPageFinder->byUser($user)
			->with(['full'])
			->setDefaultOrder('create_date', 'desc');
	
		if (is_array($viewableCategoryIds))
		{
			// if we have viewable category IDs, we likely have those permissions
			$itemPageFinder->where('Item.category_id', $viewableCategoryIds);
		}
		else
		{
			$itemPageFinder->with('Item.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
	
		return $itemPageFinder;
	}	
	
	public function findItemPagesForItemPageList(array $viewableCategoryIds = null, array $limits = [], \XenAddons\Showcase\Entity\Category $category = null)
	{
		/** @var \XenAddons\Showcase\Finder\ItemPage $itemPageFinder */
		$itemPageFinder = $this->finder('XenAddons\Showcase:ItemPage');
		
		if (is_array($viewableCategoryIds))
		{
			$itemPageFinder->where('Item.category_id', $viewableCategoryIds);
		}
		else
		{
			$itemPageFinder->with('Item.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		$itemPageFinder
			->with(['full'])
			->setDefaultOrder('create_date', 'desc');
		
		return $itemPageFinder;		
	}
	
	public function getPagesImagesForItem(\XenAddons\Showcase\Entity\Item $item)
	{
		$db = $this->db();
	
		$ids = $db->fetchAllColumn("
			SELECT page_id
			FROM xf_xa_sc_item_page
			WHERE item_id = ?
			AND page_state = 'visible'
			AND attach_count > 0
			ORDER BY page_id
			", $item->item_id
		);
	
		if ($ids)
		{
			$attachments = $this->finder('XF:Attachment')
				->where([
					'content_type' => 'sc_page',
					'content_id' => $ids
				])
				->order('attach_date')
				->fetch();
		}
		else
		{
			$attachments = $this->em->getEmptyCollection();
		}
	
		return $attachments;
	}	

	public function sendModeratorActionAlert(\XenAddons\Showcase\Entity\ItemPage $page, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null)
	{
		$item = $page->Item;

		if (!$item || !$item->user_id || !$item->User)
		{
			return false;
		}
		
		if (!$forceUser)
		{
			if (!$page->user_id || !$page->User)
			{
				return false;
			}
		
			$forceUser = $page->User;
		}

		$extra = array_merge([
			'title' => $page->title,
			'link' => $this->app()->router('public')->buildLink('nopath:showcase/page', $page),
			'prefix_id' => $item->prefix_id,
			'itemTitle' => $item->title,
			'itemLink' => $this->app()->router('public')->buildLink('nopath:showcase', $item),
			'reason' => $reason
		], $extra);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0, '',
			'user', $forceUser->user_id,
			"sc_page_{$action}", $extra,
			['dependsOnAddOnId' => 'XenAddons/Showcase']
		);

		return true;
	}
	
	/**
	 * @param $url
	 * @param null $type
	 * @param null $error
	 *
	 * @return null|\XF\Mvc\Entity\Entity
	 */
	public function getItemPageFromUrl($url, $type = null, &$error = null)
	{
		$routePath = $this->app()->request()->getRoutePathFromUrl($url);
		$routeMatch = $this->app()->router($type)->routeToController($routePath);
		$params = $routeMatch->getParameterBag();
	
		if (!$params->page_id)
		{
			$error = \XF::phrase('xa_sc_no_item_page_id_could_be_found_from_that_url');
			return null;
		}
	
		$itemPage = $this->app()->find('XenAddons\Showcase:ItemPage', $params->page_id);
		if (!$itemPage)
		{
			$error = \XF::phrase('xa_sc_no_item_page_could_be_found_with_id_x', ['page_id' => $params->page_id]);
			return null;
		}
	
		return $itemPage;
	}
}