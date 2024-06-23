<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class Author extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		if ($params->user_id)
		{
			return $this->rerouteController('XenAddons\Showcase:Author', 'Author', $params);
		}

		$this->assertNotEmbeddedImageRequest();

		if (!$this->options()->xaScEnableAuthorList)
		{
			return $this->noPermission();
		}

		$this->assertCanonicalUrl($this->buildLink('showcase/authors'));

		$page = $this->filterPage();
		$perPage = $this->options()->membersPerPage; 

		$searcher = $this->searcher('XF:User');

		$finder = $searcher->getFinder()
			->isValidUser()
			->where('xa_sc_item_count', '>', 0)
			->with(['Profile'])
			->limitByPage($page, $perPage);

		$total = $finder->total();
		$this->assertValidPage($page, $perPage, $total, 'showcase/authors');
		
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->getViewableCategories();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

		$viewParams = [
			'authors' => $finder->fetch(),

			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,
			
			'categories' => $categories,
			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
		return $this->view('XenAddons\Showcase:Author', 'xa_sc_author_list', $viewParams);
	}
	
	public function actionAuthor(ParameterBag $params)
	{
		/** @var \XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id);
	
		/** @var \XenAddons\Showcase\ControllerPlugin\AuthorItemList $authorItemListPlugin */
		$authorItemListPlugin = $this->plugin('XenAddons\Showcase:AuthorItemList');
	
		$categoryParams = $authorItemListPlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();
	
		$listParams = $authorItemListPlugin->getAuthorItemListData($viewableCategoryIds, $user);
	
		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'showcase/authors', $user);
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/authors', $user, $listParams['page']));
		
		$viewParams = $categoryParams + $listParams;
	
		return $this->view('XenAddons\Showcase:Author\View', 'xa_sc_author_view', $viewParams);
	}
	
	public function actionFilters(ParameterBag $params)
	{
		/** @var \XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id);
		
		/** @var \XenAddons\Showcase\ControllerPlugin\AuthorItemList $authorItemListPlugin */
		$authorItemListPlugin = $this->plugin('XenAddons\Showcase:AuthorItemList');
	
		return $authorItemListPlugin->actionFilters($user);
	}

	public function actionReviews(ParameterBag $params)
	{
		/** @var \XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id);
	
		/** @var \XenAddons\Showcase\ControllerPlugin\AuthorReviewList $authorReviewListPlugin */
		$authorReviewListPlugin = $this->plugin('XenAddons\Showcase:AuthorReviewList');
	
		$categoryParams = $authorReviewListPlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();
	
		$listParams = $authorReviewListPlugin->getAuthorReviewListData($viewableCategoryIds, $user);
	
		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'showcase/authors/reviews', $user);
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/authors/reviews', $user, $listParams['page']));
	
		$viewParams = $categoryParams + $listParams;
	
		return $this->view('XenAddons\Showcase:Author\Reviews', 'xa_sc_author_reviews', $viewParams);
	}
	
	public function actionReviewsFilters(ParameterBag $params)
	{
		/** @var \XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id);
	
		/** @var \XenAddons\Showcase\ControllerPlugin\AuthorReviewList $authorReviewListPlugin */
		$authorReviewListPlugin = $this->plugin('XenAddons\Showcase:AuthorReviewList');
	
		return $authorReviewListPlugin->actionFilters($user);
	}	

	public function actionDrafts(ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();;
	
		/** @var \XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id);
	
		if (!$visitor->hasShowcaseItemPermission('viewDraft')
			&& (!$visitor->user_id || $visitor->user_id != $user->user_id)
		)
		{
			throw $this->exception($this->noPermission());
		}
	
		$itemRepo = $this->getItemRepo();
	
		$itemFinder = $itemRepo->findDraftItemsForUser($user);
	
		$page = $this->filterPage();
		$perPage = $this->options()->xaScItemsPerPage;
	
		$itemFinder->limitByPage($page, $perPage);
	
		$drafts = $itemFinder->fetch()->filterViewable();
		$totalDrafts = $itemFinder->total();
	
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->getViewableCategories();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);
		
		$viewParams = [
			'user' => $user,
			
			'drafts' =>  $drafts,
			
			'page' => $page,
			'perPage' => $perPage,
			'total' => $totalDrafts,
			
			'categories' => $categories,
			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
	
		return $this->view('XenAddons\Showcase:Author\Drafts', 'xa_sc_author_drafts', $viewParams);
	}
	
	public function actionAwaitingPublishing(ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
	
		/** @var \XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id);
	
		if (!$visitor->hasShowcaseItemPermission('viewDraft') // yes, this is the correct permission as awaiting is still a DRAFT until published. 
			&& (!$visitor->user_id || $visitor->user_id != $user->user_id)
		)
		{
			throw $this->exception($this->noPermission());
		}
	
		$itemRepo = $this->getItemRepo();
	
		$itemFinder = $itemRepo->findAwaitingItemsForUser($user);
	
		$page = $this->filterPage();
		$perPage = $this->options()->xaScItemsPerPage;
	
		$itemFinder->limitByPage($page, $perPage);
	
		$awaitingPublishing = $itemFinder->fetch()->filterViewable();
		$totalAwaitingPublishing = $itemFinder->total();
	
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->getViewableCategories();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);
		
		$viewParams = [
			'user' => $user,
			
			'awaitingPublishing' =>  $awaitingPublishing,

			'page' => $page,
			'perPage' => $perPage,
			'total' => $totalAwaitingPublishing,
				
			'categories' => $categories,
			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
	
		return $this->view('XenAddons\Showcase:Author\ItemsAwaitingPublishing', 'xa_sc_author_items_awaiting_publishing', $viewParams);
	}
	
	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('xa_sc_viewing_showcase');
	}
}