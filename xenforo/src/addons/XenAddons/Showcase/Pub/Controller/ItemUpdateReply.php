<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class ItemUpdateReply extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		$itemUpdate = $this->assertViewableUpdate($reply->item_update_id);
	
		return $this->redirectPermanently($this->buildLink('showcase/update', $itemUpdate));
	}
	
	public function actionShow(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
	
		$viewParams = [
			'reply' => $reply,
			'itemUpdate' => $reply->ItemUpdate,
		];
		return $this->view('XenAddons\Showcase:ItemUpdateReply\Show', 'xa_sc_update_reply', $viewParams);
	}
	
	public function actionEdit(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		if (!$reply->canEdit($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$editor = $this->setupReplyEdit($reply);
			$editor->checkForSpam();
	
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$editor->save();
	
			$this->finalizeReplyEdit($editor);
	
			if ($this->filter('_xfWithData', 'bool') && $this->filter('_xfInlineEdit', 'bool'))
			{
				$viewParams = [
					'itemUpdate' => $reply->ItemUpdate,
					'reply' => $reply
				];
				$reply = $this->view('XenAddons\Showcase:ItemUpdateReply\EditNewReply', 'xa_sc_update_reply_edit_new_reply', $viewParams);
				$reply->setJsonParam('message', \XF::phrase('your_changes_have_been_saved'));
				return $reply;
			}
			else
			{
				return $this->redirect($this->buildLink('showcase/update-reply', $reply));
			}
		}
		else
		{
			$viewParams = [
				'reply' => $reply,
				'itemUpdate' => $reply->ItemUpdate,
				'quickEdit' => $this->responseType() == 'json'
			];
			return $this->view('XenAddons\Showcase:ItemUpdateReply\Edit', 'xa_sc_update_reply_edit', $viewParams);
		}
	}
	
	public function actionDelete(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		if (!$reply->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');
	
			if (!$reply->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}
	
			/** @var \XF\Service\ItemUpdateReply\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:ItemUpdateReply\Deleter', $reply);
	
			if ($this->filter('author_alert', 'bool') && $reply->canSendModeratorActionAlert())
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
	
			$deleter->delete($type, $reason);
	
			return $this->redirect(
				$this->getDynamicRedirect($this->buildLink('showcase/update-reply', $reply), false)
			);
		}
		else
		{
			$viewParams = [
				'reply' => $reply,
				'itemUpdate' => $reply->ItemUpdate
			];
			return $this->view('XenAddons\Showcase:ItemUpdateReply\Delete', 'xa_sc_update_reply_delete', $viewParams);
		}
	}
	
	public function actionUndelete(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		if (!$reply->canUndelete($error))
		{
			return $this->noPermission($error);
		}
	
		if ($reply->reply_state == 'deleted')
		{
			$reply->reply_state = 'visible';
			$reply->save();
		}
	
		return $this->redirect($this->buildLink('showcase/update-reply', $reply));
	}
	
	public function actionApprove(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		if (!$reply->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XF\Service\ItemUpdateReply\Approver $approver */
		$approver = \XF::service('XenAddons\Showcase:ItemUpdateReply\Approver', $reply);
		$approver->approve();
	
		return $this->redirect($this->buildLink('showcase/update-reply', $reply));
	}
	
	public function actionUnapprove(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		if (!$reply->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
	
		$reply->reply_state = 'moderated';
		$reply->save();
	
		return $this->redirect($this->buildLink('showcase/update-reply', $reply));
	}
	
	public function actionWarn(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		if (!$reply->canWarn($error))
		{
			return $this->noPermission($error);
		}
	
		$item = $reply->ItemUpdate->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'sc_update_reply', $reply,
			$this->buildLink('showcase/update-reply/warn', $reply),
			$breadcrumbs
		);
	}
	
	public function actionIp(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		
		$item = $reply->ItemUpdate->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Ip $ipPlugin */
		$ipPlugin = $this->plugin('XF:Ip');
		return $ipPlugin->actionIp($reply, $breadcrumbs);
	}
	
	public function actionReport(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
		if (!$reply->canReport($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'sc_update_reply', $reply,
			$this->buildLink('showcase/update-reply/report', $reply),
			$this->buildLink('showcase/update-reply', $reply)
		);
	}
	
	public function actionReact(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($reply, 'showcase/update-reply');
	}
	
	public function actionReactions(ParameterBag $params)
	{
		$reply = $this->assertViewableUpdateReply($params->reply_id);
	
		$item = $reply->ItemUpdate->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
		$title = \XF::phrase('xa_sc_members_who_have_reacted_to_update_reply_by_x', ['user' => $reply->username]);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$reply,
			'showcase/update-reply/reactions',
			$title, $breadcrumbs
		);
	}	
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemUpdateReply $reply
	 *
	 * @return \XenAddons\Showcase\Service\ItemUpdateReply\Editor
	 */
	protected function setupReplyEdit(\XenAddons\Showcase\Entity\ItemUpdateReply $reply)
	{
		$message = $this->plugin('XF:Editor')->fromInput('message');
	
		/** @var \XF\Service\ItemUpdateReply\Editor $editor */
		$editor = $this->service('XenAddons\Showcase:ItemUpdateReply\Editor', $reply);
		$editor->setMessage($message);
	
		if ($this->filter('author_alert', 'bool') && $reply->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
	
		return $editor;
	}
	
	protected function finalizeReplyEdit(\XenAddons\Showcase\Service\ItemUpdateReply\Editor $editor)
	{
	}	

	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('xa_sc_viewing_showcase');
	}
}