<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;

class Item extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		if ($this->responseType == 'rss')
		{
			return $this->getShowcaseRss();
		}
		
		if ($params->item_id)
		{
			return $this->rerouteController(__CLASS__, 'view', $params);
		}

		if (isset($this->options()->xaScIndexPageType) && $this->options()->xaScIndexPageType == 'modular')
		{
			return $this->rerouteController(__CLASS__, 'modular', $params);
		}
		
		$this->assertNotEmbeddedImageRequest();

		/** @var \XenAddons\Showcase\ControllerPlugin\ItemList $itemListPlugin */
		$itemListPlugin = $this->plugin('XenAddons\Showcase:ItemList');

		$categoryParams = $itemListPlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();
		
		// unset Category Ids in which item content is not to be display on the Showcase Index.
		foreach ($viewableCategoryIds AS $key => $categoryId)
		{
			$category = $categoryParams['categories'][$categoryId];
		
			if (!$category->display_items_on_index)
			{
				unset($viewableCategoryIds[$key]);
			}
		}

		$listParams = $itemListPlugin->getItemListData($viewableCategoryIds);
		
		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'showcase');
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase', null, $listParams['page']));

		$viewParams = $categoryParams + $listParams;

		return $this->view('XenAddons\Showcase:Index', 'xa_sc_index', $viewParams);
	}

	public function actionFilters()
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\ItemList $itemListPlugin */
		$itemListPlugin = $this->plugin('XenAddons\Showcase:ItemList');
	
		return $itemListPlugin->actionFilters();
	}
	
	public function actionModular(ParameterBag $params)
	{
		$viewParams = [];
	
		return $this->view('XenAddons\Showcase:Modular', 'xa_sc_index_modular', $viewParams);
	}
	
	public function actionFullMap(ParameterBag $params)
	{
		$visitor = \XF::visitor();
	
		if (!$this->options()->xaScGoogleMapsJavaScriptApiKey || !$visitor->hasShowcaseItemPermission('viewMultiMarkerMaps'))
		{
			return $this->noPermission();
		}
	
		if ($this->options()->xaScIndexFullPageMapOptions['enable_full_page_map'])
		{
			/** @var \XenAddons\Showcase\ControllerPlugin\IndexMap $indexMapPlugin */
			$indexMapPlugin = $this->plugin('XenAddons\Showcase:IndexMap');
	
			$categoryParams = $indexMapPlugin->getCategoryListData();
			$viewableCategoryIds = $categoryParams['categories']->keys();
	
			$listParams = $indexMapPlugin->getIndexMapData($viewableCategoryIds);
	
			$this->assertCanonicalUrl($this->buildLink('showcase/full-map'));
	
			$viewParams = [];
			$viewParams += $categoryParams + $listParams;
	
			return $this->view('XenAddons\Showcase:Item\IndexMap', 'xa_sc_index_map_view', $viewParams);
		}
		else
		{
			return $this->noPermission();
		}
	}
	
	public function actionFullMapFilters(ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\IndexMap $indexMapPlugin */
		$indexMapPlugin = $this->plugin('XenAddons\Showcase:IndexMap');
	
		return $indexMapPlugin->actionFilters();
	}	

	public function actionFeatured()
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\ItemList $itemListPlugin */
		$itemListPlugin = $this->plugin('XenAddons\Showcase:ItemList');

		return $itemListPlugin->actionFeatured();
	}

	public function actionLatestReviews()
	{
		$this->assertNotEmbeddedImageRequest();
		
		$this->assertCanonicalUrl($this->buildLink('showcase/latest-reviews'));
		
		$categoryRepo = $this->getCategoryRepo();
		$categories = $categoryRepo->getViewableCategories();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);
		
		$viewableCategoryIds = $categories->keys();

		$page = $this->filterPage();
		$perPage = $this->options()->xaScReviewsPerPage;
		
		$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
		$reviewFinder = $ratingRepo->findLatestReviews($viewableCategoryIds);

		$filters = $this->getReviewFilterInput();
		$this->applyReviewFilters($reviewFinder, $filters);

		$total = $reviewFinder->total();

		$this->assertValidPage($page, $perPage, $total, 'showcase/latest-reviews');
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/latest-reviews', null, $page));

		$reviewFinder->with('full')->limitByPage($page, $perPage);
		$reviews = $reviewFinder->fetch()->filterViewable();
		$reviews = $ratingRepo->addRepliesToItemRatings($reviews);

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
		
		$viewParams = [
			'reviews' => $reviews,
			
			'filters' => $filters,
			
			'reviewTabs' => $this->getReviewTabs($filters, $effectiveOrder),
			'effectiveOrder' => $effectiveOrder,
			
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
				
			'categories' => $categories,
			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras,
			
			'canInlineModReviews' => $canInlineModReviews
		];
		return $this->view('XenAddons\Showcase:LatestReviews', 'xa_sc_latest_reviews', $viewParams);
	}
	
	public function actionLatestReviewsFilters(ParameterBag $params)
	{
		$filters = $this->getReviewFilterInput();
	
		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink('showcase/latest-reviews', null, $filters));
		}
	
		$viewParams = [
			'filters' => $filters
		];
		return $this->view('XenAddons\Showcase:Item\LatestReviewsFilters', 'xa_sc_latest_reviews_filters', $viewParams);
	}
	
	public function actionLatestUpdates()
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\LatestUpdatesList $latestUpdatesListPlugin */
		$latestUpdatesListPlugin = $this->plugin('XenAddons\Showcase:LatestUpdatesList');
	
		$categoryParams = $latestUpdatesListPlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();
	
		$listParams = $latestUpdatesListPlugin->getLatestUpdatesListData($viewableCategoryIds);
	
		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'showcase/latest-updates');
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/latest-updates', null, $listParams['page']));
	
		$viewParams = $categoryParams + $listParams;
	
		return $this->view('XenAddons\Showcase:LatestUpdates', 'xa_sc_latest_updates', $viewParams);
	}
	
	public function actionLatestUpdatesFilters()
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\LatestUpdatesList $latestUpdatesListPlugin */
		$latestUpdatesListPlugin = $this->plugin('XenAddons\Showcase:LatestUpdatesList');
	
		return $latestUpdatesListPlugin->actionFilters();
	}

	public function actionAdd()
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		if (!$visitor->canAddShowcaseItem($error))
		{
			return $this->noPermission($error);
		}

		$this->assertCanonicalUrl($this->buildLink('showcase/add'));

		$categoryRepo = $this->getCategoryRepo();

		$categories = $categoryRepo->getViewableCategories();
		$canAdd = false;

		foreach ($categories AS $category)
		{
			/** @var \XenAddons\Showcase\Entity\Category $category */
			if ($category->canAddItem())
			{
				$canAdd = true;
				break;
			}
		}

		if (!$canAdd)
		{
			return $this->noPermission();
		}

		$categoryTree = $categoryRepo->createCategoryTree($categories);
		$categoryTree = $categoryTree->filter(null, function($id, \XenAddons\Showcase\Entity\Category $category, $depth, $children)
		{
			if ($children)
			{
				return true;
			}
			if ($category->canAddItem())
			{
				return true;
			}

			return false;
		});

		$categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

		$viewParams = [
			'categoryTree' => $categoryTree,
			'categoryExtras' => $categoryExtras
		];
		return $this->view('XenAddons\Showcase:Item\AddChooser', 'xa_sc_item_add_chooser', $viewParams);
	}

	public function actionView(ParameterBag $params)
	{
		$this->assertNotEmbeddedImageRequest();
		
		if (isset($this->options()->xaScSectionsDisplayType) 
			&& $this->options()->xaScSectionsDisplayType == 'tabbed'
			&& $this->filter('section', 'str')		
		)
		{
			return $this->rerouteController(__CLASS__, 'section', $params);
		}
		
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());
		
		if ($item_page_id = $this->filter('item_page', 'int'))
		{
			$itemPage = $this->assertViewablePage($item_page_id);
		
			if ($itemPage && $itemPage->item_id == $item->item_id)
			{
				return $this->redirect($this->buildLink('showcase/page', $itemPage));
			}
			else
			{
				return $this->redirect($this->buildLink('showcase', $item));
			}
		}

		$category = $item->Category;

		$page = $this->filterPage($params->page);
		$perPage = $this->options()->xaScCommentsPerPage;
		
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase', $item, $page));
		
		$itemRepo = $this->getItemRepo();
		$commentRepo = $this->getCommentRepo();
		
		$isFullView = false;
		$nextPage = false;
		$previousPage = false;
		$itemPages = $item->getItemPages();
		if ($itemPages)
		{
			if ($this->options()->xaScViewFullItem)
			{
				$isFullView = $this->filter('full', 'bool');
			}
		
			if (!$isFullView) // prepare data for previous/next pages navigation!
			{
				$nextPage = $item->getNextPage($itemPages);
				$previousPage = $item->getPreviousPage($itemPages);
			}
		
			$item->page_count = $item->page_count + 1;  // this counts the item, plus the additional pages to get the correct count for a multi page item!
		}
		
		if ($isFullView)
		{
			$sectionNavId = 0; // we don't want section 1 highlited in the TOC when viewing item in Full View!
		}
		else
		{
			$sectionNavId = $this->filter('section', 'str') ?: 1;
		}
		$section = 'section_' . $sectionNavId; // this is used for the TOC only
		
		$nextSeriesPart = false;
		$previousSeriesPart = false;
		$seriesToc = $item->getSeriesToc();
		if ($seriesToc)
		{
			$nextSeriesPart = $item->getNextSeriesPart($seriesToc);
			$previousSeriesPart = $item->getPreviousSeriesPart($seriesToc);
		}
		
		/** @var \XenAddons\Showcase\Entity\Comment[] $comments */
		$commentList = $commentRepo->findCommentsForContent($item)
			->limitByPage($page, $perPage);
		
		$comments = $commentList->fetch();
		$totalItems = $commentList->total();

		if ($comments)
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = \XF::repository('XF:Attachment');
			$attachmentRepo->addAttachmentsToContent($comments, 'sc_comment');
		}
		
		$canInlineModComments = false;
		foreach ($comments AS $comment)
		{
			if ($comment->canUseInlineModeration())
			{
				$canInlineModComments = true;
				break;
			}
		}
		
		$this->assertValidPage($page, $perPage, $totalItems, 'showcase', $item);
		
		$latestUpdates = $this->em()->getEmptyCollection();
		if ($item->real_update_count)
		{
			$recentUpdatesMax = $this->options()->xaScRecentUpdatesCount;
			if ($recentUpdatesMax)
			{
				/** @var \XenAddons\Showcase\Repository\ItemUpdate $updateRepo */
				$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');
				$latestUpdates = $updateRepo->findUpdatesForItem($item)->with('full')->fetch($recentUpdatesMax);
				$latestUpdates = $updateRepo->addRepliesToItemUpdates($latestUpdates);
				
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = \XF::repository('XF:Attachment');
				$attachmentRepo->addAttachmentsToContent($latestUpdates, 'sc_update');
			}
		}
		
		$canInlineModUpdates = false;
		foreach ($latestUpdates AS $update)
		{
			if ($update->canUseInlineModeration())
			{
				$canInlineModUpdates = true;
				break;
			}
		}
		
		$latestReviews = $this->em()->getEmptyCollection();
		if ($item->real_review_count)
		{
			$recentReviewsMax = $this->options()->xaScRecentReviewsCount;
			if ($recentReviewsMax)
			{
				/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
				$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
				$latestReviews = $ratingRepo->findReviewsInItem($item)->with('full')->fetch($recentReviewsMax);
				$latestReviews = $ratingRepo->addRepliesToItemRatings($latestReviews);
				
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = \XF::repository('XF:Attachment');
				$attachmentRepo->addAttachmentsToContent($latestReviews, 'sc_rating');
			}
		}
		
		$canInlineModReviews = false;
		foreach ($latestReviews AS $review)
		{
			if ($review->canUseInlineModeration())
			{
				$canInlineModReviews = true;
				break;
			}
		}
		
		if ($item->canViewContributors())
		{
			$contributors = $item->Contributors;
		}
		else
		{
			$contributors = $this->em()->getEmptyCollection();
		}
		
		$excludeItemIds = [];
		if ($this->options()->xaScCategoryOtherItemsCount && $item->Category)
		{
			$categoryOthers = $this->getItemRepo()
				->findOtherItemsByCategory($item)
				->with('full')
				->fetch($this->options()->xaScCategoryOtherItemsCount);
			$categoryOthers = $categoryOthers->filterViewable();
			$excludeItemIds = $categoryOthers->pluckNamed('item_id');
		}
		else
		{
			$categoryOthers = $this->em()->getEmptyCollection();
		}
		
		if ($this->options()->xaScAuthorOtherItemsCount && $item->User)
		{
			$authorOthers = $this->getItemRepo()
				->findOtherItemsByAuthor($item, $excludeItemIds)
				->with('full')
				->fetch($this->options()->xaScAuthorOtherItemsCount);
			$authorOthers = $authorOthers->filterViewable();
		}
		else
		{
			$authorOthers = $this->em()->getEmptyCollection();
		}		
		
		if ($category && $category->canUploadAndManageCommentImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_comment', $category);
		}
		else
		{
			$attachmentData = null;
		}
		
		$poll = ($item->has_poll ? $item->Poll : null);
		
		$itemRepo->markItemReadByVisitor($item);
		
		// Only log views to non contributors (contributors include Author, Co-Authors and Contributors)
		if (!$item->isContributor())
		{
			$itemRepo->logItemView($item);
		}
		
		$last = $comments->last();
		if ($last)
		{
			$itemRepo->markItemCommentsReadByVisitor($item, $last->comment_date);
		}
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('sc_item', $item->item_id);
		$userAlertRepo->markUserAlertsReadForContent('sc_update', $latestUpdates->keys());
		$userAlertRepo->markUserAlertsReadForContent('sc_rating', $latestReviews->keys());
		$userAlertRepo->markUserAlertsReadForContent('sc_comment', $comments->keys());
		
		$viewParams = [
			'item' => $item,
			'trimmedItem' => $item->getTrimmedItem(),
			'category' => $category,
			'contributors' => $contributors,
						
			'poll' => $poll,
			
			'section' => $section,
			'sectionNavId' => $sectionNavId,
			
			'itemPages' => $itemPages,
			'isFullView' => $isFullView,
			'nextPage' => $nextPage,
			'previousPage' => $previousPage,
			
			'seriesToc' => $seriesToc,
			'nextSeriesPart' => $nextSeriesPart,
			'previousSeriesPart' => $previousSeriesPart,

			'latestUpdates' => $latestUpdates,
			'latestReviews' => $latestReviews,
			
			'categoryOthers' => $categoryOthers,
			'authorOthers' => $authorOthers,

			'comments' => $comments,
			'attachmentData' => $attachmentData,
			
			'page' => $page,
			'perPage' => $perPage,
			'totalItems' => $totalItems,
			'pageNavHash' => '>0:#comments',

			'canInlineModUpdates' => $canInlineModUpdates,
			'canInlineModReviews' => $canInlineModReviews,
			'canInlineModComments' => $canInlineModComments
	];
		return $this->view('XenAddons\Showcase:Item\View', 'xa_sc_item_view', $viewParams);
	}

	public function actionCoverImage(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id, ['CoverImage']);
	
		if (!$item->CoverImage)
		{
			return $this->notFound();
		}
	
		$this->request->set('no_canonical', 1);
	
		return $this->rerouteController('XF:Attachment', 'index', ['attachment_id' => $item->CoverImage->attachment_id]);
	}
	
	protected function getItemViewExtraWith()
	{
		$extraWith = ['CoverImage', 'Featured'];
		
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$extraWith[] = 'Read|' . $userId;
			$extraWith[] = 'Watch|' . $userId;
			$extraWith[] = 'Bookmarks|' . $userId;
			$extraWith[] = 'Reactions|' . $userId;
			$extraWith[] = 'ReplyBans|' . $userId;
		}

		return $extraWith;
	}

	public function actionSection(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());
	
		$sectionId = $this->filter('section', 'str');
		if (!in_array($sectionId, ['2', '3', '4', '5', '6']) || !$item->canViewFullItem())
		{
			return $this->redirect($this->buildLink('showcase', $item));
		}
	
		$category = $item->Category;
	
		$section = 'section_' . $sectionId;
		$sectionField = 'title_s' . $sectionId;
		$sectionTitle = $category->$sectionField;
		
		$isFullView = false;
		$nextPage = false;
		$previousPage = false;
		$itemPages = $item->getItemPages();
		if ($itemPages)
		{
			if ($this->options()->xaScViewFullItem)
			{
				$isFullView = $this->filter('full', 'bool');
			}
		
			if (!$isFullView) // prepare data for previous/next pages navigation!
			{
				$nextPage = $item->getNextPage($itemPages);
				$previousPage = $item->getPreviousPage($itemPages);
			}
		
			$item->page_count = $item->page_count + 1;  // this counts the item, plus the additional pages to get the correct count for a multi page item!
		}
		
		$nextSeriesPart = false;
		$previousSeriesPart = false;
		$seriesToc = $item->getSeriesToc();
		if ($seriesToc)
		{
			$nextSeriesPart = $item->getNextSeriesPart($seriesToc);
			$previousSeriesPart = $item->getPreviousSeriesPart($seriesToc);
		}
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('sc_item', $item->item_id);
		
		$viewParams = [
			'item' => $item,
			'category' => $category,
				
			'section' => $section,
			'sectionId' => $sectionId,
			'sectionTitle' => $sectionTitle,
				
			'itemPages' => $itemPages,
			'nextPage' => $nextPage,
			'previousPage' => $previousPage,
			
			'seriesToc' => $seriesToc,
			'nextSeriesPart' => $nextSeriesPart,
			'previousSeriesPart' => $previousSeriesPart,
		];
	
		return $this->view('XenAddons\Showcase:Item\Section', 'xa_sc_item_section', $viewParams);
	}
	
	public function actionField(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());

		if (!$item->canViewFullItem())
		{
			return $this->redirect($this->buildLink('showcase', $item));
		}
		
		$fieldId = $this->filter('field', 'str');
		$tabFields = $item->getExtraFieldTabs();

		if (!isset($tabFields[$fieldId]))
		{
			return $this->redirect($this->buildLink('showcase', $item));
		}

		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $item->custom_fields;
		$definition = $fieldSet->getDefinition($fieldId);
		$fieldValue = $fieldSet->getFieldValue($fieldId);
		
		$itemRepo = $this->getItemRepo();
		$itemRepo->markItemReadByVisitor($item);
		
		// Only log views to non contributors (contributors include Owner, Co-Owners and Contributors)
		if (!$item->isContributor())
		{
			$itemRepo->logItemView($item);
		}
		
		$isFullView = false;
		$nextPage = false;
		$previousPage = false;
		$itemPages = $item->getItemPages();
		if ($itemPages)
		{
			if ($this->options()->xaScViewFullItem)
			{
				$isFullView = $this->filter('full', 'bool');
			}
		
			if (!$isFullView) // prepare data for previous/next pages navigation!
			{
				$nextPage = $item->getNextPage($itemPages);
				$previousPage = $item->getPreviousPage($itemPages);
			}
		
			$item->page_count = $item->page_count + 1;  // this counts the item overview page, plus the additional pages to get the correct count for a multi page item!
		}
		
		$nextSeriesPart = false;
		$previousSeriesPart = false;
		$seriesToc = $item->getSeriesToc();
		if ($seriesToc)
		{
			$nextSeriesPart = $item->getNextSeriesPart($seriesToc);
			$previousSeriesPart = $item->getPreviousSeriesPart($seriesToc);
		}
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('sc_item', $item->item_id);

		$viewParams = [
			'item' => $item,
			'category' => $item->Category,

			'fieldId' => $fieldId,
			'fieldDefinition' => $definition,
			'fieldValue' => $fieldValue,
			
			'itemPages' => $itemPages,
			'nextPage' => $nextPage,
			'previousPage' => $previousPage,
				
			'seriesToc' => $seriesToc,
			'nextSeriesPart' => $nextSeriesPart,
			'previousSeriesPart' => $previousSeriesPart,
		];
		
		return $this->view('XenAddons\Showcase:Item\Field', 'xa_sc_item_field', $viewParams);
	}
	
	public function actionMap(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());
	
		if ($this->options()->xaScLocationDisplayType != 'map_own_tab'
			|| !$this->options()->xaScGoogleMapsEmbedApiKey
			|| !$item->Category->allow_location
			|| !$item->location
			|| !$item->canViewItemMap()
		)
		{
			return $this->redirect($this->buildLink('showcase', $item));
		}
	
		$viewParams = [
			'item' => $item,
			'category' => $item->Category,
		];
		return $this->view('XenAddons\Showcase:Item\Map', 'xa_sc_item_map', $viewParams);
	}
	
	public function actionMapOverlay(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());
	
		if (!$this->options()->xaScGoogleMapsEmbedApiKey
			|| !$item->Category->allow_location
			|| !$item->location
			|| !$item->canViewItemMap()
		)
		{
			return $this->noPermission();
		}
	
		$viewParams = [
			'item' => $item,
			'category' => $item->Category,
		];
		return $this->view('XenAddons\Showcase:Item\MapOverlay', 'xa_sc_item_map_overlay', $viewParams);
	}
	
	public function actionGallery(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());

		$updatesImages = $this->getItemUpdateRepo()->getUpdatesImagesForItem($item);
		
		if ($this->options()->xaScIncludeReviewsImages)
		{
			$reviewsImages = $this->getRatingRepo()->getReviewsImagesForItem($item, true);
		}
		else
		{
			$reviewsImages = $this->em()->getEmptyCollection();
		}
		
		$commentsFetchType = $this->options()->xaScIncludeCommentsImagesInGallery;
		if ($item->comment_count && $commentsFetchType && $commentsFetchType != 'disabled')
		{
			$commentsImages = $this->getCommentRepo()->getCommentsImagesForItemGallery($item, $commentsFetchType);
		}
		else
		{
			$commentsImages = $this->em()->getEmptyCollection();
		}
		
		$postsFetchType = $this->options()->xaScIncludePostsImagesInGallery;
		if ($item->Discussion && $postsFetchType && $postsFetchType != 'disabled')
		{
			$postsImages = $this->getItemRepo()->getPostsImagesForItemGallery($item, $postsFetchType);
		}
		else
		{
			$postsImages = $this->em()->getEmptyCollection();
		}
		
		if (!$item->hasImageAttachments() 
			&& !count($updatesImages) 
			&& !count($reviewsImages) 
			&& !count($commentsImages) 
			&& !count($postsImages)
		)
		{
			return $this->redirect($this->buildLink('showcase', $item));
		}
	
		$viewParams = [
			'item' => $item,
			'category' => $item->Category,
			'updatesImages' => $updatesImages,
			'reviewsImages' => $reviewsImages,
			'commentsImages' => $commentsImages,
			'postsImages' => $postsImages
		];
		return $this->view('XenAddons\Showcase:Item\Gallery', 'xa_sc_item_gallery', $viewParams);
	}	
	
	public function actionUpdates(ParameterBag $params)
	{
		$this->assertNotEmbeddedImageRequest();
	
		if (!$params->item_id)
		{
			return $this->redirectPermanently($this->buildLink('showcase/latest-updates'));
		}
		
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());

		if (!$item->canViewUpdates($error))
		{
			return $this->noPermission($error);
		} 
		
		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $this->repository('XenAddons\Showcase:Item');
		$itemRepo->markItemReadByVisitor($item);
		
		// Only log views to non contributors (contributors include Owner, Co-Owners and Contributors)
		if (!$item->isContributor())
		{
			$itemRepo->logItemView($item);
		}
	
		$page = $this->filterPage();
		$perPage = $this->options()->xaScUpdatesPerPage;
	
		/** @var \XenAddons\Showcase\Repository\ItemUpdate $updateRepo */
		$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');
		$updateFinder = $updateRepo->findUpdatesForItem($item);
	
		$total = $item->real_update_count;
		if (!$total)
		{
			return $this->redirect($this->buildLink('showcase', $item));
		}
	
		$this->assertValidPage($page, $perPage, $total, 'showcase/updates', $item);
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/updates', $item, $page));
	
		$updateFinder->limitByPage($page, $perPage);
		$updates = $updateFinder->fetch();
	
		$updates = $updateRepo->addRepliesToItemUpdates($updates);
		
		/** @var \XF\Repository\Attachment $attachmentRepo */
		$attachmentRepo = \XF::repository('XF:Attachment');
		$attachmentRepo->addAttachmentsToContent($updates, 'sc_update');
		
		$canInlineModUpdates = false;
		foreach ($updates AS $update)
		{
			if ($update->canUseInlineModeration())
			{
				$canInlineModUpdates = true;
				break;
			}
		}
	
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('sc_update', $updates->keys());
	
		$viewParams = [
			'item' => $item,
			'updates' => $updates,
				
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			
			'canInlineModUpdates' => $canInlineModUpdates
		];
		return $this->view('XenAddons\Showcase:Item\Updates', 'xa_sc_item_updates', $viewParams);
	}

	public function actionRatings(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		if (!$item->canManageRatings($error))
		{
			return $this->noPermission($error);
		}
			
		$page = $this->filterPage();
		$perPage = 20;
	
		/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
		$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
		$ratingFinder = $ratingRepo->findRatingsInItem($item);
	
		$total = $ratingFinder->total();
		if (!$total)
		{
			return $this->redirect($this->buildLink('showcase', $item));
		}
	
		$this->assertValidPage($page, $perPage, $total, 'showcase/ratings', $item);
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/ratings', $item, $page));
	
		$ratingFinder->limitByPage($page, $perPage);
		$ratings = $ratingFinder->fetch();
	
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('sc_rating', $ratings->keys());
		
		$viewParams = [
			'item' => $item,
			'category' => $item->Category,
			'ratings' => $ratings,
		];
	
		return $this->view('XenAddons\Showcase:Item\Ratings', 'xa_sc_item_ratings', $viewParams);
	}
	
	public function actionReviews(ParameterBag $params)
	{
		if (!$params->item_id)
		{
			return $this->redirectPermanently($this->buildLink('showcase/latest-reviews'));
		}
		
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());
		
		if (!$item->canViewReviews($error))
		{
			return $this->noPermission($error);
		}

		$this->assertCanonicalUrl($this->buildLink('showcase/reviews', $item));

		$reviewId = $this->filter('rating_id', 'uint');
		if ($reviewId)
		{
			/** @var \XenAddons\Showcase\Entity\ItemRating|null $review */
			$review = $this->em()->find('XenAddons\Showcase:ItemRating', $reviewId);
			if (!$review || $review->item_id != $item->item_id || !$review->is_review)
			{
				return $this->noPermission();
			}
			if (!$review->canView($error))
			{
				return $this->noPermission($error);
			}

			return $this->redirectPermanently($this->buildLink('showcase/review', $review));
		}

		$page = $this->filterPage();
		$perPage = $this->options()->xaScReviewsPerPage;

		/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
		$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');
		$reviewFinder = $ratingRepo->findReviewsInItem($item);

		$filters = $this->getReviewFilterInput();
		$this->applyReviewFilters($reviewFinder, $filters);
		
		if (!$filters)
		{
			$total = $item->real_review_count;
			if (!$total)
			{
				return $this->redirect($this->buildLink('showcase', $item));
			}
		}
		else
		{
			$total = $reviewFinder->total();
		}

		$this->assertValidPage($page, $perPage, $total, 'showcase/reviews', $item);
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/reviews', $item, $page));

		$reviewFinder->with('full')->limitByPage($page, $perPage);
		$reviews = $reviewFinder->fetch();
		
		$reviews = $ratingRepo->addRepliesToItemRatings($reviews);

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
		
		$viewParams = [
			'item' => $item,
			'reviews' => $reviews,

			'filters' => $filters,
			'reviewTabs' => $this->getReviewTabs($filters, $effectiveOrder),
			'effectiveOrder' => $effectiveOrder,
			
			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			
			'canInlineModReviews' => $canInlineModReviews
		];
		return $this->view('XenAddons\Showcase:Item\Reviews', 'xa_sc_item_reviews', $viewParams);
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
	
	public function actionReviewsFilters(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		$filters = $this->getReviewFilterInput();
	
		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink('showcase/reviews', $item, $filters));
		}
	
		$viewParams = [
			'item' => $item,
			'filters' => $filters
		];
		return $this->view('XenAddons\Showcase:Item\ReviewsFilters', 'xa_sc_item_reviews_filters', $viewParams);
	}
	
	protected function getReviewFilterInput()
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
	
			if ($input['order'] != 'rating_date' || $input['direction'] != 'desc')
			{
				$filters['order'] = $input['order'];
				$filters['direction'] = $input['direction'];
			}
		}
	
		return $filters;
	}
	
	protected function getAvailableReviewSorts()
	{
		// maps [name of sort] => field in/relative to ItemRating entity
		return [
			'rating_date' => 'rating_date',
			'vote_score' => 'vote_score',
			'rating' => 'rating'
		];
	}
	
	protected function applyReviewFilters(\XenAddons\Showcase\Finder\ItemRating $reviewFinder, array $filters)
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
	}
	
	// used for multi-page items administration
	public function actionPages(ParameterBag $params)
	{
		$this->assertNotEmbeddedImageRequest();
	
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());
	
		if (!$item->canEdit($error))
		{
			return $this->noPermission($error);
		}
			
		/** @var \XenAddons\Showcase\Repository\ItemPage $itemPageRepo */
		$itemPageRepo = $this->repository('XenAddons\Showcase:ItemPage');
		$itemPages = $itemPageRepo->findPagesInItemManagePages($item)->with('full')->fetch();
	
		if ($itemPages)
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = \XF::repository('XF:Attachment');
			$attachmentRepo->addAttachmentsToContent($itemPages, 'sc_page');
		}
	
		$viewParams = [
			'item' => $item,
			'category' => $item->Category,
			'itemPages' => $itemPages,
		];
	
		return $this->view('XenAddons\Showcase:Item\Pages', 'xa_sc_item_pages', $viewParams);
	}	
	
	/**
	 * @param \XenAddons\Showcase\Entity\Item $item
	 *
	 * @return \XenAddons\Showcase\Service\Item\Rate
	 */
	protected function setupItemRate(\XenAddons\Showcase\Entity\Item $item)
	{
		/** @var \XenAddons\Showcase\Service\Item\Rate $rater */
		$rater = $this->service('XenAddons\Showcase:Item\Rate', $item);
		
		$input = $this->filter([
			'rating' => 'uint',
			'title' => 'str',
			'pros' => 'str',
			'cons' => 'str',
			'is_anonymous' => 'bool'
		]);

		$rater->setRating($input['rating']);
		$rater->setTitle($input['title']);
		$rater->setPros($input['pros']);
		$rater->setCons($input['cons']);
		$rater->setMessage($this->plugin('XF:Editor')->fromInput('message'));
		
		if ($item->Category->allow_anon_reviews && $input['is_anonymous'])
		{
			$rater->setIsAnonymous();
		}
		
		if ($item->Category->canUploadAndManageReviewImages())
		{
			$rater->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		if ($item->Category->autopost_review)
		{
			$rater->setPostThreadUpdate(true);
		}
		
		$customFields = $this->filter('custom_fields', 'array');
		$rater->setCustomFields($customFields);

		if ($item->canRatePreReg())
		{
			$rater->setIsPreRegAction(true);
		}
		
		return $rater;
	}

	public function actionRate(ParameterBag $params)
	{
		$visitorUserId = \XF::visitor()->user_id;

		$item = $this->assertViewableItem($params->item_id);
		
		$isPreRegRate = $item->canRatePreReg();
		
		if (!$item->canRate($error) && !$isPreRegRate)
		{
			return $this->noPermission($error);
		}
		
		$category = $item->Category;
		
		if ($this->isPost())
		{
			$rater = $this->setupItemRate($item);
			$rater->checkForSpam();
			
			if (!$rater->validate($errors))
			{
				return $this->error($errors);
			}
			
			if ($isPreRegRate)
			{
				/** @var \XF\ControllerPlugin\PreRegAction $preRegPlugin */
				$preRegPlugin = $this->plugin('XF:PreRegAction');
				return $preRegPlugin->actionPreRegAction(
					'XenAddons\Showcase:Item\Rate',
					$item,
					$this->getPreRegRateActionData($rater)
				);
			}

			$rating = $rater->save();

			$rater->sendNotifications();
			
			if ($rating->is_review)
			{	
				$visitor = \XF::visitor();
				
				/** @var \XenAddons\Showcase\Repository\ItemWatch $watchRepo */
				$watchRepo = $this->repository('XenAddons\Showcase:ItemWatch');
				$watchRepo->autoWatchScItem($item, $visitor);
			}
			else
			{
				// If this is a Rating Only (not a review), we always want to force a Rating Only to a visible state
				if ($rating->rating_state == 'moderated')
				{
					$rating->rating_state = 'visible';
					$rating->save();
				}
			}
			
			return $this->redirect($this->buildLink(
				$rating->is_review ? 'showcase/reviews' : 'showcase',
				$item
			));
		}
		else
		{
			$review = $item->getNewRating();
			
			if ($category && $category->canUploadAndManageReviewImages())
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('sc_rating', $category);
			}
			else
			{
				$attachmentData = null;
			}
			
			$viewParams = [
				'review' => $review,
				'item' => $item,
				'category' => $item->Category,
				'attachmentData' => $attachmentData,
			];
			return $this->view('XF:Item\Rate', 'xa_sc_item_rate', $viewParams);
		}
	}
	
	protected function getPreRegRateActionData(\XenAddons\Showcase\Service\Item\Rate $rater)
	{
		$rating = $rater->getRating();
	
		return [
			'title' => $rating->title,
			'rating' => $rating->rating,
			'message' => $rating->message,
			'pros' => $rating->pros,
			'cons' => $rating->cons,
			'custom_fields' => $rating->custom_fields->getFieldValues(),
			'is_anonymous' => $rating->is_anonymous
		];
	}
	
	public function actionReviewPreview(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$item = $this->assertViewableItem($params->item_id);
	
		$itemRater = $this->setupItemRate($item);
		if (!$itemRater->validate($errors))
		{
			return $this->error($errors);
		}
	
		$review = $itemRater->getRating();
	
		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');
	
		if ($item->Category && $item->Category->canUploadAndManageReviewImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_rating', $review, $tempHash);
			$attachments = $attachmentData['attachments'];
		}
	
		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$review->message, 'sc_rating', $review->User, $attachments, $item->canViewReviewImages()
		);
	}
	
	public function actionUpdatePreview(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$item = $this->assertViewableItem($params->item_id);
	
		$adder = $this->setupAddUpdate($item);
		if (!$adder->validate($errors))
		{
			return $this->error($errors);
		}
	
		$update = $adder->getUpdate();
	
		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');
	
		if ($item->Category && $item->Category->canUploadAndManageUpdateImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_update', $update, $tempHash);
			$attachments = $attachmentData['attachments'];
		}
	
		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$update->message, 'sc_update', $update->User, $attachments, $item->canViewUpdateImages()
		);
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\Item $item
	 *
	 * @return \XenAddons\Showcase\Service\Item\AddUpdate
	 */
	protected function setupAddUpdate(\XenAddons\Showcase\Entity\Item $item)
	{
		/** @var \XenAddons\Showcase\Service\Item\AddUpdate $adder */
		$adder = $this->service('XenAddons\Showcase:Item\AddUpdate', $item);
	
		$adder->setTitle($this->filter('title', 'str'));
	
		$adder->setMessage($this->plugin('XF:Editor')->fromInput('message'));
		
		$customFields = $this->filter('custom_fields', 'array');
		$adder->setCustomFields($customFields);
		
		if ($item->Category->canUploadAndManageUpdateImages())
		{
			$adder->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}

		if ($item->Category->autopost_update)
		{
			$adder->setPostThreadUpdate(true);
		}
		
		return $adder;
	}
	
	public function actionAddUpdate(ParameterBag $params)
	{
		$visitor = \XF::visitor();
	
		$item = $this->assertViewableItem($params->item_id);
	
		if (!$item->canAddUpdate($error))
		{
			return $this->noPermission($error);
		}
	
		$category = $item->Category;
	
		if ($this->isPost())
		{
			$adder = $this->setupAddUpdate($item);
	
			$adder->checkForSpam();
	
			if (!$adder->validate($errors))
			{
				return $this->error($errors);
			}
	
			$update = $adder->save();
	
			$adder->sendNotifications();
	
			/** @var \XenAddons\Showcase\Repository\ItemWatch $watchRepo */
			$watchRepo = $this->repository('XenAddons\Showcase:ItemWatch');
			$watchRepo->autoWatchScItem($item, $visitor);
	
			return $this->redirect($this->buildLink('showcase/updates', $item));
		}
		else
		{
			$update = $item->getNewUpdate();
	
			if ($category && $category->canUploadAndManageUpdateImages())
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('sc_update', $item);
			}
			else
			{
				$attachmentData = null;
			}
	
			$viewParams = [
				'update' => $update,
				'item' => $item,
				'category' => $category,
				'attachmentData' => $attachmentData,
			];
			return $this->view('XenAddons\Showcase:Item\AddUpdate', 'xa_sc_item_add_update', $viewParams);
		}
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\Item $item
	 *
	 * @return \XenAddons\Showcase\Service\Item\AddPage
	 */
	protected function setupAddPage(\XenAddons\Showcase\Entity\Item $item)
	{
		/** @var \XenAddons\Showcase\Service\Item\AddPage $pageAdder */
		$pageAdder = $this->service('XenAddons\Showcase:Item\AddPage', $item);
	
		$pageAdder->setTitle($this->filter('title', 'str'));
		$pageAdder->setMessage($this->plugin('XF:Editor')->fromInput('message'));
	
		$basicFields = $this->filter([
			'og_title' => 'str',
			'meta_title' => 'str',
			'display_order' => 'int',
			'depth' => 'int',
			'page_state' => 'str',
			'cover_image_caption' => 'str',
			'cover_image_above_page' => 'bool',
			'display_byline' => 'bool',
			'description' => 'str',
			'meta_description' => 'str',
		]);
		$pageAdder->getPage()->bulkSet($basicFields);
	
		if ($item->Category->canUploadAndManagePageAttachments())
		{
			$pageAdder->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
	
		return $pageAdder;
	}
	
	public function actionAddPage(ParameterBag $params)
	{
		$visitorUserId = \XF::visitor()->user_id;
	
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canAddPage($error))
		{
			return $this->noPermission($error);
		}
	
		$category = $item->Category;
	
		if ($this->isPost())
		{
			$pageAdder = $this->setupAddPage($item);
	
			if (!$pageAdder->validate($errors))
			{
				return $this->error($errors);
			}
	
			$page = $pageAdder->save();
	
			$pageAdder->sendNotifications();
	
			if ($page->page_state == 'draft')
			{
				return $this->redirect($this->buildLink('showcase/pages', $item));
			}
			else
			{
				return $this->redirect($this->buildLink('showcase/page', $page));
			}
		}
		else
		{
			if ($category && $category->canUploadAndManagePageAttachments())
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('sc_page', $category);
			}
			else
			{
				$attachmentData = null;
			}
	
			$page = $item->getNewPage();
	
			$viewParams = [
				'page' => $page,
				'item' => $item,
				'category' => $item->Category,
				'attachmentData' => $attachmentData,
			];
			return $this->view('XenAddons\Showcase:Item\AddPage', 'xa_sc_item_add_page', $viewParams);
		}
	}
	
	public function actionPagePreview(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$item = $this->assertViewableItem($params->item_id);
	
		$pageAdder = $this->setupAddPage($item);
		if (!$pageAdder->validate($errors))
		{
			return $this->error($errors);
		}
	
		$page = $pageAdder->getPage();
	
		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');
	
		if ($item->Category && $item->Category->canUploadAndManagePageAttachments())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_page', $page, $tempHash);
			$attachments = $attachmentData['attachments'];
		}
	
		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$page->message, 'sc_page', $item->User, $attachments, $item->canViewPageAttachments()
		);
	}
	
	/**
	* @param \XenAddons\Showcase\Entity\SeriesItem $series
	 *
	* @return \XenAddons\Showcase\Service\Series\AddSeriesPart
	*/
	protected function setupAddSeriesPart(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		/** @var \XenAddons\Showcase\Service\Series\AddSeriesPart $seriesPartAdder */
		$seriesPartAdder = $this->service('XenAddons\Showcase:Series\AddSeriesPart', $series);
	
		$basicFields = $this->filter([
			'display_order' => 'int',
			'item_id' => 'int'
		]);
	
		$seriesPartAdder->getSeriesPart()->bulkSet($basicFields);
	
		return $seriesPartAdder;
	}
	
	protected function finalizeAddSeriesPart(\XenAddons\Showcase\Service\Series\AddSeriesPart $seriesPartAdder)
	{
		$seriesPartAdder->sendNotifications();
	
		$seriesPart = $seriesPartAdder->getSeriesPart();
	}
	
	public function actionAddToSeries(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canAddItemToSeries($error))
		{
			return $this->noPermission($error);
		}
	
		$visitor = \XF::visitor();
	
		if ($this->isPost())
		{
			if ($seriesUrl = $this->filter('series_url', 'string'))
			{
				$series = $this->repository('XenAddons\Showcase:Series')->getSeriesFromUrl($seriesUrl, 'public', $error);
	
				if ($error)
				{
					return $this->error($error);
				}
	
				$series = $this->assertViewableSeries($series->series_id);
			}
			else
			{
				$series = $this->assertViewableSeries($this->filter('series_id', 'int'));
			}
	
			if (!$series->canAddItemToSeries($error))
			{
				return $this->error($error);
			}
			
			$creator = $this->setupAddSeriesPart($series);
		
			if (!$creator->validate($errors))
			{
				return $this->error($errors);
			}
	
			$seriesPart = $creator->save();
				
			$this->finalizeAddSeriesPart($creator);
				
			// We probably want to stay on the item, so lets return to the item overview in this case...
			return $this->redirect($this->buildLink('showcase', $item));
	
			// TODO maybe add an option on the form to let the viewing user decide whether to return to the item overview or take them to the series?
			//return $this->redirect($this->buildLink('showcase/series', $series));
		}
		else
		{
			/** @var \XenAddons\Showcase\Repository\Series $seriesRepo */
			$seriesRepo = $this->getSeriesRepo();
			$seriesFinder = $seriesRepo->findSeriesForSelectList($visitor->user_id)
				->setDefaultOrder('last_part_date', 'desc');
				
			$series = $seriesFinder->fetch(50);
	
			$communitySeriesFinder = $seriesRepo->findCommunitySeriesForSelectList()
				->setDefaultOrder('last_part_date', 'desc');
	
			$communitySeries = $communitySeriesFinder->fetch(50);
	
			foreach ($communitySeries AS $key => $communitySeriesItem)
			{
				if (!$communitySeriesItem->canAddItemToSeries())
				{
					unset($communitySeries[$key]);
				}
			}
	
			$viewParams = [
				'item' => $item,
				'series' => $series,
				'communitySeries' => $communitySeries,
			];
			return $this->view('XenAddons\Showcase:Series\AddToSeries', 'xa_sc_item_add_to_series', $viewParams);
		}
	}
	
	public function actionRemoveFromSeries(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		if(!$item->isInSeries())
		{
			return $this->noPermission($error);
		}
	
		$seriesPart = $this->assertViewableSeriesPart($item->series_part_id);
		if (!$seriesPart->canRemove($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			/** @var \XenAddons\Showcase\Service\SeriesPart\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:SeriesPart\Deleter', $seriesPart);
	
			$deleter->delete();
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'seriesPart' => $seriesPart,
		 		'series' => $seriesPart->Series,
				'item' => $item,
			];
			return $this->view('XenAddons\Showcase:Item\RemoveFromSeries', 'xa_sc_item_remove_from_series', $viewParams);
		}
	}
	
	public function actionJoinContributorsTeam(ParameterBag $params): AbstractReply
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canJoinContributorsTeam($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			/** @var \XenAddons\Showcase\Service\Item\ContributorsManager $contributorsManager */
			$contributorsManager = $this->service('XenAddons\Showcase:Item\ContributorsManager', $item);
				
			$contributorsManager->addSelfJoinContributor(\XF::visitor());
	
			if (!$contributorsManager->validate($errors))
			{
				return $this->error($errors);
			}
	
			$contributorsManager->save();
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
	
		$viewParams = [
			'item' => $item,
			'category' => $item->Category,
		];
		return $this->view('XenAddons\Showcase:Item\JoinContributorsTeam', 'xa_sc_item_join_contributors_team', $viewParams);
	}
	
	public function actionLeaveContributorsTeam(ParameterBag $params): AbstractReply
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canLeaveContributorsTeam($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			/** @var \XenAddons\Showcase\Service\Item\ContributorsManager $contributorsManager */
			$contributorsManager = $this->service(
				'XenAddons\Showcase:Item\ContributorsManager',
				$item
			);
			$contributorsManager->leaveContributorsTeam(\XF::visitor());
	
			if (!$contributorsManager->validate($errors))
			{
				return $this->error($errors);
			}
	
			$contributorsManager->save();
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
	
		$category = $item->Category;
	
		$viewParams = [
			'item' => $item,
			'category' => $category,
		];
		return $this->view('XenAddons\Showcase:Item\LeaveContributorsTeam', 'xa_sc_item_leave_contributors_team', $viewParams);
	}
	
	public function actionManageContributors(ParameterBag $params): AbstractReply
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canManageContributors($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$input = $this->filter([
				'add_co_owners' => 'str',
				'add_contributors' => 'str',
				'remove_contributors' => 'array-uint'
			]);
	
			/** @var \XenAddons\Showcase\Service\Item\ContributorsManager $contributorsManager */
			$contributorsManager = $this->service('XenAddons\Showcase:Item\ContributorsManager', $item);
	
			if ($item->canAddCoOwners())
			{
				$contributorsManager->addCoOwners($input['add_co_owners']);
			}
				
			if ($item->canAddContributors())
			{
				$contributorsManager->addContributors($input['add_contributors']);
			}
	
			if ($item->canRemoveContributors())
			{
				$contributorsManager->removeContributors($input['remove_contributors']);
			}
	
			if (!$contributorsManager->validate($errors))
			{
				return $this->error($errors);
			}
	
			$contributorsManager->save();
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
	
		$category = $item->Category;
		$contributors = $item->Contributors;
		$maxContributors = $item->getMaxContributorCount();
		
		$viewParams = [
			'item' => $item,
			'category' => $category,
			'contributors' => $contributors,
			'maxContributors' => $maxContributors,
		];
		return $this->view('XenAddons\Showcase:Item\ManageContributors', 'xa_sc_item_manage_contributors', $viewParams);
	}	

	/**
	 * @param \XenAddons\Showcase\Entity\Item $item
	 *
	 * @return \XenAddons\Showcase\Service\Item\Edit
	 */
	protected function setupItemEdit(\XenAddons\Showcase\Entity\Item $item)
	{
		/** @var \XenAddons\Showcase\Service\Item\Edit $editor */
		$editor = $this->service('XenAddons\Showcase:Item\Edit', $item);

		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId != $item->prefix_id && !$item->Category->isPrefixUsable($prefixId))
		{
			$prefixId = 0; // not usable, just blank it out
		}
		$editor->setPrefix($prefixId);

		$editor->setTitle($this->filter('title', 'str'));
		
		$editor->setMessage($this->plugin('XF:Editor')->fromInput('message'));
		$editor->setMessageS2($this->plugin('XF:Editor')->fromInput('message_s2'));
		$editor->setMessageS3($this->plugin('XF:Editor')->fromInput('message_s3'));
		$editor->setMessageS4($this->plugin('XF:Editor')->fromInput('message_s4'));
		$editor->setMessageS5($this->plugin('XF:Editor')->fromInput('message_s5'));
		$editor->setMessageS6($this->plugin('XF:Editor')->fromInput('message_s6'));
		
		$customFields = $this->filter('custom_fields', 'array');
		$editor->setCustomFields($customFields);
		
		$editor->setLocation($this->filter('location', 'str'));

		if ($item->Category->allow_author_rating && $item->canSetAuthorRating())
		{
			$editor->setAuthorRating($this->filter('author_rating', 'float'));
		}
		
		$canStickUnstick = $item->canStickUnstick($error);
		if ($canStickUnstick)
		{
			$editor->setSticky($this->filter('sticky', 'bool'));
		}
		
		$basicFields = $this->filter([
			'og_title' => 'str',
			'meta_title' => 'str',
			'description' => 'str',
			'meta_description' => 'str',
			'comments_open' => 'bool',
			'ratings_open' => 'bool'
		]);
		$item->edit_date = time();
		$item->bulkSet($basicFields);
		
		if ($item->Category->canUploadAndManageItemAttachments())
		{
			$editor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		if ($this->filter('post_as_update', 'bool'))
		{
			$editor->setPostThreadUpdate(true, $this->filter('update_message', 'str'));
		}
		
		if ($this->filter('author_alert', 'bool') && $item->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}

		return $editor;
	}

	public function actionEdit(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canEdit($error))
		{
			return $this->noPermission($error);
		}

		$category = $item->Category;

		if ($this->isPost())
		{
			$editor = $this->setupItemEdit($item);
			$editor->checkForSpam();

			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}

			$editor->save();
			
			if ($this->filter('post_as_update', 'bool'))
			{
				$editor->sendNotifications();
			}
			
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			if ($category && $category->canUploadAndManageItemAttachments())
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('sc_item', $item);
			}
			else
			{
				$attachmentData = null;
			}

			$viewParams = [
				'item' => $item,
				'category' => $category,
				'attachmentData' => $attachmentData,
				'prefixes' => $category->getUsablePrefixes($item->prefix_id)
			];
			return $this->view('XF:Item\Edit', 'xa_sc_item_edit', $viewParams);
		}
	}

	public function actionPreview(ParameterBag $params)
	{
		$this->assertPostOnly();

		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canEdit($error))
		{
			return $this->noPermission($error);
		}

		$editor = $this->setupItemEdit($item);

		if (!$editor->validate($errors))
		{
			return $this->error($errors);
		}

		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');

		$category = $item->Category;
		if ($category && $category->canUploadAndManageItemAttachments())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_item', $item, $tempHash);
			$attachments = $attachmentData['attachments'];
		}

		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$item->message, 'sc_item', $item->User, $attachments, $item->canViewItemAttachments()
		);
	}

	/**
	 * @param \XenAddons\Showcase\Entity\Item $item
	 * @param \XenAddons\Showcase\Entity\Category $category
	 *
	 * @return \XenAddons\Showcase\Service\Item\Move
	 */
	protected function setupItemMove(\XenAddons\Showcase\Entity\Item $item, \XenAddons\Showcase\Entity\Category $category)
	{
		$options = $this->filter([
			'notify_watchers' => 'bool',
			'author_alert' => 'bool',
			'author_alert_reason' => 'str',
			'prefix_id' => 'uint'
		]);

		/** @var \XenAddons\Showcase\Service\Item\Move $mover */
		$mover = $this->service('XenAddons\Showcase:Item\Move', $item);

		if ($options['author_alert'])
		{
			$mover->setSendAlert(true, $options['author_alert_reason']);
		}

		if ($options['notify_watchers'])
		{
			$mover->setNotifyWatchers();
		}

		if ($options['prefix_id'] !== null)
		{
			$mover->setPrefix($options['prefix_id']);
		}

		$mover->addExtraSetup(function($item, $category)
		{
			$item->title = $this->filter('title', 'str');
		});

		return $mover;
	}

	public function actionMove(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canMove($error))
		{
			return $this->noPermission($error);
		}

		$category = $item->Category;

		if ($this->isPost())
		{
			$targetCategoryId = $this->filter('target_category_id', 'uint');

			/** @var \XenAddons\Showcase\Entity\Category $targetCategory */
			$targetCategory = $this->app()->em()->find('XenAddons\Showcase:Category', $targetCategoryId);
			if (!$targetCategory || !$targetCategory->canView())
			{
				return $this->error(\XF::phrase('requested_category_not_found'));
			}

			$this->setupItemMove($item, $targetCategory)->move($targetCategory);

			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$categoryRepo = $this->getCategoryRepo();
			$categories = $categoryRepo->getViewableCategories();

			$viewParams = [
				'item' => $item,
				'category' => $category,
				'prefixes' => $category->getUsablePrefixes(),
				'categoryTree' => $categoryRepo->createCategoryTree($categories)
			];
			return $this->view('XenAddons\Showcase:Item\Move', 'xa_sc_item_move', $viewParams);
		}
	}

	public function actionTags(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canEditTags($error))
		{
			return $this->noPermission($error);
		}

		/** @var \XF\Service\Tag\Changer $tagger */
		$tagger = $this->service('XF:Tag\Changer', 'sc_item', $item);

		if ($this->isPost())
		{
			$tagger->setEditableTags($this->filter('tags', 'str'));
			if ($tagger->hasErrors())
			{
				return $this->error($tagger->getErrors());
			}

			$tagger->save();

			if ($this->filter('_xfInlineEdit', 'bool'))
			{
				$viewParams = [
					'item' => $item
				];
				$reply = $this->view('XenAddons\Showcase:Item\TagsInline', 'xa_sc_item_tags_list', $viewParams);
				$reply->setJsonParam('message', \XF::phrase('your_changes_have_been_saved'));
				return $reply;
			}
			else
			{
				return $this->redirect($this->buildLink('showcase', $item));
			}
		}
		else
		{
			$grouped = $tagger->getExistingTagsByEditability();

			$viewParams = [
				'item' => $item,
				'category' => $item->Category,
				'editableTags' => $grouped['editable'],
				'uneditableTags' => $grouped['uneditable']
			];
			return $this->view('XenAddons\Showcase:Item\Tags', 'xa_sc_item_tags', $viewParams);
		}
	}

	public function actionWatch(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canWatch($error))
		{
			return $this->noPermission($error);
		}

		$visitor = \XF::visitor();

		if ($this->isPost())
		{
			if ($this->filter('stop', 'bool'))
			{
				$action = 'delete';
				$config = [];
			}
			else
			{
				$action = 'watch';
				$config = [
					'email_subscribe' => $this->filter('email_subscribe', 'bool')
				];
			}

			/** @var \XenAddons\Showcase\Repository\ItemWatch $watchRepo */
			$watchRepo = $this->repository('XenAddons\Showcase:ItemWatch');
			$watchRepo->setWatchState($item, $visitor, $action, $config);

			// Check to see if the item has an associated discussion thread and auto watch it
			if ($item->Discussion)
			{
				if ($action == 'watch')
				{
					// This needs to be based on the viewing users auto watch preferences as we don't want to force things they do not want!
					$this->repository('XF:ThreadWatch')->autoWatchThread($item->Discussion, $visitor, false); // set to false for 'interaction_watch_state' vs 'creation_watch_state'
				}
				elseif ($action == 'delete')
				{
					$this->repository('XF:ThreadWatch')->setWatchState($item->Discussion, $visitor, $action);
				}
			}
			
			$redirect = $this->redirect($this->buildLink('showcase', $item));
			$redirect->setJsonParam('switchKey', $action == 'delete' ? 'watch' : 'unwatch');
			return $redirect;
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'isWatched' => !empty($item->Watch[$visitor->user_id]),
				'category' => $item->Category
			];
			return $this->view('XenAddons\Showcase:Item\Watch', 'xa_sc_item_watch', $viewParams);
		}
	}

	public function actionReassign(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canReassign($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$user = $this->em()->findOne('XF:User', ['username' => $this->filter('username', 'str')]);
			if (!$user)
			{
				return $this->error(\XF::phrase('requested_user_not_found'));
			}

			/** @var \XenAddons\Showcase\Service\Item\Reassign $reassigner */
			$reassigner = $this->service('XenAddons\Showcase:Item\Reassign', $item);

			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}

			$reassigner->reassignTo($user);

			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XF:Item\Reassign', 'xa_sc_item_reassign', $viewParams);
		}
	}

	public function actionQuickFeature(ParameterBag $params)
	{
		$this->assertPostOnly();

		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canFeatureUnfeature($error))
		{
			return $this->error($error);
		}

		/** @var \XenAddons\Showcase\Service\Item\Feature $featurer */
		$featurer = $this->service('XenAddons\Showcase:Item\Feature', $item);

		if ($item->Featured)
		{
			$featurer->unfeature();
			$featured = false;
			$text = \XF::phrase('xa_sc_item_quick_feature');
		}
		else
		{
			$featurer->feature();
			$featured = true;
			$text = \XF::phrase('xa_sc_item_quick_unfeature');
		}

		$reply = $this->redirect($this->getDynamicRedirect());
		$reply->setJsonParams([
			'text' => $text,
			'featured' => $featured
		]);
		return $reply;
	}
	
	public function actionQuickStick(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canStickUnstick($error))
		{
			return $this->error($error);
		}
	
		/** @var \XenAddons\Showcase\Service\Item\Edit $editor */
		$editor = $this->service('XenAddons\Showcase:Item\Edit', $item);
	
		if ($item->sticky)
		{
			$editor->setSticky(false);
			$text = \XF::phrase('xa_sc_stick_item');
		}
		else
		{
			$editor->setSticky(true);
			$text = \XF::phrase('xa_sc_unstick_item');
		}
	
		if (!$editor->validate($errors))
		{
			return $this->error($errors);
		}
	
		$editor->save();
	
		if ($this->filter('_xfWithData', 'bool') && !$this->filter('_xfRedirect', 'str'))
		{
			$reply = $this->view('XenAddons\Showcase:Item\QuickStick');
			$reply->setJsonParams([
				'text' => $text,
				'sticky' => $item->sticky,
				'message' => \XF::phrase('redirect_changes_saved_successfully')
			]);
			return $reply;
		}
		else
		{
			return $this->redirect($this->getDynamicRedirect());
		}
	}
	
	public function actionBookmark(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		/** @var \XF\ControllerPlugin\Bookmark $bookmarkPlugin */
		$bookmarkPlugin = $this->plugin('XF:Bookmark');
	
		return $bookmarkPlugin->actionBookmark(
			$item, $this->buildLink('showcase/bookmark', $item)
		);
	}
	
	public function actionChangeDates(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canChangeDates($error))
		{
			return $this->noPermission($error);
		}
	
		$category = $item->Category;
	
		if ($this->isPost())
		{
			// TODO probably move this process into a service in a future version!
	
			$createDateInput = $this->filter([
				'item_create_date' => 'str',
				'item_create_hour' => 'int',
				'item_create_minute' => 'int',
				'item_timezone' => 'str'
			]);
	
			$lastUpdateDateInput = $this->filter([
				'item_last_update_date' => 'str',
				'item_last_update_hour' => 'int',
				'item_last_update_minute' => 'int'
			]);
	
			$tz = new \DateTimeZone($createDateInput['item_timezone']);
	
			$createDate = $createDateInput['item_create_date'];
			$createHour = $createDateInput['item_create_hour'];
			$createMinute = $createDateInput['item_create_minute'];
			$createDate = new \DateTime("$createDate $createHour:$createMinute", $tz);
			$createDate = $createDate->format('U');
	
			$lastUpdateDate = $lastUpdateDateInput['item_last_update_date'];
			$lastUpdateHour = $lastUpdateDateInput['item_last_update_hour'];
			$lastUpdateMinute = $lastUpdateDateInput['item_last_update_minute'];
			$lastUpdateDate = new \DateTime("$lastUpdateDate $lastUpdateHour:$lastUpdateMinute", $tz);
			$lastUpdateDate = $lastUpdateDate->format('U');
	
			if ($createDate > \XF::$time || $lastUpdateDate > \XF::$time)
			{
				return $this->error(\XF::phraseDeferred('xa_sc_can_not_change_date_into_the_future'));
			}
	
			$item->create_date = $createDate;
	
			// if the last update date is older then the create date, then use the create date to set the last update date!
			if ($lastUpdateDate < $createDate)
			{
				$item->last_update = $createDate;
			}
			else
			{
				$item->last_update = $lastUpdateDate;
			}
	
			$item->save();
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$visitor = \XF::visitor();
	
			$itemCreateDate = new \DateTime('@' . $item->create_date);
			$itemCreateDate->setTimezone(new \DateTimeZone($visitor->timezone));
	
			$itemLastUpdateDate = new \DateTime('@' . $item->last_update);
			$itemLastUpdateDate->setTimezone(new \DateTimeZone($visitor->timezone));
	
			$viewParams = [
				'item' => $item,
				'category' => $category,
	
				'itemCreateDate' => $itemCreateDate,
				'itemCreateHour' => $itemCreateDate->format('H'),
				'itemCreateMinute' => $itemCreateDate->format('i'),
	
				'itemLastUpdateDate' => $itemLastUpdateDate,
				'itemLastUpdateHour' => $itemLastUpdateDate->format('H'),
				'itemLastUpdateMinute' => $itemLastUpdateDate->format('i'),
	
				'hours' => $item->getHours(),
				'minutes' => $item->getMinutes(),
				'timeZones' => $this->app->data('XF:TimeZone')->getTimeZoneOptions()
			];
			return $this->view('XenAddons\Showcase:Item\ChangeDates', 'xa_sc_item_change_dates', $viewParams);
		}
	}	
		
	public function actionLockUnlockComments(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canLockUnlockComments($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			if ($item->comments_open)
			{
				$item->comments_open = 0;
				$item->save();
			}
			else
			{
				$item->comments_open = 1;
				$item->save();
			}
		
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XF:Item\LockUnlockComments', 'xa_sc_item_lock_unlock_comments', $viewParams);
		}
	}

	public function actionLockUnlockRatings(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canLockUnlockRatings($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			if ($item->ratings_open)
			{
				$item->ratings_open = 0;
				$item->save();
			}
			else
			{
				$item->ratings_open = 1;
				$item->save();
			}
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XF:Item\LockUnlockRatings', 'xa_sc_item_lock_unlock_ratings', $viewParams);
		}
	}	

	public function actionSetCoverImage(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canSetCoverImage($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$item->cover_image_id = $this->filter('attachment_id', 'int');
			$item->save();
			
			return $this->redirect($this->buildLink('showcase', $item));			
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XF:Item\SetCoverImage', 'xa_sc_item_set_cover_image', $viewParams);
		}		
	}
		
	public function actionDelete(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');

			if (!$item->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}

			/** @var \XenAddons\Showcase\Service\Item\Delete $deleter */
			$deleter = $this->service('XenAddons\Showcase:Item\Delete', $item);

			if ($this->filter('author_alert', 'bool'))
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}

			$deleter->delete($type, $reason);

			$this->plugin('XF:InlineMod')->clearIdFromCookie('sc_item', $item->item_id);

			return $this->redirect($this->buildLink('showcase/categories', $item->Category));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XF:Item\Delete', 'xa_sc_item_delete', $viewParams);
		}
	}

	public function actionUndelete(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canUndelete($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			if ($item->item_state == 'deleted')
			{
				$item->item_state = 'visible';
				$item->save();
			}

			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XF:Item\Undelete', 'xa_sc_item_undelete', $viewParams);
		}
	}

	public function actionApprove(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			/** @var \XenAddons\Showcase\Service\Item\Approve $approver */
			$approver = \XF::service('XenAddons\Showcase:Item\Approve', $item);
			$approver->setNotifyRunTime(1); // may be a lot happening
			$approver->approve();

			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XF:Item\Approve', 'xa_sc_item_approve', $viewParams);
		}
	}
	
	public function actionPublishDraft(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		
		if (!$item->canPublishDraft($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			/** @var \XenAddons\Showcase\Service\Item\PublishDraft $draftPublisher */
			$draftPublisher = \XF::service('XenAddons\Showcase:Item\PublishDraft', $item);
			$draftPublisher->setNotifyRunTime(1); // may be a lot happening
			$draftPublisher->publishDraft();
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XF:Item\PublishDraft', 'xa_sc_item_publish_draft', $viewParams);
		}
	}	

	public function actionPublishDraftScheduled(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		if (!$item->canPublishDraftScheduled($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$publishDateInput = $this->filter([
				'item_publish_date' => 'str',
				'item_publish_hour' => 'int',
				'item_publish_minute' => 'int',
				'item_timezone' => 'str'
			]);
	
			$tz = new \DateTimeZone($publishDateInput['item_timezone']);
	
			$publishDate = $publishDateInput['item_publish_date'];
			$publishHour = $publishDateInput['item_publish_hour'];
			$publishMinute = $publishDateInput['item_publish_minute'];
			$publishDate = new \DateTime("$publishDate $publishHour:$publishMinute", $tz);
			$publishDate = $publishDate->format('U');
	
			if ($publishDate <= \XF::$time)
			{
				// Throw error as can not set publish date into the past/present, only the future!
				return $this->error(\XF::phraseDeferred('xa_sc_scheduled_publish_date_must_be_set_into_the_future'));
			}
	
			$item->item_state = 'awaiting';
			$item->create_date = $publishDate;
			$item->edit_date = \XF::$time;
			$item->last_update = \XF::$time;
				
			$item->save();
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category,
	
				'hours' => $item->getHours(),
				'minutes' => $item->getMinutes(),
				'timeZones' => $this->app->data('XF:TimeZone')->getTimeZoneOptions()
			];
			return $this->view('XenAddons\Showcase:Item\PublishDraftScheduled', 'xa_sc_item_publish_draft_scheduled', $viewParams);
		}
	}
	
	public function actionChangeScheduledPublishDate(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		if (!$item->canChangeScheduledPublishDate($error))
		{
			return $this->noPermission($error);
		}
		
		$visitor = \XF::visitor();
	
		if ($this->isPost())
		{
			$publishDateInput = $this->filter([
				'item_publish_date' => 'str',
				'item_publish_hour' => 'int',
				'item_publish_minute' => 'int',
				'item_timezone' => 'str'
			]);
	
			$tz = new \DateTimeZone($publishDateInput['item_timezone']);
	
			$publishDate = $publishDateInput['item_publish_date'];
			$publishHour = $publishDateInput['item_publish_hour'];
			$publishMinute = $publishDateInput['item_publish_minute'];
			$publishDate = new \DateTime("$publishDate $publishHour:$publishMinute", $tz);
			$publishDate = $publishDate->format('U');
	
			if ($publishDate <= \XF::$time)
			{
				// Throw error as can not set publish date into the past/present, only the future!
				return $this->error(\XF::phraseDeferred('xa_sc_scheduled_publish_date_must_be_set_into_the_future'));
			}
	
			$item->create_date = $publishDate;
			$item->edit_date = \XF::$time;
			$item->last_update = \XF::$time;
	
			$item->save();
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$itemPublishDate = new \DateTime('@' . $item->create_date);
			$itemPublishDate->setTimezone(new \DateTimeZone($visitor->timezone));
	
			$viewParams = [
				'item' => $item,
				'category' => $item->Category,
	
				'itemPublishDate' => $itemPublishDate,
				'itemPublishHour' => $itemPublishDate->format('H'),
				'itemPublishMinute' => $itemPublishDate->format('i'),
	
				'hours' => $item->getHours(),
				'minutes' => $item->getMinutes(),
				'timeZones' => $this->app->data('XF:TimeZone')->getTimeZoneOptions()
			];
			return $this->view('XenAddons\Showcase:Item\ChangeScheduledPublishDate', 'xa_sc_item_change_scheduled_publish_date', $viewParams);
		}
	}
					
	public function actionIp(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		$breadcrumbs = $item->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Ip $ipPlugin */
		$ipPlugin = $this->plugin('XF:Ip');
		return $ipPlugin->actionIp($item, $breadcrumbs);
	}
	
	public function actionReport(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canReport($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'sc_item', $item,
			$this->buildLink('showcase/report', $item),
			$this->buildLink('showcase', $item)
		);
	}

	public function actionWarn(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		if (!$item->canWarn($error))
		{
			return $this->noPermission($error);
		}
	
		$breadcrumbs = $item->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'sc_item', $item,
			$this->buildLink('showcase/warn', $item),
			$breadcrumbs
		);
	}

	public function actionShare(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		/** @var \XF\ControllerPlugin\Share $sharePlugin */
		$sharePlugin = $this->plugin('XF:Share');
		return $sharePlugin->actionTooltip(
			$this->buildLink('canonical:showcase', $item),
			\XF::phrase('xa_sc_item_x', ['title' => $item->title]),
			\XF::phrase('xa_sc_share_this_item')
		);
	}

	public function actionSetBusinessHours(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canSetBusinessHours($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			if ($this->filter('unset_business_hours', 'bool'))
			{
				$business_hours = [];
			}
			else
			{
				$business_hours = $this->filter([
					'open_monday' => 'bool',
					'monday_open_hour' => 'str',
					'monday_open_minute' => 'str',
					'monday_close_hour' => 'str',
					'monday_close_minute' => 'str',
					'monday_open_hour_2' => 'str',
					'monday_open_minute_2' => 'str',
					'monday_close_hour_2' => 'str',
					'monday_close_minute_2' => 'str',
					'monday_open_hour_3' => 'str',
					'monday_open_minute_3' => 'str',
					'monday_close_hour_3' => 'str',
					'monday_close_minute_3' => 'str',
	
					'open_tuesday' => 'bool',
					'tuesday_open_hour' => 'str',
					'tuesday_open_minute' => 'str',
					'tuesday_close_hour' => 'str',
					'tuesday_close_minute' => 'str',
					'tuesday_open_hour_2' => 'str',
					'tuesday_open_minute_2' => 'str',
					'tuesday_close_hour_2' => 'str',
					'tuesday_close_minute_2' => 'str',
					'tuesday_open_hour_3' => 'str',
					'tuesday_open_minute_3' => 'str',
					'tuesday_close_hour_3' => 'str',
					'tuesday_close_minute_3' => 'str',
	
					'open_wednesday' => 'bool',
					'wednesday_open_hour' => 'str',
					'wednesday_open_minute' => 'str',
					'wednesday_close_hour' => 'str',
					'wednesday_close_minute' => 'str',
					'wednesday_open_hour_2' => 'str',
					'wednesday_open_minute_2' => 'str',
					'wednesday_close_hour_2' => 'str',
					'wednesday_close_minute_2' => 'str',
					'wednesday_open_hour_3' => 'str',
					'wednesday_open_minute_3' => 'str',
					'wednesday_close_hour_3' => 'str',
					'wednesday_close_minute_3' => 'str',

					'open_thursday' => 'bool',
					'thursday_open_hour' => 'str',
					'thursday_open_minute' => 'str',
					'thursday_close_hour' => 'str',
					'thursday_close_minute' => 'str',
					'thursday_open_hour_2' => 'str',
					'thursday_open_minute_2' => 'str',
					'thursday_close_hour_2' => 'str',
					'thursday_close_minute_2' => 'str',
					'thursday_open_hour_3' => 'str',
					'thursday_open_minute_3' => 'str',
					'thursday_close_hour_3' => 'str',
					'thursday_close_minute_3' => 'str',

					'open_friday' => 'bool',
					'friday_open_hour' => 'str',
					'friday_open_minute' => 'str',
					'friday_close_hour' => 'str',
					'friday_close_minute' => 'str',
					'friday_open_hour_2' => 'str',
					'friday_open_minute_2' => 'str',
					'friday_close_hour_2' => 'str',
					'friday_close_minute_2' => 'str',
					'friday_open_hour_3' => 'str',
					'friday_open_minute_3' => 'str',
					'friday_close_hour_3' => 'str',
					'friday_close_minute_3' => 'str',

					'open_saturday' => 'bool',
					'saturday_open_hour' => 'str',
					'saturday_open_minute' => 'str',
					'saturday_close_hour' => 'str',
					'saturday_close_minute' => 'str',
					'saturday_open_hour_2' => 'str',
					'saturday_open_minute_2' => 'str',
					'saturday_close_hour_2' => 'str',
					'saturday_close_minute_2' => 'str',
					'saturday_open_hour_3' => 'str',
					'saturday_open_minute_3' => 'str',
					'saturday_close_hour_3' => 'str',
					'saturday_close_minute_3' => 'str',

					'open_sunday' => 'bool',
					'sunday_open_hour' => 'str',
					'sunday_open_minute' => 'str',
					'sunday_close_hour' => 'str',
					'sunday_close_minute' => 'str',
					'sunday_open_hour_2' => 'str',
					'sunday_open_minute_2' => 'str',
					'sunday_close_hour_2' => 'str',
					'sunday_close_minute_2' => 'str',
					'sunday_open_hour_3' => 'str',
					'sunday_open_minute_3' => 'str',
					'sunday_close_hour_3' => 'str',
					'sunday_close_minute_3' => 'str',
						
					'business_timezone' => 'str',
				]);
			}
				
			$item->business_hours = $business_hours;
			$item->save();
	
			return $this->redirect($this->getDynamicRedirect($this->buildLink('showcase', $item), false));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category,
				
				'hours' => $item->getHours(),
				'minutes' => $item->getMinutes(),
				'timeZones' => $this->app->data('XF:TimeZone')->getTimeZoneOptions()
			];
	
			return $this->view('XenAddons\Showcase:Item\SetBusinessHours', 'xa_sc_item_set_business_hours', $viewParams);
		}
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\Item $item
	 *
	 * @return \XenAddons\Showcase\Service\Item\ReplyBan|null
	 */
	protected function setupItemReplyBan(\XenAddons\Showcase\Entity\Item $item)
	{
		$input = $this->filter([
			'username' => 'str',
			'ban_length' => 'str',
			'ban_length_value' => 'uint',
			'ban_length_unit' => 'str',

			'send_alert' => 'bool',
			'reason' => 'str'
		]);
	
		if (!$input['username'])
		{
			return null;
		}
	
		/** @var \XF\Entity\User $user */
		$user = $this->finder('XF:User')->where('username', $input['username'])->fetchOne();
		if (!$user)
		{
			throw $this->exception(
				$this->notFound(\XF::phrase('requested_user_x_not_found', ['name' => $input['username']]))
			);
		}
	
		/** @var \XenAddons\Showcase\Service\Item\ReplyBan $replyBanService */
		$replyBanService = $this->service('XenAddons\Showcase:Item\ReplyBan', $item, $user);
	
		if ($input['ban_length'] == 'temporary')
		{
			$replyBanService->setExpiryDate($input['ban_length_unit'], $input['ban_length_value']);
		}
		else
		{
			$replyBanService->setExpiryDate(0);
		}
	
		$replyBanService->setSendAlert($input['send_alert']);
		$replyBanService->setReason($input['reason']);
	
		return $replyBanService;
	}
	
	public function actionReplyBans(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canReplyBan($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$delete = $this->filter('delete', 'array-bool');
			$delete = array_filter($delete);
	
			$replyBanService = $this->setupItemReplyBan($item);
			if ($replyBanService)
			{
				if (!$replyBanService->validate($errors))
				{
					return $this->error($errors);
				}
	
				$replyBanService->save();
	
				// don't try to delete the record we just added
				unset($delete[$replyBanService->getUser()->user_id]);
			}
	
			if ($delete)
			{
				$replyBans = $item->ReplyBans;
				foreach (array_keys($delete) AS $userId)
				{
					if (isset($replyBans[$userId]))
					{
						$replyBans[$userId]->delete();
					}
				}
			}
	
			return $this->redirect($this->getDynamicRedirect($this->buildLink('showcase', $item), false));
		}
		else
		{
			/** @var \XenAddons\Showcase\Repository\ItemReplyBan $replyBanRepo */
			$replyBanRepo = $this->repository('XenAddons\Showcase:ItemReplyBan');
			$replyBanFinder = $replyBanRepo->findReplyBansForItem($item)->order('ban_date');
	
			$viewParams = [
				'item' => $item,
				'category' => $item->Category,
				'bans' => $replyBanFinder->fetch()
			];
			return $this->view('XenAddons\Showcase:Item\ReplyBans', 'xa_sc_item_reply_bans', $viewParams);
		}
	}	
	
	public function actionChangeThread(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canChangeDiscussionThread($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			/** @var \XenAddons\Showcase\Service\Item\ChangeDiscussion $changer */
			$changer = $this->service('XenAddons\Showcase:Item\ChangeDiscussion', $item);

			$threadAction = $this->filter('thread_action', 'str');
			
			if ($threadAction == 'create')
			{
				$changer->createDiscussion();
			}
			elseif ($threadAction == 'disconnect')
			{
				$changer->disconnectDiscussion();
			}
			else
			{
				$threadUrl = $this->filter('thread_url', 'str');
	
				if (!$changer->changeThreadByUrl($threadUrl, true, $error))
				{
					return $this->error($error);
				}
			}
	
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			$viewParams = [
				'item' => $item,
				'category' => $item->Category
			];
			return $this->view('XenAddons\Showcase:Item\ChangeThread', 'xa_sc_item_change_thread', $viewParams);
		}
	}
	
	public function actionConvertToThread(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canConvertToThread($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$nodeId = $this->filter('target_node_id', 'int');
	
			$forum = $this->app()->em()->find('XF:Forum', $nodeId);
			if (!$forum)
			{
				throw new \InvalidArgumentException("Invalid target forum ($nodeId)");
			}
				
			/** @var \XenAddons\Showcase\Service\Item\ConvertToThread $converter */
			$converter = $this->service('XenAddons\Showcase:Item\ConvertToThread', $item);
			$converter->setNewThreadTags($this->filter('tags', 'str'));
				
			$prefixId = $this->filter('prefix_id', 'int');
			
			if ($this->filter('author_alert', 'bool'))
			{
				$converter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
				
			$thread = $converter->convertToThread($forum, $prefixId);
				
			if ($thread)
			{
				/** @var \XenAddons\Showcase\Service\Item\Delete $deleter */
				$deleter = $this->service('XenAddons\Showcase:Item\Delete', $item);
				$deleter->delete('hard', 'Converted item to thread', true);
	
				return $this->redirect($this->buildLink('threads', $thread));
			}
				
			return $this->redirect($this->buildLink('showcase', $item));
		}
		else
		{
			/** @var \XF\Repository\Node $nodeRepo */
			$nodeRepo = $this->app()->repository('XF:Node');
			$nodes = $nodeRepo->getFullNodeList()->filterViewable();
				
			/** @var \XF\Repository\ThreadPrefix $prefixRepo */
			$prefixRepo = $this->repository('XF:ThreadPrefix');
			$availablePrefixes = $prefixRepo->findPrefixesForList()->fetch();
			$availablePrefixes = $availablePrefixes->pluckNamed('title', 'prefix_id');
			$prefixListData = $prefixRepo->getPrefixListData();
			
			/** @var \XF\Service\Tag\Changer $tagger */
			$tagger = $this->service('XF:Tag\Changer', 'sc_item', $item);
			$grouped = $tagger->getExistingTagsByEditability();
				
			$viewParams = [
				'item' => $item,
				'category' => $item->Category,
		
				'nodeTree' => $nodeRepo->createNodeTree($nodes),
		
				'threadPrefixes' => $availablePrefixes,
				
				'editableTags' => $grouped['editable'],
				'uneditableTags' => $grouped['uneditable']
			];
			return $this->view('XenAddons\Showcase:Item\ConvertToThread', 'xa_sc_convert_item_to_thread', $viewParams);
		}
	}
	
	public function actionModeratorActions(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if (!$item->canViewModeratorLogs($error))
		{
			return $this->noPermission($error);
		}
	
		$breadcrumbs = $item->getBreadcrumbs();
		$title = $item->title;
	
		$this->request()->set('page', $params->page);
	
		/** @var \XF\ControllerPlugin\ModeratorLog $modLogPlugin */
		$modLogPlugin = $this->plugin('XF:ModeratorLog');
		return $modLogPlugin->actionModeratorActions(
			$item,
			['showcase/moderator-actions', $item],
			$title, $breadcrumbs
		);
	}	
	
	public function actionHistory(ParameterBag $params)
	{
		return $this->rerouteController('XF:EditHistory', 'index', [
			'content_type' => 'sc_item',
			'content_id' => $params->item_id
		]);
	}
	
	public function actionReact(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($item, 'showcase');
	}
	
	public function actionReactions(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		$breadcrumbs = $item->getBreadcrumbs();
		$title = \XF::phrase('xa_sc_members_who_reacted_to_item', ['title' => $item->title]);
	
		$this->request()->set('page', $params->page);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$item,
			'showcase/reactions',
			$title, $breadcrumbs
		);
	}	

	public function actionPrefixes(ParameterBag $params)
	{
		$this->assertPostOnly();

		$categoryId = $this->filter('val', 'uint');

		/** @var \XenAddons\Showcase\Entity\Category $category */
		$category = $this->em()->find('XenAddons\Showcase:Category', $categoryId,
			'Permissions|' . \XF::visitor()->permission_combination_id
		);
		if (!$category)
		{
			return $this->notFound(\XF::phrase('requested_category_not_found'));
		}

		if (!$category->canView($error))
		{
			return $this->noPermission($error);
		}

		$viewParams = [
			'category' => $category,
			'prefixes' => $category->getUsablePrefixes()
		];
		
		return $this->view('XenAddons\Showcase:Category\Prefixes', 'xa_sc_category_prefixes', $viewParams);
	}
	
	public function actionMarkRead()
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return $this->noPermission();
		}
	
		$markDate = $this->filter('date', 'uint');
		if (!$markDate)
		{
			$markDate = \XF::$time;
		}
	
		if ($this->isPost())
		{
			$categoryRepo = $this->getCategoryRepo();
			$itemRepo = $this->getItemRepo();
	
			$categoryList = $categoryRepo->getViewableCategories();
			$categoryIds = $categoryList->keys();
	
			$itemRepo->markItemsReadByVisitor($categoryIds, $markDate);
			$itemRepo->markAllItemCommentsReadByVisitor($categoryIds, $markDate);
	
			return $this->redirect($this->buildLink('showcase'), \XF::phrase('xa_sc_all_items_marked_as_read'));
		}
		else
		{
			$viewParams = [
				'date' => $markDate
			];
			return $this->view('XenAddons\Showcase:Item\MarkRead', 'xa_sc_item_mark_read', $viewParams);
		}	
	}

	public function actionDialogYours()
	{
		$categoryRepo = $this->getCategoryRepo();
	
		$categoryList = $categoryRepo->getViewableCategories();
		$categoryIds = $categoryList->keys();
	
		$page = $this->filterPage();
		$perPage = $this->options()->xaScItemsPerPage;
	
		$itemRepo = $this->getItemRepo();
		$itemsList = $itemRepo->findItemsForUser(\XF::visitor(), $categoryIds, ['visibility' => false])
			->where('item_state', 'visible')
			->limitByPage($page, $perPage, 1);
	
		$items = $itemsList->fetch();
	
		$hasMore = false;
		if ($items->count() > $perPage)
		{
			$hasMore = true;
			$items = $items->slice(0, $perPage);
		}
	
		$viewParams = [
			'items' => $items,
			'page' => $page,
			'hasMore' => $hasMore
		];
		return $this->view('XenAddons\Showcase:Item\Dialog\Yours', 'xa_sc_dialog_your_items', $viewParams);
	}
	
	public function actionDialogBrowse()
	{
		$categoryRepo = $this->getCategoryRepo();
	
		$categoryList = $categoryRepo->getViewableCategories();
		$categoryIds = $categoryList->keys();
	
		$page = $this->filterPage();
		$perPage = $this->options()->xaScItemsPerPage;
	
		$itemRepo = $this->getItemRepo();
		$itemsList = $itemRepo->findItemsForItemList($categoryIds, ['visibility' => false])
			->where('item_state', 'visible')
			->where('user_id', '<>', \XF::visitor()->user_id)
			->limitByPage($page, $perPage, 1);
	
		$items = $itemsList->fetch();
	
		$hasMore = false;
		if ($items->count() > $perPage)
		{
			$hasMore = true;
			$items = $items->slice(0, $perPage);
		}
	
		$viewParams = [
			'items' => $items,
			'page' => $page,
			'hasMore' => $hasMore
		];
		return $this->view('XenAddons\Showcase:Item\Dialog\Browse', 'xa_sc_dialog_browse_items', $viewParams);
	}
	
	public function actionDialogYourPages()
	{
		$categoryRepo = $this->getCategoryRepo();
	
		$categoryList = $categoryRepo->getViewableCategories();
		$categoryIds = $categoryList->keys();
	
		$page = $this->filterPage();
		$perPage = $this->options()->xaScItemsPerPage;
	
		$itemPageRepo = $this->getPageRepo();
		$itemPagesList = $itemPageRepo->findItemPagesForUser(\XF::visitor(), $categoryIds, ['visibility' => false])
			->where('page_state', 'visible')
			->where('Item.item_state', 'visible')
			->limitByPage($page, $perPage, 1);
	
		$itemPages = $itemPagesList->fetch();
	
		$hasMore = false;
		if ($itemPages->count() > $perPage)
		{
			$hasMore = true;
			$itemPages = $itemPages->slice(0, $perPage);
		}
	
		$viewParams = [
			'itemPages' => $itemPages,
			'page' => $page,
			'hasMore' => $hasMore
		];
		return $this->view('XenAddons\Showcase:Item\Dialog\YourPages', 'xa_sc_dialog_your_pages', $viewParams);
	}
	
	public function actionDialogBrowsePages()
	{
		$categoryRepo = $this->getCategoryRepo();
	
		$categoryList = $categoryRepo->getViewableCategories();
		$categoryIds = $categoryList->keys();
	
		$page = $this->filterPage();
		$perPage = $this->options()->xaScItemsPerPage;
	
		$itemPageRepo = $this->getPageRepo();
		$itemPagesList = $itemPageRepo->findItemPagesForItemPageList($categoryIds, ['visibility' => false])
			->where('page_state', 'visible')
			->where('Item.item_state', 'visible')
			->where('user_id', '<>', \XF::visitor()->user_id)
			->limitByPage($page, $perPage, 1);
	
		$itemPages = $itemPagesList->fetch();
	
		$hasMore = false;
		if ($itemPages->count() > $perPage)
		{
			$hasMore = true;
			$itemPages = $itemPages->slice(0, $perPage);
		}
	
		$viewParams = [
			'itemPages' => $itemPages,
			'page' => $page,
			'hasMore' => $hasMore
		];
		return $this->view('XenAddons\Showcase:Item\Dialog\BrowsePages', 'xa_sc_dialog_browse_pages', $viewParams);
	}
	
	public function actionPollCreate(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
	
		$breadcrumbs = $item->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionCreate('sc_item', $item, $breadcrumbs);
	}
	
	public function actionPollEdit(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		$poll = $item->Poll;
	
		$breadcrumbs = $item->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionEdit($poll, $breadcrumbs);
	}
	
	public function actionPollDelete(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		$poll = $item->Poll;
	
		$breadcrumbs = $item->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionDelete($poll, $breadcrumbs);
	}
	
	public function actionPollVote(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		$poll = $item->Poll;
	
		$breadcrumbs = $item->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionVote($poll, $breadcrumbs);
	}
	
	public function actionPollResults(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);;
		$poll = $item->Poll;
	
		$breadcrumbs = $item->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionResults($poll, $breadcrumbs);
	}
	
	protected function getShowcaseRss()
	{
		$limit = $this->options()->discussionsPerPage;
	
		$itemRepo = $this->getItemRepo();
		$itemList = $itemRepo->findItemsForRssFeed()->limit($limit * 3);
	
		$order = $this->filter('order', 'str');
		switch ($order)
		{
			case 'last_update':
				break;
	
			default:
				$order = 'create_date';
				break;
		}
		$itemList->order($order, 'DESC');
	
		$items = $itemList->fetch()->filterViewable()->slice(0, $limit);
	
		return $this->view('XenAddons\Showcase:Category\Rss', '', ['category' => null, 'items' => $items]);
	}
	
	public function actionItemQueue(ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
	
		if (!$visitor->canViewShowcaseItemQueue($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		/** @var \XenAddons\Showcase\ControllerPlugin\ItemsQueue $itemQueuePlugin */
		$itemQueuePlugin = $this->plugin('XenAddons\Showcase:ItemsQueue');
	
		$categoryParams = $itemQueuePlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();
	
		$listParams = $itemQueuePlugin->getItemsQueueData($viewableCategoryIds);
	
		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'showcase/item-queue');
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/item-queue', null, $listParams['page']));
	
		$viewParams = $categoryParams + $listParams;
	
		return $this->view('XenAddons\Showcase:ItemQueue', 'xa_sc_item_queue', $viewParams);
	}
	
	public function actionItemQueueFilters()
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\ItemsQueue $itemQueuePlugin */
		$itemQueuePlugin = $this->plugin('XenAddons\Showcase:ItemsQueue');
	
		return $itemQueuePlugin->actionFilters();
	}
	
	public static function getActivityDetails(array $activities)
	{
		return self::getActivityDetailsForContent(
			$activities, \XF::phrase('xa_sc_viewing_item'), 'item_id',
			function(array $ids)
			{
				$items = \XF::em()->findByIds(
					'XenAddons\Showcase:Item',
					$ids,
					['Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id]
				);

				$router = \XF::app()->router('public');
				$data = [];

				foreach ($items->filterViewable() AS $id => $item)
				{
					$data[$id] = [
						'title' => $item->title,
						'url' => $router->buildLink('showcase', $item)
					];
				}

				return $data;
			},
			\XF::phrase('xa_sc_viewing_showcase')
		);
	}
}