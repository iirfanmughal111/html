<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class ItemReview extends AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);

		return $this->redirectToReview($review);
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemRating $rating
	 *
	 * @return \XenAddons\Showcase\Service\Review\Edit
	 */
	protected function setupReviewEdit(\XenAddons\Showcase\Entity\ItemRating $rating)
	{
		/** @var \XenAddons\Showcase\Service\Review\Edit $editor */
		$editor = $this->service('XenAddons\Showcase:Review\Edit', $rating);
	
		if ($rating->canEditSilently())
		{
			$silentEdit = $this->filter('silent', 'bool');
			if ($silentEdit)
			{
				$editor->logEdit(false);
				if ($this->filter('clear_edit', 'bool'))
				{
					$rating->last_edit_date = 0;
				}
			}
		}
	
		$input = $this->filter([
			'rating' => 'uint',
			'title' => 'str',
			'pros' => 'str',
			'cons' => 'str',
		]);
	
		$editor->setRating($input['rating']);
		$editor->setTitle($input['title']);
		$editor->setPros($input['pros']);
		$editor->setCons($input['cons']);
		$editor->setMessage($this->plugin('XF:Editor')->fromInput('message'));
		
		$customFields = $this->filter('custom_fields', 'array');
		$editor->setCustomFields($customFields);
		
		if ($this->filter('author_alert', 'bool') && $rating->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		if ($rating->Item->Category->canUploadAndManageReviewImages())
		{
			$editor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		return $editor;
	}	
	
	public function actionEdit(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
		if (!$review->canEdit($error))
		{
			return $this->noPermission($error);
		}	
		
		$category = $review->Item->Category;

		if ($this->isPost())
		{
			$editor = $this->setupReviewEdit($review);
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			
			$review = $editor->save();
			
			if ($this->filter('_xfWithData', 'bool') && $this->filter('_xfInlineEdit', 'bool'))
			{
				$viewParams = [
					'review' => $review,
					'item' => $review->Item,
					'category' => $review->Item->Category,
				];
				$review = $this->view('XenAddons\Showcase:ItemReview\EditNewReview', 'xa_sc_review_edit_new_review', $viewParams);
				$review->setJsonParam('message', \XF::phrase('your_changes_have_been_saved'));
				return $review;
			}
			else
			{
				return $this->redirectToReview($review);
			}
		}
		else
		{
			if ($category && $category->canUploadAndManageReviewImages())
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('sc_rating', $review);
			}
			else
			{
				$attachmentData = null;
			}
			
			$viewParams = [
				'review' => $review,
				'item' => $review->Item,
				'category' => $review->Item->Category,
				'attachmentData' => $attachmentData,
				'quickEdit' => $this->responseType() == 'json'
			];
			return $this->view('XenAddons\Showcase:Review\Edit', 'xa_sc_review_edit', $viewParams);
		}	
	}

	public function actionPreview(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$review = $this->assertViewableReview($params->rating_id);
	
		$reviewEditor = $this->setupReviewEdit($review);
		if (!$reviewEditor->validate($errors))
		{
			return $this->error($errors);
		}
	
		$review = $reviewEditor->getRating();
	
		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');
	
		if ($review->Item->Category && $review->Item->Category->canUploadAndManageReviewImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_rating', $review, $tempHash);
			$attachments = $attachmentData['attachments'];
		}
	
		return $this->plugin('XF:BbCodePreview')->actionPreview(
				$review->message, 'sc_rating', $review->User, $attachments, $review->Item->canViewReviewImages()
		);
	}

	public function actionChangeDate(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
		if (!$review->canChangeDate($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			// TODO probably move this process into a service in a future version!
	
			$reviewDateInput = $this->filter([
				'review_date' => 'str',
				'review_hour' => 'int',
				'review_minute' => 'int',
				'review_timezone' => 'str'
			]);
	
			$tz = new \DateTimeZone($reviewDateInput['review_timezone']);
	
			$reviewDate = $reviewDateInput['review_date'];
			$reviewHour = $reviewDateInput['review_hour'];
			$reviewMinute = $reviewDateInput['review_minute'];
			$reviewDate = new \DateTime("$reviewDate $reviewHour:$reviewMinute", $tz);
			$reviewDate = $reviewDate->format('U');
	
			if ($reviewDate < $review->Item->create_date)
			{
				return $this->error(\XF::phraseDeferred('xa_sc_can_not_set_review_date_older_than_item_create_date'));
			}
	
			if ($reviewDate > \XF::$time)
			{
				return $this->error(\XF::phraseDeferred('xa_sc_can_not_change_date_into_the_future'));
			}
	
			$review->rating_date = $reviewDate;
			$review->save();
	
			return $this->redirect($this->buildLink('showcase/review', $review));
		}
		else
		{
			$visitor = \XF::visitor();
	
			$reviewDate = new \DateTime('@' . $review->rating_date);
			$reviewDate->setTimezone(new \DateTimeZone($visitor->timezone));
	
			$viewParams = [
				'review' => $review,
				'item' => $review->Item,
	
				'reviewDate' => $reviewDate,
				'reviewHour' => $reviewDate->format('H'),
				'reviewMinute' => $reviewDate->format('i'),
	
				'hours' => $review->Item->getHours(),
				'minutes' => $review->Item->getMinutes(),
				'timeZones' => $this->app->data('XF:TimeZone')->getTimeZoneOptions()
			];
			return $this->view('XenAddons\Showcase:Review\ChangeDate', 'xa_sc_review_change_date', $viewParams);
		}
	}
	
	public function actionReassign(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
		if (!$review->canReassign($error))
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
	
			/** @var \XenAddons\Showcase\Service\Review\Reassign $reassigner */
			$reassigner = $this->service('XenAddons\Showcase:Review\Reassign', $review);
	
			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}
	
			$reassigner->reassignTo($user);
	
			return $this->redirect($this->buildLink('showcase/review', $review));
		}
		else
		{
			$viewParams = [
				'review' => $review,
				'item' => $review->Item,
				'category' => $review->Item->Category
			];
			return $this->view('XenAddons\Showcase:Review\Reassign', 'xa_sc_review_reassign', $viewParams);
		}
	}
		
	public function actionDelete(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
		if (!$review->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');

			if (!$review->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}

			/** @var \XenAddons\Showcase\Service\Review\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:Review\Deleter', $review);

			if ($this->filter('author_alert', 'bool') && $review->canSendModeratorActionAlert())
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}

			$deleter->delete($type, $reason);

			return $this->redirect(
				$this->getDynamicRedirect($this->buildLink('showcase', $review->Item), false)
			);
		}
		else
		{
			$viewParams = [
				'review' => $review,
				'item' => $review->Item
			];
			return $this->view('XenAddons\Showcase:Review\Delete', 'xa_sc_review_delete', $viewParams);
		}
	}

	public function actionUndelete(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));

		$review = $this->assertViewableReview($params->rating_id);
		if (!$review->canUndelete($error))
		{
			return $this->noPermission($error);
		}

		if ($review->rating_state == 'deleted')
		{
			$review->rating_state = 'visible';
			$review->save();
		}

		return $this->redirect($this->buildLink('showcase/review', $review));
	}
	
	public function actionIp(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
		
		$item = $review->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Ip $ipPlugin */
		$ipPlugin = $this->plugin('XF:Ip');
		return $ipPlugin->actionIp($review, $breadcrumbs);
	}	

	public function actionReport(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
		if (!$review->canReport($error))
		{
			return $this->noPermission($error);
		}

		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'sc_rating', $review,
			$this->buildLink('showcase/review/report', $review),
			$this->buildLink('showcase/review', $review)
		);
	}
	
	public function actionHistory(ParameterBag $params)
	{
		return $this->rerouteController('XF:EditHistory', 'index', [
			'content_type' => 'sc_rating',
			'content_id' => $params->rating_id
		]);
	}
	
	public function actionReact(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($review, 'showcase/review');
	}
	
	public function actionReactions(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
	
		$breadcrumbs = $review->Content->getBreadcrumbs();
		$title = \XF::phrase('xa_sc_members_who_have_reacted_to_review_by_x', ['user' => ($review->is_anonymous ? \XF::phrase('anonymous') : $review->username)]);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$review,
			'showcase/review/reactions',
			$title, $breadcrumbs
		);
	}

	public function actionVote(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
	
		/** @var \XF\ControllerPlugin\ContentVote $votePlugin */
		$votePlugin = $this->plugin('XF:ContentVote');
	
		return $votePlugin->actionVote(
			$review,
			$this->buildLink('showcase/review', $review),
			$this->buildLink('showcase/review/vote', $review)
		);
	}

	public function actionWarn(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);

		if (!$review->canWarn($error))
		{
			return $this->noPermission($error);
		}

		$item = $review->Item;
		$breadcrumbs = $item->Category->getBreadcrumbs();

		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'sc_rating', $review,
			$this->buildLink('showcase/review/warn', $review),
			$breadcrumbs
		);
	}
	
	public function actionDeleteRating(ParameterBag $params)
	{
		$rating = $this->assertViewableRating($params->rating_id);
		if (!$rating->canDelete('hard', $error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$type = 'hard';
			$reason = $this->filter('reason', 'str');
	
			if (!$rating->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}
	
			/** @var \XenAddons\Showcase\Service\Review\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:Review\Deleter', $rating);
	
			if ($this->filter('author_alert', 'bool') && $rating->canSendModeratorActionAlert())
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
	
			$deleter->delete($type, $reason);
	
			return $this->redirect(
				$this->getDynamicRedirect($this->buildLink('showcase', $rating->Item), false)
			);
		}
		else
		{
			$viewParams = [
				'rating' => $rating,
				'item' => $rating->Item
			];
			return $this->view('XenAddons\Showcase:ItemRating\Delete', 'xa_sc_rating_delete', $viewParams);
		}
	}	
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemRating $itemRating
	 *
	 * @return \XenAddons\Showcase\Service\ItemRatingReply\Creator
	 */
	protected function setupItemRatingReply(\XenAddons\Showcase\Entity\ItemRating $itemRating)
	{
		$message = $this->plugin('XF:Editor')->fromInput('message');
	
		/** @var \XenAddons\Showcase\Service\ItemRatingReply\Creator $creator */
		$creator = $this->service('XenAddons\Showcase:ItemRatingReply\Creator', $itemRating);
		$creator->setContent($message);
	
		return $creator;
	}
	
	protected function finalizeItemRatingReply(\XenAddons\Showcase\Service\ItemRatingReply\Creator $creator)
	{
		$creator->sendNotifications();
	}
	
	public function actionAddReply(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$review = $this->assertViewableReview($params->rating_id);
		if (!$review->canReply($error))
		{
			return $this->noPermission($error);
		}
	
		$creator = $this->setupItemRatingReply($review);
		$creator->checkForSpam();
	
		if (!$creator->validate($errors))
		{
			return $this->error($errors);
		}
		$this->assertNotFlooding('post');
		$reply = $creator->save();
	
		$this->finalizeItemRatingReply($creator);
	
		if ($this->filter('_xfWithData', 'bool') && $this->request->exists('last_date') && $review->canView())
		{
			$ratingRepo = $this->getRatingRepo();
	
			$lastDate = $this->filter('last_date', 'uint');
	
			/** @var \XF\Mvc\Entity\Finder $itemRatingReplyList */
			$itemRatingReplyList = $ratingRepo->findNewestRepliesForItemRating($review, $lastDate);
			$itemRatingReplies = $itemRatingReplyList->fetch();
	
			// put the posts into oldest-first order
			$itemRatingReplies = $itemRatingReplies->reverse(true);
	
			$viewParams = [
				'review' => $review,
				'itemRatingReplies' => $itemRatingReplies
			];
			$view = $this->view('XenAddons\Showcase:Review\NewItemRatingReplies', 'xa_sc_review_new_replies', $viewParams);
			$view->setJsonParam('lastDate', $itemRatingReplies->last()->reply_date);
			return $view;
		}
		else
		{
			return $this->redirect($this->buildLink('showcase/review-reply', $reply));
		}
	}
	
	public function actionLoadPrevious(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->rating_id);
	
		$repo = $this->getRatingRepo();
	
		$replies = $repo->findItemRatingReplies($review)
			->with('full')
			->where('reply_date', '<', $this->filter('before', 'uint'))
			->order('reply_date', 'DESC')
			->limit(20)
			->fetch()
			->reverse();
	
		if ($replies->count())
		{
			$firstReplyDate = $replies->first()->reply_date;
	
			$moreRepliesFinder = $repo->findItemRatingReplies($review)
				->where('reply_date', '<', $firstReplyDate);
	
			$loadMore = ($moreRepliesFinder->total() > 0);
		}
		else
		{
			$firstReplyDate = 0;
			$loadMore = false;
		}
	
		$viewParams = [
			'reivew' => $review,
			'replies' => $replies,
			'firstReplyDate' => $firstReplyDate,
			'loadMore' => $loadMore
		];
		return $this->view('XenAddons\Showcase:ItemRating\LoadPrevious', 'xa_sc_review_replies', $viewParams);
	}

	protected function redirectToReview(\XenAddons\Showcase\Entity\ItemRating $review)
	{
		$item = $review->Item;

		$newerFinder = $this->getRatingRepo()->findReviewsInItem($item);
		$newerFinder->where('rating_date', '>', $review->rating_date);
		$totalNewer = $newerFinder->total();

		$perPage = $this->options()->xaScReviewsPerPage;
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
			$this->buildLink('showcase/reviews', $item, $params)
			. '#review-' . $review->rating_id
		);
	}

	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('xa_sc_viewing_showcase');
	}
}