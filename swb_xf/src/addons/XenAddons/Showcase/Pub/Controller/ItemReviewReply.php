<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class ItemReviewReply extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
		$itemRating = $this->assertViewableReview($reply->rating_id);
	
		return $this->redirectPermanently($this->buildLink('showcase/review', $itemRating));
	}
	
	public function actionShow(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
	
		$viewParams = [
			'reply' => $reply,
			'review' => $reply->ItemRating,
		];
		return $this->view('XenAddons\Showcase:ItemReviewReply\Show', 'xa_sc_review_reply', $viewParams);
	}
	
	public function actionEdit(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
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
					'itemRating' => $reply->ItemRating,
					'reply' => $reply
				];
				$reply = $this->view('XenAddons\Showcase:ItemReviewReply\EditNewReply', 'xa_sc_review_reply_edit_new_reply', $viewParams);
				$reply->setJsonParam('message', \XF::phrase('your_changes_have_been_saved'));
				return $reply;
			}
			else
			{
				return $this->redirect($this->buildLink('showcase/review-reply', $reply));
			}
		}
		else
		{
			$viewParams = [
				'reply' => $reply,
				'itemRating' => $reply->ItemRating,
				'quickEdit' => $this->responseType() == 'json'
			];
			return $this->view('XenAddons\Showcase:ItemReviewReply\Edit', 'xa_sc_review_reply_edit', $viewParams);
		}
	}

	public function actionChangeDate(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
		if (!$reply->canChangeDate($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			// TODO probably move this process into a service in a future version!
	
			$replyDateInput = $this->filter([
				'reply_date' => 'str',
				'reply_hour' => 'int',
				'reply_minute' => 'int',
				'reply_timezone' => 'str'
			]);
	
			$tz = new \DateTimeZone($replyDateInput['reply_timezone']);
	
			$replyDate = $replyDateInput['reply_date'];
			$replyHour = $replyDateInput['reply_hour'];
			$replyMinute = $replyDateInput['reply_minute'];
			$replyDate = new \DateTime("$replyDate $replyHour:$replyMinute", $tz);
			$replyDate = $replyDate->format('U');
	
			if ($replyDate < $reply->ItemRating->rating_date)
			{
				return $this->error(\XF::phraseDeferred('xa_sc_can_not_set_reply_date_older_than_review_date'));
			}
	
			if ($replyDate > \XF::$time)
			{
				return $this->error(\XF::phraseDeferred('xa_sc_can_not_change_date_into_the_future'));
			}
	
			$reply->reply_date = $replyDate;
			$reply->save();
	
			return $this->redirect($this->buildLink('showcase/review-reply', $reply));
		}
		else
		{
			$visitor = \XF::visitor();
	
			$replyDate = new \DateTime('@' . $reply->reply_date);
			$replyDate->setTimezone(new \DateTimeZone($visitor->timezone));
	
			$viewParams = [
				'reply' => $reply,
				'review' => $reply->ItemRating,
				'item' => $reply->ItemRating->Item,
	
				'replyDate' => $replyDate,
				'replyHour' => $replyDate->format('H'),
				'replyMinute' => $replyDate->format('i'),
	
				'hours' => $reply->ItemRating->Item->getHours(),
				'minutes' => $reply->ItemRating->Item->getMinutes(),
				'timeZones' => $this->app->data('XF:TimeZone')->getTimeZoneOptions()
			];
			return $this->view('XenAddons\Showcase:ItemReviewReply\ChangeDate', 'xa_sc_review_reply_change_date', $viewParams);
		}
	}
	
	public function actionReassign(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
		if (!$reply->canReassign($error))
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
	
			/** @var \XenAddons\Showcase\Service\ItemRatingReply\Reassign $reassigner */
			$reassigner = $this->service('XenAddons\Showcase:ItemRatingReply\Reassign', $reply);
	
			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}
	
			$reassigner->reassignTo($user);
	
			return $this->redirect($this->buildLink('showcase/review-reply', $reply));
		}
		else
		{
			$viewParams = [
				'reply' => $reply,
				'review' => $reply->ItemRating,
			];
			return $this->view('XenAddons\Showcase:ItemReviewReply\Reassign', 'xa_sc_review_reply_reassign', $viewParams);
		}
	}
	
	public function actionDelete(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
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
	
			/** @var \XF\Service\ItemRatingReply\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:ItemRatingReply\Deleter', $reply);
	
			if ($this->filter('author_alert', 'bool') && $reply->canSendModeratorActionAlert())
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
	
			$deleter->delete($type, $reason);
	
			return $this->redirect(
				$this->getDynamicRedirect($this->buildLink('showcase/review-reply', $reply), false)
			);
		}
		else
		{
			$viewParams = [
				'reply' => $reply,
				'itemRating' => $reply->ItemRating
			];
			return $this->view('XenAddons\Showcase:ItemReviewReply\Delete', 'xa_sc_review_reply_delete', $viewParams);
		}
	}
	
	public function actionUndelete(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$reply = $this->assertViewableReviewReply($params->reply_id);
		if (!$reply->canUndelete($error))
		{
			return $this->noPermission($error);
		}
	
		if ($reply->reply_state == 'deleted')
		{
			$reply->reply_state = 'visible';
			$reply->save();
		}
	
		return $this->redirect($this->buildLink('showcase/review-reply', $reply));
	}
	
	public function actionApprove(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$reply = $this->assertViewableReviewReply($params->reply_id);
		if (!$reply->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XF\Service\ItemRatingReply\Approver $approver */
		$approver = \XF::service('XenAddons\Showcase:ItemRatingReply\Approver', $reply);
		$approver->approve();
	
		return $this->redirect($this->buildLink('showcase/review-reply', $reply));
	}
	
	public function actionUnapprove(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$reply = $this->assertViewableReviewReply($params->reply_id);
		if (!$reply->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
	
		$reply->reply_state = 'moderated';
		$reply->save();
	
		return $this->redirect($this->buildLink('showcase/review-reply', $reply));
	}
	
	public function actionWarn(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
		if (!$reply->canWarn($error))
		{
			return $this->noPermission($error);
		}
	
		$item = $reply->ItemRating->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'sc_rating_reply', $reply,
			$this->buildLink('showcase/review-reply/warn', $reply),
			$breadcrumbs
		);
	}
	
	public function actionIp(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
		
		$item = $reply->ItemRating->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Ip $ipPlugin */
		$ipPlugin = $this->plugin('XF:Ip');
		return $ipPlugin->actionIp($reply, $breadcrumbs);
	}
	
	public function actionReport(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
		if (!$reply->canReport($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'sc_rating_reply', $reply,
			$this->buildLink('showcase/review-reply/report', $reply),
			$this->buildLink('showcase/review-reply', $reply)
		);
	}
	
	public function actionReact(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($reply, 'showcase/review-reply');
	}
	
	public function actionReactions(ParameterBag $params)
	{
		$reply = $this->assertViewableReviewReply($params->reply_id);
	
		$item = $reply->ItemRating->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
		$title = \XF::phrase('xa_sc_members_who_have_reacted_to_review_reply_by_x', ['user' => $reply->username]);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$reply,
			'showcase/review-reply/reactions',
			$title, $breadcrumbs
		);
	}	
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemRatingReply $reply
	 *
	 * @return \XenAddons\Showcase\Service\ItemRatingReply\Editor
	 */
	protected function setupReplyEdit(\XenAddons\Showcase\Entity\ItemRatingReply $reply)
	{
		$message = $this->plugin('XF:Editor')->fromInput('message');
	
		/** @var \XF\Service\ItemRatingReply\Editor $editor */
		$editor = $this->service('XenAddons\Showcase:ItemRatingReply\Editor', $reply);
		$editor->setMessage($message);
	
		if ($this->filter('author_alert', 'bool') && $reply->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
	
		return $editor;
	}
	
	protected function finalizeReplyEdit(\XenAddons\Showcase\Service\ItemRatingReply\Editor $editor)
	{
	}	

	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('xa_sc_viewing_showcase');
	}
}