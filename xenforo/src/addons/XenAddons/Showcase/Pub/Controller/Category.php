<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class Category extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id, $this->getCategoryViewExtraWith());

		if ($this->responseType == 'rss')
		{
			return $this->getCategoryRss($category);
		}
		
		/** @var \XenAddons\Showcase\ControllerPlugin\ItemList $itemListPlugin */
		$itemListPlugin = $this->plugin('XenAddons\Showcase:ItemList');

		$categoryParams = $itemListPlugin->getCategoryListData($category);

		/** @var \XF\Tree $categoryTree */
		$categoryTree = $categoryParams['categoryTree'];
		$descendants = $categoryTree->getDescendants($category->category_id);

		$sourceCategoryIds = array_keys($descendants);
		$sourceCategoryIds[] = $category->category_id;

		$category->cacheViewableDescendents($descendants);

		$listParams = $itemListPlugin->getItemListData($sourceCategoryIds, $category);

		$this->assertValidPage(
			$listParams['page'],
			$listParams['perPage'],
			$listParams['total'],
			'showcase/categories',
			$category
		);
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/categories', $category, $listParams['page']));
		
		if ($category->layout_type)
		{
			$layoutType = $category->layout_type;
		}
		else 
		{
			$layoutType = $this->options()->xaScItemListLayoutType;
		}
		
		$viewParams = [
			'category' => $category,
			'pendingApproval' => $this->filter('pending_approval', 'bool'),
			'layoutType' => $layoutType
		];
		$viewParams += $categoryParams + $listParams;

		return $this->view('XenAddons\Showcase:Category\View', 'xa_sc_category_view', $viewParams);
	}
	
	public function actionMapMarkerLegend(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);
	
		if (!$this->options()->xaScGoogleMapsJavaScriptApiKey
			|| !$category->canViewCategoryMap()
		)
		{
			return $this->noPermission();
		}
	
		/** @var \XenAddons\Showcase\ControllerPlugin\CategoryMap $categoryMapPlugin */
		$categoryMapPlugin = $this->plugin('XenAddons\Showcase:CategoryMap');
	
		$categoryParams = $categoryMapPlugin->getCategoryListData($category);
	
		/** @var \XF\Tree $categoryTree */
		$categoryTree = $categoryParams['categoryTree'];
		$descendants = $categoryTree->getDescendants($category->category_id);
	
		$category->cacheViewableDescendents($descendants);
	
		$this->assertCanonicalUrl($this->buildLink('showcase/categories/map-marker-legend', $category));
	
		$viewParams = [
			'category' => $category,
			'descendants' => $descendants
		];
	
		return $this->view('XenAddons\Showcase:Category\MapMarkerLegend', 'xa_sc_map_marker_legend', $viewParams);
	}

	public function actionMap(ParameterBag $params)
	{
		if (!$this->options()->xaScGoogleMapsJavaScriptApiKey)
		{
			return $this->noPermission();
		}
	
		$category = $this->assertViewableCategory($params->category_id);
	
		if (isset($category['map_options']['enable_full_page_map']) 
			&& $category['map_options']['enable_full_page_map']
			&& $category->canViewCategoryMap()
		)
		{
			/** @var \XenAddons\Showcase\ControllerPlugin\CategoryMap $categoryMapPlugin */
			$categoryMapPlugin = $this->plugin('XenAddons\Showcase:CategoryMap');
	
			$categoryParams = $categoryMapPlugin->getCategoryListData($category);
	
			/** @var \XF\Tree $categoryTree */
			$categoryTree = $categoryParams['categoryTree'];
			$descendants = $categoryTree->getDescendants($category->category_id);
	
			$sourceCategoryIds = array_keys($descendants);
			$sourceCategoryIds[] = $category->category_id;
	
			$category->cacheViewableDescendents($descendants);
	
			$listParams = $categoryMapPlugin->getCategoryMapData($sourceCategoryIds, $category);
	
			$this->assertCanonicalUrl($this->buildLink('showcase/categories/map', $category));
	
			$viewParams = [
				'category' => $category,
			];
			$viewParams += $categoryParams + $listParams;
	
			return $this->view('XenAddons\Showcase:Category\Map', 'xa_sc_category_map_view', $viewParams);
		}
		else
		{
			return $this->noPermission();
		}
	}
	
	protected function getCategoryViewExtraWith()
	{
		$extraWith = [];
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$extraWith[] = 'Watch|' . $userId;
		}

		return $extraWith;
	}

	public function actionFilters(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);

		/** @var \XenAddons\Showcase\ControllerPlugin\ItemList $itemListPlugin */
		$itemListPlugin = $this->plugin('XenAddons\Showcase:ItemList');

		return $itemListPlugin->actionFilters($category);
	}
	
	public function actionMapFilters(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);
	
		/** @var \XenAddons\Showcase\ControllerPlugin\CategoryMap $categoryMapPlugin */
		$categoryMapPlugin = $this->plugin('XenAddons\Showcase:CategoryMap');
	
		return $categoryMapPlugin->actionFilters($category);
	}

	public function actionFeatured(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);

		/** @var \XenAddons\Showcase\ControllerPlugin\ItemList $itemListPlugin */
		$itemListPlugin = $this->plugin('XenAddons\Showcase:ItemList');

		return $itemListPlugin->actionFeatured($category);
	}
	
	public function actionMarkRead(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);
	
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
			/** @var \XenAddons\Showcase\ControllerPlugin\ItemList $itemListPlugin */
			$itemListPlugin = $this->plugin('XenAddons\Showcase:ItemList');
			
			$categoryParams = $itemListPlugin->getCategoryListData($category);
			
			/** @var \XF\Tree $categoryTree */
			$categoryTree = $categoryParams['categoryTree'];
			$descendants = $categoryTree->getDescendants($category->category_id);
			
			$categoryIds = array_keys($descendants);
			$categoryIds[] = $category->category_id;
			
			$itemRepo = $this->getItemRepo();
			$itemRepo->markItemsReadByVisitor($categoryIds, $markDate);
			$itemRepo->markAllItemCommentsReadByVisitor($categoryIds, $markDate);
	
			return $this->redirect(
				$this->buildLink('showcase/categories', $category),
				\XF::phrase('xa_sc_category_x_marked_as_read', ['title' => $category->title])
			);
		}
		else
		{
			$viewParams = [
				'category' => $category,
				'date' => $markDate
			];
			return $this->view('XenAddons\Showcase:Category\MarkRead', 'xa_sc_category_mark_read', $viewParams);
		}
	}	

	public function actionWatch(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);
		if (!$category->canWatch($error))
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
				$config = $this->filter([
					'notify_on' => 'str',
					'send_alert' => 'bool',
					'send_email' => 'bool',
					'include_children' => 'bool'
				]);
			}

			/** @var \XenAddons\Showcase\Repository\CategoryWatch $watchRepo */
			$watchRepo = $this->repository('XenAddons\Showcase:CategoryWatch');
			$watchRepo->setWatchState($category, $visitor, $action, $config);

			$redirect = $this->redirect($this->buildLink('showcase/categories', $category));
			$redirect->setJsonParam('switchKey', $action == 'delete' ? 'watch' : 'unwatch');
			return $redirect;
		}
		else
		{
			$viewParams = [
				'category' => $category,
				'isWatched' => !empty($category->Watch[$visitor->user_id])
			];
			return $this->view('XenAddons\Showcase:Category\Watch', 'xa_sc_category_watch', $viewParams);
		}
	}

	/**
	 * @param \XenAddons\Showcase\Entity\Category $category
	 *
	 * @return \XenAddons\Showcase\Service\Item\Create
	 */
	protected function setupItemCreate(\XenAddons\Showcase\Entity\Category $category)
	{
		$title = $this->filter('title', 'str');

		$message = $this->plugin('XF:Editor')->fromInput('message');

		/** @var \XenAddons\Showcase\Service\Item\Create $creator */
		$creator = $this->service('XenAddons\Showcase:Item\Create', $category);
		
		$creator->setContent($title, $message);
		
		$creator->setMessageS2($this->plugin('XF:Editor')->fromInput('message_s2'));
		$creator->setMessageS3($this->plugin('XF:Editor')->fromInput('message_s3'));
		$creator->setMessageS4($this->plugin('XF:Editor')->fromInput('message_s4'));
		$creator->setMessageS5($this->plugin('XF:Editor')->fromInput('message_s5'));
		$creator->setMessageS6($this->plugin('XF:Editor')->fromInput('message_s6'));

		//This is new as of SC 3.2.20
		$saveType = $this->filter('save_type', 'str'); 
		if ($saveType == 'draft') // This is for allowing Authors to save an item as a draft and publish it at a later time.
		{
			$creator->setItemState('draft');
			$creator->setCreateAssociatedThread(false); // An associated thread will be created by the publish draft service instead
		}
		else if ($saveType == 'publish_scheduled') // This is for allowing Authors to save an item as awaiting to be auto published at a later time.
		{
			$creator->setItemState('awaiting');
			$creator->setCreateAssociatedThread(false); // An associated thread will be created by the publish draft service instead
			
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
			
			$creator->setScheduledPublishDate($publishDate);
		}
		
		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId && $category->isPrefixUsable($prefixId))
		{
			$creator->setPrefix($prefixId);
		}

		if ($category->canEditTags())
		{
			$creator->setTags($this->filter('tags', 'str'));
			
			if ($category->thread_node_id && $category->thread_set_item_tags)
			{
				$creator->setAssociatedThreadTags($this->filter('tags', 'str'));
			}
		}

		if ($category->canUploadAndManageItemAttachments())
		{
			$creator->setItemAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		if ($category->allow_author_rating)
		{
			$creator->setAuthorRating($this->filter('author_rating', 'float'));
		}
		
		$creator->setLocation($this->filter('location', 'str'));
		
		$bulkInput = $this->filter([
			'og_title' => 'str',
			'meta_title' => 'str',
			'description' => 'str',
			'meta_description' => 'str',
			'comments_open' => 'bool',
			'ratings_open' => 'bool'
		]);
		$creator->getItem()->bulkSet($bulkInput);

		$customFields = $this->filter('custom_fields', 'array');
		$creator->setCustomFields($customFields);

		$pollQuestion = $this->filter('poll.question', 'str');
		if ($category->canCreatePoll() && strlen($pollQuestion))
		{
			$pollCreator = $this->plugin('XF:Poll')->setupPollCreate('sc_item', $creator->getItem());
			$creator->setPollCreator($pollCreator);
		}
		
		return $creator;
	}

	protected function finalizeItemCreate(\XenAddons\Showcase\Service\Item\Create $creator)
	{
		$creator->sendNotifications();

		$item = $creator->getItem();

		if (\XF::visitor()->user_id)
		{
			$creator->getCategory()->draft_item->delete();

			if ($item->item_state == 'moderated')
			{
				$this->session()->setHasContentPendingApproval();
			}
		}
		
		$visitor = \XF::visitor();
		
		/** @var \XenAddons\Showcase\Repository\ItemWatch $watchRepo */
		$watchRepo = $this->repository('XenAddons\Showcase:ItemWatch');
		$watchRepo->autoWatchScItem($item, $visitor, true);
	}

	public function actionAdd(ParameterBag $params)
	{
		$category = $this->assertViewableCategory($params->category_id);
		if (!$category->canAddItem($error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$creator = $this->setupItemCreate($category);
			$creator->checkForSpam();

			if (!$creator->validate($errors))
			{
				return $this->error($errors);
			}
			$this->assertNotFlooding('post');

			/** @var \XenAddons\Showcase\Entity\Item $item */
			$item = $creator->save();
			$this->finalizeItemCreate($creator);

			if (!$item->canView())
			{
				return $this->redirect($this->buildLink('showcase/categories', $category, ['pending_approval' => 1]));
			}
			else
			{
				return $this->redirect($this->buildLink('showcase', $item));
			}
		}
		else
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');

			$draft = $category->draft_item;

			if ($category->canUploadAndManageItemAttachments())
			{
				$attachmentData = $attachmentRepo->getEditorData('sc_item', $category, $draft->attachment_hash);
			}
			else
			{
				$attachmentData = null;
			}

			$item = $category->getNewItem();

			$item->title = $draft->title ?: '';
			$item->og_title = $draft->og_title ?: '';
			$item->meta_title = $draft->meta_title ?: '';
			$item->description = $draft->description ?: '';
			$item->meta_description = $draft->meta_description ?: '';
			$item->prefix_id = $draft->prefix_id ?: 0;
			$item->author_rating = $draft->author_rating ?: 0;
			$item->message = $draft->message ?: '';
			$item->message_s2 = $draft->message_s2 ?: '';
			$item->message_s3 = $draft->message_s3 ?: '';
			$item->message_s4 = $draft->message_s4 ?: '';
			$item->message_s5 = $draft->message_s5 ?: '';
			$item->message_s6 = $draft->message_s6 ?: '';
			$item->location = $draft->location ?: '';
			
			
			if ($category->draft_item->tags)
			{
				// do nothing for now!  Might expand to FORCE default tags at some point in the future
			}
			else
			{
				// Adds the categories default tags to preload in the tags input for creating new items
				$category->draft_item->tags = $category->default_tags;
			}
			
			if ($draft->custom_fields)
			{
				/** @var \XF\CustomField\Set $customFields */
				$customFields = $item->custom_fields;
				$customFields->bulkSet($draft->custom_fields, null, 'user', true);
			}
			
			$viewParams = [
				'category' => $category,
				'item' => $item,
				'prefixes' => $category->getUsablePrefixes(),

				'attachmentData' => $attachmentData,
				
				'hours' => $item->getHours(),
				'minutes' => $item->getMinutes(),
				'timeZones' => $this->app->data('XF:TimeZone')->getTimeZoneOptions()
			];
			return $this->view('XenAddons\Showcase:Category\AddItem', 'xa_sc_category_add_item', $viewParams);
		}
	}

	public function actionDraft(ParameterBag $params)
	{
		$this->assertPostOnly();

		$category = $this->assertViewableCategory($params->category_id);
		if (!$category->canAddItem($error))
		{
			return $this->noPermission($error);
		}

		$creator = $this->setupItemCreate($category);
		$item = $creator->getItem();

		$fromInput = $this->filter([
			'tags' => 'str',
			'attachment_hash' => 'str',
		]);

		$extraData = [
			'title' => $item->title,
			'og_title' => $item->og_title,
			'meta_title' => $item->meta_title,
			'description' => $item->description,
			'meta_description' => $item->meta_description,
			'prefix_id' => $item->prefix_id,
			'author_rating' => $item->author_rating,
			'message_s2' => $item->message_s2,
			'message_s3' => $item->message_s3,
			'message_s4' => $item->message_s4,
			'message_s5' => $item->message_s5,
			'message_s6' => $item->message_s6,			
			'location' => $item->location,
			'custom_fields' => $item->custom_fields->getFieldValues()
		] + $fromInput;

		if ($category->canCreatePoll() && $this->filter('poll.question', 'str'))
		{
			$pollPlugin = $this->plugin('XF:Poll');
			$extraData['poll'] = $pollPlugin->getPollInput();
		}
		
		/** @var \XF\ControllerPlugin\Draft $draftPlugin */
		$draftPlugin = $this->plugin('XF:Draft');
		
		return $draftPlugin->actionDraftMessage($category->draft_item, $extraData, 'message');
	}

	public function actionPreview(ParameterBag $params)
	{
		$this->assertPostOnly();

		$category = $this->assertViewableCategory($params->category_id);
		if (!$category->canAddItem($error))
		{
			return $this->noPermission($error);
		}

		$creator = $this->setupItemCreate($category);

		if (!$creator->validate($errors))
		{
			return $this->error($errors);
		}

		$item = $creator->getItem();

		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');

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
	 * @return \XenAddons\Showcase\Repository\Item
	 */
	protected function getItemRepo()
	{
		return $this->repository('XenAddons\Showcase:Item');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\Comment
	 */
	protected function getCommentRepo()
	{
		return $this->repository('XenAddons\Showcase:Comment');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XenAddons\Showcase:Category');
	}	
	
	/**
	 * @param integer $categoryId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\Category
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableCategory($categoryId, array $extraWith = [])
	{
		$visitor = \XF::visitor();

		$extraWith[] = 'Permissions|' . $visitor->permission_combination_id;

		/** @var \XenAddons\Showcase\Entity\Category $category */
		$category = $this->em()->find('XenAddons\Showcase:Category', $categoryId, $extraWith);
		if (!$category)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_category_not_found')));
		}

		if (!$category->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		return $category;
	}

	protected function getCategoryRss(\XenAddons\Showcase\Entity\Category $category = null)
	{
		$limit = $this->options()->discussionsPerPage;
	
		$itemRepo = $this->getItemRepo();
		$itemList = $itemRepo->findItemsForRssFeed($category)->limit($limit * 3);
	
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
	
		return $this->view('XenAddons\Showcase:Category\Rss', '', ['category' => $category, 'items' => $items]);
	}
	
	public static function getActivityDetails(array $activities)
	{
		return self::getActivityDetailsForContent(
			$activities, \XF::phrase('xa_sc_viewing_showcase_category'), 'category_id',
			function(array $ids)
			{
				$categories = \XF::em()->findByIds(
					'XenAddons\Showcase:Category',
					$ids,
					['Permissions|' . \XF::visitor()->permission_combination_id]
				);

				$router = \XF::app()->router('public');
				$data = [];

				foreach ($categories->filterViewable() AS $id => $category)
				{
					$data[$id] = [
						'title' => $category->title,
						'url' => $router->buildLink('showcase/categories', $category)
					];
				}

				return $data;
			}
		);
	}
	
	public function actionPrefixHelp(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$category = $this->assertViewableCategory($params->category_id);
	
		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId && $category->isPrefixUsable($prefixId))
		{
			$prefix = $this->em()->find('XenAddons\Showcase:ItemPrefix', $prefixId);
	
			return $this->view('XenAddons\Showcase:Category\PrefixHelp', 'prefix_usage_help', [
				'prefix' => $prefix,
				'contentType' => 'sc_item'
			]);
		}
	
		return $this->notFound();
	}
}