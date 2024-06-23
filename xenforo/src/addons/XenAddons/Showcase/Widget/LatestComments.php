<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractWidget;

class LatestComments extends AbstractWidget
{
	protected $defaultOptions = [
		'limit' => 5,
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

		/** @var \XenAddons\Showcase\Repository\Comment $commentRepo */
		$commentRepo = $this->repository('XenAddons\Showcase:Comment');
		$finder = $commentRepo->findLatestCommentsForWidget($sourceCategoryIds);

		if (!$useContext)
		{
			// with the context, we already fetched the item category and permissions
			$finder->with('Item.Category.Permissions|' . $visitor->permission_combination_id);
		}
		
		$comments = $finder->fetch($this->options['limit'] * 10)->filterViewable();
		$comments = $comments->slice(0, $this->options['limit']);

		$router = $this->app->router('public');
		$link = $router->buildLink('whats-new/showcase-comments', null, ['skip' => 1]);

		$viewParams = [
			'title' => $this->getTitle(),
			'comments' => $comments,
			'link' => $link
		];
		return $this->renderer('xa_sc_widget_latest_comments', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
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