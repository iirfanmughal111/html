<?php

namespace XenAddons\Showcase\ActivitySummary;

use XF\ActivitySummary\AbstractSection;
use XF\ActivitySummary\Instance;
use XF\Mvc\Entity\Finder;

class LatestItems extends AbstractSection
{
	protected $defaultOptions = [
		'limit' => 5,
		'category_ids' => [0],
		'condition' => 'last_update',
		'min_comments' => null,
		'min_reaction_score' => null,
		'has_cover_image' => false,
		'order' => 'last_update',
		'direction' => 'DESC',
		'display_header' => false,
		'display_attribution' => false,
		'display_description' => false,
		'display_footer' => false,
		'display_footer_opposite' => false,
		'snippet_type' => 'plain_text',
	];

	public function getDefaultTitle(\XF\Entity\ActivitySummaryDefinition $definition)
	{
		return \XF::phrase('xa_sc_latest_items');
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
		return $this->finder('XenAddons\Showcase:Item')
			->with(['Category', 'User', 'User.PermissionCombination'])
			->setDefaultOrder($this->options['order'], $this->options['direction']);
	}

	protected function findDataForFetch(Finder $finder): Finder
	{
		$options = $this->options;

		$limit = $options['limit'];
		$categoryIds = $options['category_ids'];
		$condition = $options['condition'];

		$finder
			->where('item_state', 'visible')
			->limit(max($limit * 5, 25));

		if ($categoryIds && !in_array(0, $categoryIds))
		{
			$finder->where('category_id', $categoryIds);
		}

		$finder->where($condition, '>', $this->getActivityCutOff());

		if ($options['min_reaction_score'] !== null)
		{
			$finder->where('reaction_score', '>=', $minReactionScore);
		}
		
		if ($options['min_comments'] !== null)
		{
			$finder->where('comment_count', '>=', $options['min_comments']);
		}
		
		if ($options['has_cover_image'])
		{
			$finder->whereOr(
				['cover_image_id', '!=', 0],
				['Category.content_image_url', '!=', '']
			);
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

		$options = $this->options;
		
		/** @var \XF\Mvc\Entity\ArrayCollection|\XenAddons\Showcase\Entity\Item[] $items */
		$items = $this->fetchData();

		$categoryIds = $items->pluckNamed('category_id');

		$user->cacheShowcaseItemCategoryPermissions(array_unique($categoryIds));

		foreach ($items AS $itemId => $item)
		{
			if (!$item->canView() || $item->isIgnored())
			{
				unset($items[$itemId]);
				continue;
			}

			if ($instance->hasSeen('sc_item', $itemId))
			{
				unset($items[$itemId]);
				continue;
			}
		}

		if (!$items->count())
		{
			return '';
		}

		$items = $items->slice(0, $this->options['limit']);

		foreach ($items AS $item)
		{
			$instance->addSeen('sc_item', $item->item_id);
		}

		$viewParams = [
			'items' => $items,
			
			'displayHeader' => $options['display_header'],
			'displayAttribution' => $options['display_attribution'],
			'displayDescription' => $options['display_description'],
			'displayFooter' => $options['display_footer'],
			'displayFooterOpposite' => $options['display_footer_opposite'],
			'snippetType' => $options['snippet_type'],
		];
		return $this->renderSectionTemplate($instance, 'xa_sc_activity_summary_latest_items', $viewParams);
	}

	protected function getDefaultOrderOptions()
	{
		return [
			'create_date' => \XF::phrase('xa_sc_create_date'),
			'last_update' => \XF::phrase('xa_sc_last_update'),
			'comment_count' => \XF::phrase('comments'),
			'rating_weighted' => \XF::phrase('rating'),
			'reaction_score' => \XF::phrase('reaction_score'),
			'view_count' => \XF::phrase('views')
		];
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
			'category_ids' => 'array-uint',
			'condition' => 'str',
			'min_comments' => '?int',
			'min_reaction_score' => '?int',
			'has_cover_image' => 'bool',
			'order' => 'str',
			'direction' => 'str',
			'display_header' => 'bool',
			'display_attribution' => 'bool',
			'display_description' => 'bool',
			'display_footer' => 'bool',
			'display_footer_opposite' => 'bool',
			'snippet_type' => 'str',
		]);

		if (in_array(0, $options['category_ids']))
		{
			$options['category_ids'] = [0];
		}

		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		if (!in_array($options['condition'], ['create_date', 'last_update']))
		{
			$options['condition'] = 'last_update';
		}
		
		if (!in_array($options['snippet_type'], ['rich_text', 'plain_text']))
		{
			$options['condition'] = 'plain_text';
		}

		$orders = $this->getDefaultOrderOptions();
		if (!isset($orders[$options['order']]))
		{
			$options['order'] = 'create_date';
		}

		$options['direction'] = strtoupper($options['direction']);
		if (!in_array($options['direction'], ['ASC', 'DESC']))
		{
			$options['direction'] = 'DESC';
		}

		return true;
	}
}
