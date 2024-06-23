<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractWidget;

use function in_array;

class LatestItems extends AbstractWidget
{
	protected $defaultOptions = [
		'order' => 'last_update',
		'limit' => 5,
		'cutOffDays' => 0,
		'exclude_featured' => false,
		'style' => 'simple',
		'require_cover_or_content_image' => false,
		'block_title_link' => '',
		'item_category_ids' => [],
		'item_prefix_ids' => [],
		'tags' => ''
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
		$cutOffDays = $options['cutOffDays'];
		$order = $options['order'] ? : 'last_update';
		$categoryIds = $options['item_category_ids'];
		$prefixIds = $options['item_prefix_ids'];
		$tags = $options['tags'];

		$hasCategoryIds = ($categoryIds && !in_array(0, $categoryIds));
		$hasCategoryContext = (
			isset($this->contextParams['category'])
			&& $this->contextParams['category'] instanceof \XenAddons\Showcase\Entity\Category
		);
		$useContext = false;
		$category = null;
		
		if (!$hasCategoryIds && $hasCategoryContext)
		{
			/** @var \XenAddons\Showcase\Entity\Category $category */
			$category = $this->contextParams['category'];
			$viewableDescendents = $category->getViewableDescendants();
			$sourceCategoryIds = array_keys($viewableDescendents);
			$sourceCategoryIds[] = $category->category_id;
		
			$useContext = true;
		}
		else if ($hasCategoryIds)
		{
			$sourceCategoryIds = $categoryIds;
		}
		else
		{
			$sourceCategoryIds = null;
		}
		
		/** @var \XenAddons\Showcase\Finder\Item $finder */
		$finder = $this->finder('XenAddons\Showcase:Item');
		
		$finder
			->where('item_state', 'visible')
			->with('User');
		
		if (is_array($sourceCategoryIds))
		{
			$finder->where('category_id', $sourceCategoryIds);
		}
		else
		{
			$finder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		if (!$useContext)
		{
			// with the context, we already fetched the category and permissions
			$finder->with('Category.Permissions|' . $visitor->permission_combination_id);
		}

		if ($cutOffDays)
		{
			$cutOffDate = \XF::$time - ($cutOffDays * 86400);
			$finder->where('last_update', '>', $cutOffDate);
		}
		
		if ($order == 'random')
		{
			$finder->order($finder->expression('RAND()'));
		}
		else
		{
			$finder->order($order, 'desc');
		}

		if ($tags)
		{
			/** @var \XF\Repository\Tag $tagRepo */
			$tagRepo = $this->repository('XF:Tag');
		
			$tags = $tagRepo->splitTagList($tags);
				
			if ($tags)
			{
				$validTags = $tagRepo->getTags($tags, $notFound);
				if ($notFound)
				{
					// if they entered an unknown tag, we don't want to ignore it, so we need to force no results
					$finder->whereImpossible();
				}
				else
				{
					foreach (array_keys($validTags) AS $tagId)
					{
						$finder->with('Tags|' . $tagId, true);
					}
				}
			}
		}
		
		if ($prefixIds && !in_array(0, $prefixIds))
		{
			$finder->where('prefix_id', $prefixIds);
		}
		
		if ($options['require_cover_or_content_image'])
		{
			$finder->whereOr(
				['cover_image_id', '!=', 0],
				['Category.content_image_url', '!=', '']
			);
		}

		if ($options['style'] != 'simple')
		{
			$finder->with('fullCategory');
		}

		$items = $finder->fetch(max($limit * 2, 10));

		/** @var \XenAddons\Showcase\Entity\Item $item */
		foreach ($items AS $itemId => $item)
		{
			if (
				!$item->canView() 
				|| $visitor->isIgnoring($item->user_id) 
				|| ($options['exclude_featured'] && $item->Featured)
			)
			{
				unset($items[$itemId]);
			}
		}

		$total = $items->count();
		$items = $items->slice(0, $limit, true);
		
		foreach ($items AS $item)
		{
			if (!$item->canViewFullItem())
			{
				$snippet = $this->app->stringFormatter()->wholeWordTrim($item->message, $this->app->options()->xaScLimitedViewItemLength);
				if (strlen($snippet) < strlen($item->message))
				{
					$item->message = $this->app->bbCode()->render($snippet, 'bbCodeClean', 'sc_item', null);
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
			$link = $router->buildLink('whats-new/showcase-items', null, ['skip' => 1]);
		}
		
		$viewParams = [
			'title' => $this->getTitle(),
			'link' => $link,
			'items' => $items,
			'itemsCount' => $items->count(),
			'style' => $options['style'],
		];
		return $this->renderer('xa_sc_widget_latest_items', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'order' => 'str',
			'limit' => 'uint',
			'cutOffDays' => 'uint',
			'exclude_featured' => 'bool',	
			'style' => 'str',
			'require_cover_or_content_image' => 'bool',
			'block_title_link' => 'str',
			'item_category_ids' => 'array-uint',
			'item_prefix_ids' => 'array-uint',
			'tags' => 'str'
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