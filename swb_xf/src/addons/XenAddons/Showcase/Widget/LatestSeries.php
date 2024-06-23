<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractWidget;

class LatestSeries extends AbstractWidget
{
	protected $defaultOptions = [
		'order' => 'last_part_date',
		'limit' => 5,
		'item_count' => 1,
		'exclude_featured' => false,
		'cutOffDays' => 0,
		'style' => 'simple',
		'require_series_icon' => false,
		'block_title_link' => '',
		'tags' => ''
	];

	protected function getDefaultTemplateParams($context)
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
		}
		return $params;
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
		$minRequiredPartCount = $options['item_count'];
		$cutOffDays = $options['cutOffDays'];
		$order = $options['order'] ? : 'last_part_date';
		$tags = $options['tags'];
		
		/** @var \XenAddons\Showcase\Finder\SeriesItem $finder */
		$finder = $this->finder('XenAddons\Showcase:SeriesItem');
		$finder->with(['User', 'LastItem']);
		
		if ($minRequiredPartCount > 0)
		{
			$finder->where('item_count', '>=', $minRequiredPartCount);
		}
		
		if ($options['require_series_icon'])
		{
			$finder->where('icon_date', '!=', 0);
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
		
		if ($cutOffDays)
		{
			$cutOffDate = \XF::$time - ($cutOffDays * 86400);
			
			if ($order == 'create_date')
			{
				$finder->where('create_date', '>', $cutOffDate);
			}
			else 
			{
				$finder->where('last_part_date', '>', $cutOffDate);
			}	
		}
		
		if ($order == 'random')
		{
			$finder->order($finder->expression('RAND()'));
		}
		else
		{
			$finder->order($order, 'desc');
		}
		
		$series = $finder->fetch(max($limit * 2, 10));

		/** @var \XenAddons\Showcase\Entity\SeriesItem $seriesItem */
		foreach ($series AS $seriesId => $seriesItem)
		{
			if (
				!$seriesItem->canView() 
				|| $visitor->isIgnoring($seriesItem->user_id)
				|| ($options['exclude_featured'] && $seriesItem->Featured)
			)
			{
				unset($series[$seriesId]);
			}
		}

		$total = $series->count();
		$series = $series->slice(0, $limit, true);
		
		// check to see if there is a block_title_link to be used for the block header! 
		if (isset ($options['block_title_link']) && $options['block_title_link'])
		{
			$link = $options['block_title_link'];
		}
		else 
		{
			$router = $this->app->router('public');
			$link = $router->buildLink('showcase/series');
		}
		
		$viewParams = [
			'title' => $this->getTitle(),
			'link' => $link,
			'series' => $series,
			'seriesCount' => $series->count(),
			'style' => $options['style'],
		];
		return $this->renderer('xa_sc_widget_latest_series', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'order' => 'str',
			'limit' => 'uint',
			'item_count' => 'uint',
			'exclude_featured' => 'bool',
			'cutOffDays' => 'uint',
			'style' => 'str',
			'require_series_icon' => 'bool',
			'block_title_link' => 'str',
			'tags' => 'str'
		]);
		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}
}