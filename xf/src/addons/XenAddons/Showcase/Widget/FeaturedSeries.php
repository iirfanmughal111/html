<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractWidget;

class FeaturedSeries extends AbstractWidget
{
	protected $defaultOptions = [
		'limit' => 5,
		'item_count' => 1,
		'style' => 'simple',
		'require_series_icon' => false,
		'tags' => '',
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
		$tags = $options['tags'];
		
		/** @var \XenAddons\Showcase\Finder\SeriesItem $finder */
		$finder = $this->finder('XenAddons\Showcase:SeriesItem');
		$finder
			->with('Featured', true)
			->with('User')
			->order($finder->expression('RAND()'));
		
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
		
		$series = $finder->fetch(max($limit * 2, 10));

		/** @var \XenAddons\Showcase\Entity\SeriesItem $seriesItem */
		foreach ($series AS $seriesId => $seriesItem)
		{
			if (!$seriesItem->canView() || $visitor->isIgnoring($seriesItem->user_id))
			{
				unset($series[$seriesId]);
			}
		}

		$total = $series->count();
		$series = $series->slice(0, $limit, true);
		
		$viewParams = [
			'title' => $this->getTitle(),
			'series' => $series,
			'seriesCount' => $series->count(),
			'style' => $options['style'],
		];
		return $this->renderer('xa_sc_widget_featured_series', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
			'item_count' => 'uint',
			'style' => 'str',
			'require_series_icon' => 'bool',
			'tags' => 'str'
		]);
		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\Series
	 */
	protected function getSeriesRepo()
	{
		return $this->repository('XenAddons\Showcase:Series');
	}
}