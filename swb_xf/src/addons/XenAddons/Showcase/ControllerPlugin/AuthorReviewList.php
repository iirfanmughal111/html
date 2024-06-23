<?php

namespace XenAddons\Showcase\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

class AuthorReviewList extends AbstractPlugin
{
	public function getCategoryListData(\XenAddons\Showcase\Entity\Category $category = null)
	{
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->getViewableCategories();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

		return [
			'categories' => $categories,
			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
	}
	
	public function getAuthorReviewListData(array $sourceCategoryIds, \XF\Entity\User $user = null)
	{
		$itemRatingRepo = $this->getItemRatingRepo();
	
		$reviewFinder = $itemRatingRepo->findReviewsForAuthorReviewList($user, $sourceCategoryIds);
	
		$filters = $this->getReviewFilterInput();
		$this->applyReviewFilters($reviewFinder, $filters);
		
		$page = $this->filterPage();
		$perPage = $this->options()->xaScReviewsPerPage;
	
		$reviewFinder->limitByPage($page, $perPage);
		$reviews = $reviewFinder->fetch()->filterViewable();
		$reviews = $itemRatingRepo->addRepliesToItemRatings($reviews);

		$totalReviews = $reviewFinder->total();
		
		$effectiveOrder = $filters['order'] ?? 'rating_date';
	
		/** @var \XF\Repository\Attachment $attachmentRepo */
		$attachmentRepo = \XF::repository('XF:Attachment');
		$attachmentRepo->addAttachmentsToContent($reviews, 'sc_rating');

		$canInlineModReviews = false;
		foreach ($reviews AS $review)
		{
			if ($review->canUseInlineModeration())
			{
				$canInlineModReviews = true;
				break;
			}
		}
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('sc_rating', $reviews->keys());
		
		return [
			'user' => $user,
			'reviews' => $reviews,
			'filters' => $filters,
			
			'reviewTabs' => $this->getReviewTabs($filters, $effectiveOrder),
			'effectiveOrder' => $effectiveOrder,

			'page' => $page,
			'perPage' => $perPage,
			'total' => $totalReviews,
			
			'canInlineModReviews' => $canInlineModReviews,
		];
	}	

	protected function getReviewTabs(
		array $filters,
		string $effectiveOrder
	): array
	{
		$tabs = [
			'latest' => [
				'selected' => ($effectiveOrder == 'rating_date'),
				'filters' => array_replace($filters, [
					'order' => 'rating_date',
					'direction' => 'desc'
				])
				],
			'helpful' => [
				'selected' => ($effectiveOrder == 'vote_score'),
				'filters' => array_replace($filters, [
					'order' => 'vote_score',
					'direction' => 'desc'
				])
			],
			'rating' => [
				'selected' => ($effectiveOrder == 'rating'),
				'filters' => array_replace($filters, [
					'order' => 'rating',
					'direction' => 'desc'
				])
			]
		];
	
		$defaultOrder = 'rating_date';
		$defaultDirection = 'desc';
	
		foreach ($tabs AS $tabId => &$tab)
		{
			if (isset($tab['filters']['order']) && $tab['filters']['order'] == $defaultOrder)
			{
				$tab['filters']['order'] = null;
			}
			if (isset($tab['filters']['direction']) && $tab['filters']['direction'] == $defaultDirection)
			{
				$tab['filters']['direction'] = null;
			}
		}
	
		return $tabs;
	}
	
	public function applyReviewFilters(\XenAddons\Showcase\Finder\ItemRating $reviewFinder, array $filters)
	{
		if (!empty($filters['rating']))
		{
			$reviewFinder->where('rating', $filters['rating']);
		}
		
		if (!empty($filters['term']))
		{
			$reviewFinder->whereOr(
				[$reviewFinder->columnUtf8('title'), 'LIKE', $reviewFinder->escapeLike($filters['term'], '%?%')],
				[$reviewFinder->columnUtf8('message'), 'LIKE', $reviewFinder->escapeLike($filters['term'], '%?%')],
				[$reviewFinder->columnUtf8('pros'), 'LIKE', $reviewFinder->escapeLike($filters['term'], '%?%')],
				[$reviewFinder->columnUtf8('cons'), 'LIKE', $reviewFinder->escapeLike($filters['term'], '%?%')]
			);
		}
	
		$sorts = $this->getAvailableReviewSorts();
	
		if (!empty($filters['order']) && isset($sorts[$filters['order']]))
		{
			$reviewFinder->order($sorts[$filters['order']], $filters['direction']);
		}
		// else the default order has already been applied
	}
	
	public function getReviewFilterInput()
	{
		$filters = [];
	
		$input = $this->filter([
			'rating' => 'uint',
			'term' => 'str',
			'order' => 'str',
			'direction' => 'str'
		]);
		
		if ($input['rating'] >= 1 && $input['rating'] <= 5)
		{
			$filters['rating'] = $input['rating'];
		}
		
		if ($input['term'])
		{
			$filters['term'] = $input['term'];
		}
	
		$sorts = $this->getAvailableReviewSorts();
	
		if ($input['order'] && isset($sorts[$input['order']]))
		{
			if (!in_array($input['direction'], ['asc', 'desc']))
			{
				$input['direction'] = 'desc';
			}
	
			$defaultOrder = 'rating_date';
			$defaultDir = 'desc';
	
			if ($input['order'] != $defaultOrder || $input['direction'] != $defaultDir)
			{
				$filters['order'] = $input['order'];
				$filters['direction'] = $input['direction'];
			}
		}
	
		return $filters;
	}
	
	public function getAvailableReviewSorts()
	{
		return [
			'rating_date' => 'rating_date',
			'rating' => 'rating',
			'vote_score' => 'vote_score',
			'reaction_score' => 'reaction_score'
		];
	}
	
	public function actionFilters(\XF\Entity\User $user = null)
	{
		$filters = $this->getReviewFilterInput();
	
		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink('showcase/authors/reviews', $user, $filters));
		}
	
		$applicableCategories = $this->getCategoryRepo()->getViewableCategories();
		$applicableCategoryIds = $applicableCategories->keys();
	
		$defaultOrder = 'rating_date';
		$defaultDir = 'desc';
	
		if (empty($filters['order']))
		{
			$filters['order'] = $defaultOrder;
		}
		if (empty($filters['direction']))
		{
			$filters['direction'] = $defaultDir;
		}
	
		$viewParams = [
			'user' => $user,
			'filters' => $filters,
		];
		return $this->view('XenAddons\Showcase:AuthorReviewsFilters', 'xa_sc_author_reviews_filters', $viewParams);
	}

	/**
	 * @return \XenAddons\Showcase\Repository\ItemRating
	 */
	protected function getItemRatingRepo()
	{
		return $this->repository('XenAddons\Showcase:ItemRating');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\Item
	 */
	protected function getItemRepo()
	{
		return $this->repository('XenAddons\Showcase:Item');
	}

	/**
	 * @return \XenAddons\Showcase\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XenAddons\Showcase:Category');
	}
}