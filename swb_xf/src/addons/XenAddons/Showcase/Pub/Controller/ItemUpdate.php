<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class ItemUpdate extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);

		return $this->redirectToUpdate($update);
	}
	
	// This is not used in core, added for custom development purposes only (not supported)
	public function actionShow(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
	
		$viewParams = [
			'update' => $update,
			'item' => $update->Item,
			'canInlineMod' => $update->canUseInlineModeration()
		];
		return $this->view('XenAddons\Showcase:ItemUpdate\Show', 'xa_sc_update_show', $viewParams);
	}
	
	public function actionPreview(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$update = $this->assertViewableUpdate($params->item_update_id);
	
		$editor = $this->setupItemUpdateEdit($update);
		if (!$editor->validate($errors))
		{
			return $this->error($errors);
		}
	
		$update = $editor->getUpdate();
	
		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');
		
		if ($update->Item->Category && $update->Item->Category->canUploadAndManageUpdateImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_update', $update, $tempHash);
			$attachments = $attachmentData['attachments'];
		}
		
		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$update->message, 'sc_update', $update->User, $attachments, $update->Item->canViewUpdateImages()
		);
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemUpdate $update
	 *
	 * @return \XenAddons\Showcase\Service\ItemUpdate\Edit
	 */
	protected function setupUpdateEdit(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		/** @var \XenAddons\Showcase\Service\ItemUpdate\Edit $editor */
		$editor = $this->service('XenAddons\Showcase:ItemUpdate\Edit', $update);

		if ($update->canEditSilently())
		{
			$silentEdit = $this->filter('silent', 'bool');
			if ($silentEdit)
			{
				$editor->logEdit(false);
				if ($this->filter('clear_edit', 'bool'))
				{
					$update->last_edit_date = 0;
				}
			}
		}
		
		$editor->setTitle($this->filter('title', 'str'));
		$editor->setMessage($this->plugin('XF:Editor')->fromInput('message'));
		
		$customFields = $this->filter('custom_fields', 'array');
		$editor->setCustomFields($customFields);
		
		/** @var \XenAddons\Showcase\Entity\Category $category */
		$category = $update->Item->Category;
		if ($category->canUploadAndManageUpdateImages())
		{
			$editor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		if ($this->filter('author_alert', 'bool') && $update->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		return $editor;
	}	
	
	public function actionEdit(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
		if (!$update->canEdit($error))
		{
			return $this->noPermission($error);
		}	

		if ($this->isPost())
		{
			$editor = $this->setupUpdateEdit($update);
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			
			$update = $editor->save();
			
			if ($this->filter('_xfWithData', 'bool') && $this->filter('_xfInlineEdit', 'bool'))
			{
				$viewParams = [
					'update' => $update,
					'item' => $update->Item,
				];
				$reply = $this->view('XenAddons\Showcase:ItemUpdate\EditNewValue', 'xa_sc_update_edit_new_value', $viewParams);
				$reply->setJsonParams([
					'message' => \XF::phrase('your_changes_have_been_saved')
				]);
				return $reply;
			}
			else
			{
				return $this->redirectToUpdate($update);
			}
		}
		else
		{
			if ($update->Item->Category->canUploadAndManageUpdateImages())
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('sc_update', $update);
			}
			else
			{
				$attachmentData = null;
			}
			
			$viewParams = [
				'update' => $update,
				'item' => $update->Item,
				'attachmentData' => $attachmentData,
				'quickEdit' => $this->filter('_xfWithData', 'bool')
			];
			return $this->view('XenAddons\Showcase:ItemUpdate\Edit', 'xa_sc_update_edit', $viewParams);
		}	
	}
	
	public function actionDelete(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
		if (!$update->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');

			if (!$update->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}

			/** @var \XenAddons\Showcase\Service\ItemUpdate\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:ItemUpdate\Deleter', $update);

			if ($this->filter('author_alert', 'bool') && $update->canSendModeratorActionAlert())
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}

			$deleter->delete($type, $reason);

			return $this->redirect(
				$this->getDynamicRedirect($this->buildLink('showcase', $update->Item), false)
			);
		}
		else
		{
			$viewParams = [
				'update' => $update,
				'item' => $update->Item
			];
			return $this->view('XenAddons\Showcase:ItemUpdate\Delete', 'xa_sc_update_delete', $viewParams);
		}
	}

	public function actionUndelete(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
		
		/** @var \XF\ControllerPlugin\Undelete $plugin */
		$plugin = $this->plugin('XF:Undelete');
		return $plugin->actionUndelete(
			$update,
			$this->buildLink('showcase/update/undelete', $update),
			$this->buildLink('showcase/update', $update),
			$update->title,
			'update_state'
		);
	}
	
	public function actionIp(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
		
		$item = $update->Item;
		$breadcrumbs = $item->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Ip $ipPlugin */
		$ipPlugin = $this->plugin('XF:Ip');
		return $ipPlugin->actionIp($update, $breadcrumbs);
	}	

	public function actionReport(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
		if (!$update->canReport($error))
		{
			return $this->noPermission($error);
		}

		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'sc_update', $update,
			$this->buildLink('showcase/update/report', $update),
			$this->buildLink('showcase/update', $update)
		);
	}
	
	public function actionHistory(ParameterBag $params)
	{
		return $this->rerouteController('XF:EditHistory', 'index', [
			'content_type' => 'sc_update',
			'content_id' => $params->item_update_id
		]);
	}
	
	public function actionBookmark(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
	
		/** @var \XF\ControllerPlugin\Bookmark $bookmarkPlugin */
		$bookmarkPlugin = $this->plugin('XF:Bookmark');
	
		return $bookmarkPlugin->actionBookmark(
			$update, $this->buildLink('showcase/update/bookmark', $update)
		);
	}
	
	public function actionReact(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($update, 'showcase/update');
	}
	
	public function actionReactions(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
	
		$item = $update->Item;
		$breadcrumbs = $item->getBreadcrumbs();
		$title = \XF::phrase('xa_sc_members_who_have_reacted_to_update_by_x', ['user' => $update->username]);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$update,
			'showcase/update/reactions',
			$title, $breadcrumbs
		);
	}

	public function actionWarn(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
	
		if (!$update->canWarn($error))
		{
			return $this->noPermission($error);
		}
	
		$item = $update->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'sc_update', $update,
			$this->buildLink('showcase/update/warn', $update),
			$breadcrumbs
		);
	}
	
	public function actionApprove(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$update = $this->assertViewableUpdate($params->item_update_id);
		if (!$update->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XenAddons\Showcase\Service\ItemUpdate\Approve $approver */
		$approver = \XF::service('XenAddons\Showcase:ItemUpdate\Approve', $update);
		$approver->approve();
	
		return $this->redirect($this->buildLink('showcase/update', $update));
	}
	
	public function actionUnapprove(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$update = $this->assertViewableUpdate($params->item_update_id);
		if (!$update->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
	
		$update->update_state = 'moderated';
		$update->save();
	
		return $this->redirect($this->buildLink('showcase/update', $update));
	}
	
	
	
	// Item Update Replies functions..... 	
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemUpdate $itemUpdate
	 *
	 * @return \XenAddons\Showcase\Service\ItemUpdateReply\Creator
	 */
	protected function setupItemUpdateReply(\XenAddons\Showcase\Entity\ItemUpdate $itemUpdate)
	{
		$message = $this->plugin('XF:Editor')->fromInput('message');
	
		/** @var \XenAddons\Showcase\Service\ItemUpdateReply\Creator $creator */
		$creator = $this->service('XenAddons\Showcase:ItemUpdateReply\Creator', $itemUpdate);
		$creator->setContent($message);
	
		return $creator;
	}
	
	protected function finalizeItemUpdateReply(\XenAddons\Showcase\Service\ItemUpdateReply\Creator $creator)
	{
		$creator->sendNotifications();
	}
	
	public function actionAddReply(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$update = $this->assertViewableUpdate($params->item_update_id);
		if (!$update->canReply($error))
		{
			return $this->noPermission($error);
		}
	
		$creator = $this->setupItemUpdateReply($update);
		$creator->checkForSpam();
	
		if (!$creator->validate($errors))
		{
			return $this->error($errors);
		}
		$this->assertNotFlooding('post');
		$reply = $creator->save();
	
		$this->finalizeItemUpdateReply($creator);
	
		if ($this->filter('_xfWithData', 'bool') && $this->request->exists('last_date') && $update->canView())
		{
			$updateRepo = $this->getItemUpdateRepo();
	
			$lastDate = $this->filter('last_date', 'uint');
	
			/** @var \XF\Mvc\Entity\Finder $itemUpdateReplyList */
			$itemUpdateReplyList = $updateRepo->findNewestRepliesForItemUpdate($update, $lastDate);
			$itemUpdateReplies = $itemUpdateReplyList->fetch();
	
			// put the posts into oldest-first order
			$itemUpdateReplies = $itemUpdateReplies->reverse(true);
	
			$viewParams = [
				'itemUpdate' => $update,
				'itemUpdateReplies' => $itemUpdateReplies
			];
			$view = $this->view('XenAddons\Showcase:ItemUpdate\NewItemUpdateReplies', 'xa_sc_update_new_replies', $viewParams);
			$view->setJsonParam('lastDate', $itemUpdateReplies->last()->reply_date);
			return $view;
		}
		else
		{
			return $this->redirect($this->buildLink('showcase/update-reply', $reply));
		}
	}
	
	public function actionLoadPrevious(ParameterBag $params)
	{
		$update = $this->assertViewableUpdate($params->item_update_id);
	
		$updateRepo = $this->getItemUpdateRepo();
	
		$replies = $updateRepo->findItemUpdateReplies($update)
			->with('full')
			->where('reply_date', '<', $this->filter('before', 'uint'))
			->order('reply_date', 'DESC')
			->limit(20)
			->fetch()
			->reverse();
	
		if ($replies->count())
		{
			$firstReplyDate = $replies->first()->reply_date;
	
			$moreRepliesFinder = $updateRepo->findItemUpdateReplies($update)
				->where('reply_date', '<', $firstReplyDate);
	
			$loadMore = ($moreRepliesFinder->total() > 0);
		}
		else
		{
			$firstReplyDate = 0;
			$loadMore = false;
		}
	
		$viewParams = [
			'itemUpdate' => $update,
			'replies' => $replies,
			'firstReplyDate' => $firstReplyDate,
			'loadMore' => $loadMore
		];
		return $this->view('XenAddons\Showcase:ItemUpdate\LoadPrevious', 'xa_sc_update_replies', $viewParams); // TODO might use item_update_replies instead!
	}

	protected function redirectToUpdate(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		$item = $update->Item;

		$newerFinder = $this->getItemUpdateRepo()->findUpdatesForItem($item);
		$newerFinder->where('update_date', '>', $update->update_date);
		$totalNewer = $newerFinder->total();

		$perPage = $this->options()->xaScUpdatesPerPage;
		$page = ceil(($totalNewer + 1) / $perPage);

		if ($page > 1)
		{
			$params = ['page' => $page];
		}
		else
		{
			$params = [];
		}

		return $this->redirect(
			$this->buildLink('showcase/updates', $item, $params)
			. '#item-update-' . $update->item_update_id
		);
	}

	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('xa_sc_viewing_item_updates');
	}
}