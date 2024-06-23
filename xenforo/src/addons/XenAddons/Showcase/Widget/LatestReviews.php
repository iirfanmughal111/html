<?php

namespace XenAddons\Showcase\Widget;

use XF\Widget\AbstractWidget;

class LatestReviews extends AbstractWidget
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
		
		/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
		$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');

		/** @var \XenAddons\Showcase\Finder\ItemRating $finder */
		$finder = $ratingRepo->findLatestReviewsForWidget($sourceCategoryIds, $cutOffDays);
		
		if (!$useContext)
		{
			// with the context, we already fetched the item category and permissions
			$finder->with('Item.Category.Permissions|' . $visitor->permission_combination_id);
		}
		
		if ($options['style'] == 'full')
		{
			$finder->with('full');
		}
		
		$reviews = $finder->fetch(max($limit * 2, 10));
		
		/** @var \XenAddons\Showcase\Entity\ItemRating $review */
		foreach ($reviews AS $id => $review)
		{
			if (!$review->canView() || $review->isIgnored() || $review->Item->isIgnored())
			{
				unset($reviews[$id]);
			}
		}

		$total = $reviews->count();
		$reviews = $reviews->slice(0, $limit, true);

		if ($options['style'] == 'full')
		{
			$reviews = $ratingRepo->addRepliesToItemRatings($reviews);
				
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = \XF::repository('XF:Attachment');
			$attachmentRepo->addAttachmentsToContent($reviews, 'sc_rating');
		}
		
		$link = $this->app->router('public')->buildLink('showcase/latest-reviews');

		$viewParams = [
			'title' => $this->getTitle(),
			'link' => $link,
			'reviews' => $reviews,
			'style' => $options['style'],
			'hasMore' => $total > $reviews->count()
		];
		return $this->renderer('xa_sc_widget_latest_reviews', $viewParams);
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