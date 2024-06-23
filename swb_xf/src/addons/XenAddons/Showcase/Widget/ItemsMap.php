<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractWidget;

class ItemsMap extends AbstractWidget
{
	protected $defaultOptions = [
		'order' => 'rating_weighted',
		'featured_items_only' => false,
		'limit' => 100,
		'location' => '',
		'container_height' => 200,
		'block_title_link' => '',
		'item_category_ids' => [],
		'item_prefix_ids' => []
	];

	protected function getDefaultTemplateParams($context)
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
			$categoryRepo = $this->repository('XenAddons\Showcase:Category');
			$params['categoryTree'] = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
			
			$prefixListData = $this->getPrefixListData();
			$params['prefixGroups'] = $prefixListData['prefixGroups'];
			$params['prefixesGrouped'] = $prefixListData['prefixesGrouped'];
		}
		return $params;
	}
	
	protected function getPrefixListData()
	{
		/** @var \XenAddons\Showcase\Repository\ItemPrefix $prefixRepo */
		$prefixRepo = $this->repository('XenAddons\Showcase:ItemPrefix');
		return $prefixRepo->getVisiblePrefixListData();
	}

	public function render()
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!method_exists($visitor, 'canViewShowcaseItems') || !$visitor->canViewShowcaseItems())
		{
			return '';
		}

		$options = $this->options;
		$limit = $options['limit'];
		$order = $options['order'] ? : 'rating_weighted';
		$featureItemsOnly = $options['featured_items_only'];
		$categoryIds = $options['item_category_ids'];
		$prefixIds = $options['item_prefix_ids'];

		/** @var \XenAddons\Showcase\Finder\Item $finder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
		
		$itemFinder
			->where('item_state', 'visible')
			->where('location', '<>', '')
			->with('Category.Permissions|' . $visitor->permission_combination_id)
			->with('fullCategory')
			->order($order, 'desc');
		
		if (!empty($options['location']))
		{
			$itemFinder->whereOr(
				[$itemFinder->columnUtf8('location'), 'LIKE', $itemFinder->escapeLike($options['location'], '%?%')],
				[$itemFinder->columnUtf8('location_data'), 'LIKE', $itemFinder->escapeLike($options['location'], '%?%')]
			);
		}
		
		if ($categoryIds && !in_array(0, $categoryIds))
		{
			$itemFinder->where('category_id', $categoryIds);
		}
		
		if ($prefixIds && !in_array(0, $prefixIds))
		{
			$itemFinder->where('prefix_id', $prefixIds);
		}
		
		if ($featureItemsOnly)
		{
			$itemFinder->with('Featured', true);
		}
		
		$items = $itemFinder->fetch($limit)->filterViewable();
		
		/** @var \XenAddons\Showcase\Entity\Item $item */
		foreach ($items AS $itemId => $item)
		{
			if ($visitor->isIgnoring($item->user_id))
			{
				unset($items[$itemId]);
			}
		}

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
		
		// check to see if there is a block_title_link to be used for the block header! 
		if (isset ($options['block_title_link']) && $options['block_title_link'])
		{
			$link = $options['block_title_link'];
		}
		else 
		{
			$router = $this->app->router('public');
			$link = $router->buildLink('showcase');
		}
		
		$viewParams = [
			'title' => $this->getTitle(),
			'link' => $link,
			'mapItems' => $mapItems,
			'container_height' => $options['container_height'],
		];
		return $this->renderer('xa_sc_widget_items_map', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'order' => 'str',
			'featured_items_only' => 'bool',
			'limit' => 'uint',
			'location' => 'str',
			'container_height' => 'uint',
			'block_title_link' => 'str',
			'item_category_ids' => 'array-uint',
			'item_prefix_ids' => 'array-uint'
		]);
		if (in_array(0, $options['item_category_ids']))
		{
			$options['item_category_ids'] = [0];
		}
		if (in_array(0, $options['item_prefix_ids']))
		{
			$options['item_prefix_ids'] = [0];
		}
		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}
}