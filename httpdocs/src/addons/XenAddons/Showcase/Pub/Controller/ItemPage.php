<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class ItemPage extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		if ($params->page_id)
		{
			return $this->rerouteController(__CLASS__, 'view', $params);
		}
		
		if ($params->item_id)
		{
			return $this->redirect($this->buildLink('showcase', $params));
		}
		
		return $this->redirect($this->buildLink('showcase'));
	}
	
	protected function getItemViewExtraWith()
	{
		$extraWith = ['CoverImage', 'Featured', 'SeriesPart', 'SeriesPart.Series'];
	
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$extraWith[] = 'Read|' . $userId;
			$extraWith[] = 'Watch|' . $userId;
			$extraWith[] = 'Reactions|' . $userId;
			$extraWith[] = 'Bookmarks|' . $userId;
			$extraWith[] = 'ReplyBans|' . $userId;
		}
	
		return $extraWith;
	}
	
	protected function getItemPageViewExtraWith()
	{
		$extraWith = ['CoverImage'];
	
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$extraWith[] = 'Reactions|' . $userId;
		}
	
		return $extraWith;
	}

	public function actionView(ParameterBag $params)
	{
		$this->assertNotEmbeddedImageRequest();
		
		$item = $this->assertViewableItem($params->item_id, $this->getItemViewExtraWith());
		
		$itemPage = $this->assertViewablePage($params->page_id, $this->getItemPageViewExtraWith());
	
		$this->assertCanonicalUrl($this->buildLink('showcase/page', $itemPage));
		
		$itemRepo = $this->getItemRepo();

		$nextPage = false;
		$previousPage = false;
		$itemPages = $item->getItemPages();
		if ($itemPages)
		{
			$nextPage = $item->getNextPage($itemPages, $itemPage);
			$previousPage = $item->getPreviousPage($itemPages, $itemPage);
				
			$item->page_count = $item->page_count + 1;  // this counts the item, plus the additional pages to get the correct count!
		}
		
		$nextSeriesPart = false;
		$previousSeriesPart = false;
		$seriesToc = $item->getSeriesToc();
		if ($seriesToc)
		{
			$nextSeriesPart = $item->getNextSeriesPart($seriesToc);
			$previousSeriesPart = $item->getPreviousSeriesPart($seriesToc);
		}
		
		// Only log views to non contributors (contributors include Author, Co-Authors and Contributors)
		if (!$item->isContributor())
		{
			$itemRepo->logItemView($item);
		}
		
		$itemRepo->markItemReadByVisitor($item);
		
		$pagePoll = (($itemPage && $itemPage->has_poll) ? $itemPage->Poll : null);
		
		/** @var \XF\Repository\UserAlert $userAlertRepo */
		$userAlertRepo = $this->repository('XF:UserAlert');
		$userAlertRepo->markUserAlertsReadForContent('sc_item', $item->item_id);
		
		$viewParams = [
			'itemPage' => $itemPage,
			'item' => $item,
			'category' => $item->Category,

			'pagePoll' => $pagePoll,
			
			'itemPages' => $itemPages,
			'nextPage' => $nextPage,
			'previousPage' => $previousPage,
			
			'seriesToc' => $seriesToc,
			'nextSeriesPart' => $nextSeriesPart,
			'previousSeriesPart' => $previousSeriesPart,
		];
		return $this->view('XenAddons\Showcase:ItemPage\View', 'xa_sc_item_page_view', $viewParams);
	}

	public function actionCoverImage(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id, ['CoverImage']);
	
		if (!$page->CoverImage)
		{
			return $this->notFound();
		}
	
		$this->request->set('no_canonical', 1);
	
		return $this->rerouteController('XF:Attachment', 'index', ['attachment_id' => $page->CoverImage->attachment_id]);
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemPage $page
	 *
	 * @return \XenAddons\Showcase\Service\Page\Editor
	 */
	protected function setupPageEdit(\XenAddons\Showcase\Entity\ItemPage $page)
	{
		/** @var \XenAddons\Showcase\Service\Page\Editor $editor */
		$editor = $this->service('XenAddons\Showcase:Page\Editor', $page);

		$editor->setTitle($this->filter('title', 'str'));
		$message = $this->plugin('XF:Editor')->fromInput('message');
		$editor->setMessage($message);
		
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
		$page->bulkSet($basicFields);

		$page->edit_date = time();
		
		if ($page->Item->Category->canUploadAndManagePageAttachments())
		{
			$editor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		return $editor;
	}	
	
	public function actionEdit(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		if (!$page->canEdit($error))
		{
			return $this->noPermission($error);
		}	
		
		$item = $page->Item;
		$category = $page->Item->Category;

		if ($this->isPost())
		{
			$oldPageState = $page->page_state;
			
			$editor = $this->setupPageEdit($page);
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			
			$page = $editor->save();
			
			// If the page is in draft mode and changed to visible, we want to send notifications to watched users
			if ($oldPageState == 'draft' && $page->page_state == 'visible')
			{
				$editor->sendNotifications();
			}
			
			if ($this->filter('mp', 'bool'))
			{
				return $this->redirectToManagePages($page);
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
				$attachmentData = $attachmentRepo->getEditorData('sc_page', $page);
			}
			else
			{
				$attachmentData = null;
			}
			
			$viewParams = [
				'page' => $page,
				'item' => $item,
				'category' => $category,
				'attachmentData' => $attachmentData,
				'from_page_management' => $this->filter('mp', 'bool')
			];
			return $this->view('XenAddons\Showcase:Page\Edit', 'xa_sc_page_edit', $viewParams);
		}	
	}
	
	public function actionPreview(ParameterBag $params)
	{
		$this->assertPostOnly();
		
		$page = $this->assertViewablePage($params->page_id);
	
		$pageEditor = $this->setupPageEdit($page);
		if (!$pageEditor->validate($errors))
		{
			return $this->error($errors);
		}
	
		$page = $pageEditor->getPage();
	
		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');
	
		if ($page->Item->Category && $page->Item->Category->canUploadAndManagePageAttachments())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_page', $page, $tempHash);
			$attachments = $attachmentData['attachments'];
		}
	
		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$page->message, 'sc_page', $page->Item->User, $attachments, $page->canViewAttachments()
		);
	}	
	
	public function actionSetCoverImage(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		if (!$page->canSetCoverImage($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$page->cover_image_id = $this->filter('attachment_id', 'int');
			$page->cover_image_caption = $this->filter('cover_image_caption', 'str');
			$page->cover_image_above_page = $this->filter('cover_image_above_page', 'int');
			$page->save();
				
			if ($this->filter('mp', 'bool'))
			{
				return $this->redirectToManagePages($page);
			}
			else
			{
				return $this->redirect($this->buildLink('showcase/page', $page));
			}	
		}
		else
		{
			$viewParams = [
				'page' => $page,
				'item' => $page->Item,
				'category' => $page->Item->Category,
				'from_page_management' => $this->filter('mp', 'bool')
			];
			return $this->view('XF:ItemPage\SetCoverImage', 'xa_sc_item_page_set_cover_image', $viewParams);
		}
	}	
	
	public function actionReassign(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		if (!$page->canReassign($error))
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
	
			/** @var \XenAddons\Showcase\Service\Page\Reassign $reassigner */
			$reassigner = $this->service('XenAddons\Showcase:Page\Reassign', $page);
	
			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}
	
			$reassigner->reassignTo($user);
			
			if ($this->filter('mp', 'bool'))
			{
				return $this->redirectToManagePages($page);
			}
			else
			{
				return $this->redirect($this->buildLink('showcase/page', $page));
			}
		}
		else
		{
			$viewParams = [
				'page' => $page,
				'item' => $page->Item,
				'category' => $page->Item->Category,
				'from_page_management' => $this->filter('mp', 'bool')
			];
			return $this->view('XenAddons\Showcase:Page\Reassign', 'xa_sc_page_reassign', $viewParams);
		}
	}	

	public function actionDelete(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		if (!$page->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');

			if (!$page->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}
			
			/** @var \XenAddons\Showcase\Service\Outcome\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:Page\Deleter', $page);

			if ($this->filter('author_alert', 'bool') && $page->canSendModeratorActionAlert())
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
			
			$deleter->delete($type, $reason);
			
			if ($this->filter('mp', 'bool'))
			{
				return $this->redirectToManagePages($page);
			}
			else 
			{
				return $this->redirectToItem($page->Item);
			}
		}
		else
		{
			$viewParams = [
				'page' => $page,
				'item' => $page->Item,
				'from_page_management' => $this->filter('mp', 'bool')
			];
			return $this->view('XenAddons\Showcase:ItemPage\Delete', 'xa_sc_page_delete', $viewParams);
		}
	}
	
	public function actionUndelete(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$page = $this->assertViewablePage($params->page_id);
		if (!$page->canUndelete($error))
		{
			return $this->noPermission($error);
		}
	
		if ($page->page_state == 'deleted')
		{
			$page->page_state = 'visible';
			$page->save();
		}
	
		if ($this->filter('mp', 'bool'))
		{
			return $this->redirectToManagePages($page);
		}
		else 
		{
			return $this->redirect($this->buildLink('showcase/page', $page));
		}
	}
	
	public function actionHistory(ParameterBag $params)
	{
		return $this->rerouteController('XF:EditHistory', 'index', [
			'content_type' => 'sc_page',
			'content_id' => $params->page_id
		]);
	}
	
	public function actionIp(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
	
		$item = $page->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Ip $ipPlugin */
		$ipPlugin = $this->plugin('XF:Ip');
		return $ipPlugin->actionIp($page, $breadcrumbs);
	}
	
	public function actionReport(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		if (!$page->canReport($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'sc_page', $page,
			$this->buildLink('showcase/page/report', $page),
			$this->buildLink('showcase/page', $page)
		);
	}	
	
	public function actionReact(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($page, 'showcase/page');
	}
	
	public function actionReactions(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
	
		$breadcrumbs = $page->Content->getBreadcrumbs();
		$title = \XF::phrase('xa_sc_members_who_have_reacted_to_page_by_x', ['title' => $page->title , 'user' => $page->username]);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$page,
			'showcase/page/reactions',
			$title, $breadcrumbs
		);
	}
	
	public function actionWarn(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
	
		if (!$page->canWarn($error))
		{
			return $this->noPermission($error);
		}
	
		$item = $page->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'sc_page', $page,
			$this->buildLink('showcase/page/warn', $page),
			$breadcrumbs
		);
	}
	
	public function actionPollCreate(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
	
		$breadcrumbs = $page->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionCreate('sc_page', $page, $breadcrumbs);
	}
	
	public function actionPollEdit(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		$poll = $page->Poll;
	
		$breadcrumbs = $page->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionEdit($poll, $breadcrumbs);
	}
	
	public function actionPollDelete(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		$poll = $page->Poll;
	
		$breadcrumbs = $page->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionDelete($poll, $breadcrumbs);
	}
	
	public function actionPollVote(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		$poll = $page->Poll;
	
		$breadcrumbs = $page->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionVote($poll, $breadcrumbs);
	}
	
	public function actionPollResults(ParameterBag $params)
	{
		$page = $this->assertViewablePage($params->page_id);
		$poll = $page->Poll;
	
		$breadcrumbs = $page->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionResults($poll, $breadcrumbs);
	}
	
	protected function redirectToManagePages(\XenAddons\Showcase\Entity\ItemPage $page)
	{
		$item = $page->Item;
	
		return $this->redirect($this->buildLink('showcase/pages', $item));
	}
	
	protected function redirectToItem(\XenAddons\Showcase\Entity\Item $item)
	{
		return $this->redirect($this->buildLink('showcase', $item));
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
			\XF::phrase('xa_sc_viewing_items')
		);
	}	
}