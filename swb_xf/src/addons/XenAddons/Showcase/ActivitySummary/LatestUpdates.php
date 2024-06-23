<?php

namespace XenAddons\Showcase\ActivitySummary;

use XF\ActivitySummary\AbstractSection;
use XF\ActivitySummary\Instance;
use XF\Mvc\Entity\Finder;

class LatestUpdates extends AbstractSection
{
	protected $defaultOptions = [
		'limit' => 5,
		'category_ids' => [0],
		'min_reaction_score' => null,
		'order' => 'update_date',
		'direction' => 'DESC'
	];

	public function getDefaultTitle(\XF\Entity\ActivitySummaryDefinition $definition)
	{
		return \XF::phrase('xa_sc_latest_updates');
	}

	protected function getDefaultTemplateParams($context)
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
			$categoryRepo = $this->app->repository('XenAddons\Showcase:Category');
			$params['categoryTree'] = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
			$params['sortOrders'] = $this->getDefaultOrderOptions();
		}
		return $params;
	}

	protected function getBaseFinderForFetch(): Finder
	{
		return $this->finder('XenAddons\Showcase:ItemUpdate')
			->with('Item', true)
			->with(['Item.Category', 'User'])
			->setDefaultOrder($this->options['order'], $this->options['direction']);
	}

	protected function findDataForFetch(Finder $finder): Finder
	{
		$options = $this->options;

		$limit = $options['limit'];
		$categoryIds = $options['category_ids'];

		$finder->where([
			'Item.item_state' => 'visible',
			'update_state' => 'visible'
		])->limit(max($limit * 2, 10));

		if ($categoryIds && !in_array(0, $categoryIds))
		{
			$finder->where('Item.category_id', $categoryIds);
		}

		$finder->where('update_date', '>', $this->getActivityCutOff());

		if ($options['min_reaction_score'] !== null)
		{
			$finder->where('reaction_score', '>=', $options['min_reaction_score']);
		}
		
		return $finder;
	}

	protected function renderInternal(Instance $instance): string
	{
		$user = $instance->getUser();
		if (!method_exists($user, 'cacheShowcaseItemCategoryPermissions'))
		{
			return '';
		}

		/** @var \XF\Mvc\Entity\ArrayCollection|\XenAddons\Showcase\Entity\ItemUpdate[] $updates */
		$updates = $this->fetchData();

		$categoryIds = $updates->pluck(
			function (\XenAddons\Showcase\Entity\ItemUpdate $update)
			{
				return $update->Item ? [$update->item_update_id, $update->Item->category_id] : null;
			},
			false
		);
		$user->cacheShowcaseItemCategoryPermissions(array_unique($categoryIds));

		foreach ($updates AS $updateId => $update)
		{
			if (!$update->canView() || $update->isIgnored())
			{
				unset($updates[$updateId]);
				continue;
			}

			if ($instance->hasSeen('sc_update', $updateId))
			{
				unset($updates[$updateId]);
				continue;
			}
		}

		if (!$updates->count())
		{
			return '';
		}

		$updates = $updates->slice(0, $this->options['limit']);

		foreach ($updates AS $update)
		{
			$instance->addSeen('sc_update', $update->item_update_id);
		}

		$viewParams = [
			'updates' => $updates
		];
		return $this->renderSectionTemplate($instance, 'xa_sc_activity_summary_latest_updates', $viewParams);
	}
	
	protected function getDefaultOrderOptions()
	{
		return [
			'update_date' => \XF::phrase('date'),
			'reaction_score' => \XF::phrase('reaction_score')
		];
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
			'category_ids' => 'array-uint',
			'min_reaction_score' => '?int',
			'order' => 'str',
			'direction' => 'str'
		]);

		if (in_array(0, $options['category_ids']))
		{
			$options['category_ids'] = [0];
		}

		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}
		
		$orders = $this->getDefaultOrderOptions();
		if (!isset($orders[$options['order']]))
		{
			$options['order'] = 'update_date';
		}
		
		$options['direction'] = strtoupper($options['direction']);
		if (!in_array($options['direction'], ['ASC', 'DESC']))
		{
			$options['direction'] = 'DESC';
		}

		return true;
	}
}