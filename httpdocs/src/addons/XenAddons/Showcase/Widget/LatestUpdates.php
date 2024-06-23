<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractWidget;

class LatestUpdates extends AbstractWidget
{
	protected $defaultOptions = [
		'limit' => 5,
		'cutOffDays' => 90,
		'style' => 'simple',
		'item_category_ids' => []
	];

	protected function getDefaultTemplateParams($context)
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
			$categoryRepo = $this->repository('XenAddons\Showcase:Category');
			$params['categoryTree'] = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
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
		$cutOffDays = $options['cutOffDays'];
		$categoryIds = $options['item_category_ids'];
		
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
		
		/** @var \XenAddons\Showcase\Repository\ItemUpdate $updateRepo */
		$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');

		/** @var \XenAddons\Showcase\Finder\ItemUpdate $finder */
		$finder = $updateRepo->findLatestUpdatesForWidget($sourceCategoryIds, $cutOffDays);
		
		if (!$useContext)
		{
			// with the context, we already fetched the item category and permissions
			$finder->with('Item.Category.Permissions|' . $visitor->permission_combination_id);
		}
		
		if ($options['style'] == 'full')
		{
			$finder->with('full');
		}
		
		$updates = $finder->fetch(max($limit * 2, 10));
		
		/** @var \XenAddons\Showcase\Entity\ItemUpdate $updates */
		foreach ($updates AS $id => $update)
		{
			if (!$update->canView() || $update->isIgnored() || $update->Item->isIgnored())
			{
				unset($updates[$id]);
			}
		}

		$total = $updates->count();
		$updates = $updates->slice(0, $limit, true);

		if ($options['style'] == 'full')
		{
			$updates = $updateRepo->addRepliesToItemUpdates($updates);
				
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = \XF::repository('XF:Attachment');
			$attachmentRepo->addAttachmentsToContent($updates, 'sc_update');
		}
		
		$link = $this->app->router('public')->buildLink('showcase/latest-updates');

		$viewParams = [
			'title' => $this->getTitle(),
			'link' => $link,
			'updates' => $updates,
			'style' => $options['style'],
			'hasMore' => $total > $updates->count()
		];
		return $this->renderer('xa_sc_widget_latest_updates', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
			'cutOffDays' => 'uint',
			'style' => 'str',
			'item_category_ids' => 'array-uint'
		]);
		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}
		if (in_array(0, $options['item_category_ids']))
		{
			$options['item_category_ids'] = [0];
		}
		
		return true;
	}
}