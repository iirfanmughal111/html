<?php

namespace XenAddons\Showcase\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class ItemReplace extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('showcase');
	}
	
	public function actionIndex()
	{
		return $this->view('XenAddons\Showcase:ItemReplace\Index', 'xa_sc_itemreplace_index');
	}

	public function actionReplace()
	{
		$this->assertPostOnly();

		$input = $this->filter([
			'quick_find' => 'str',
			'regex' => 'str',
			'replace' => 'str',
			'commit' => 'uint'
		]);
		
		
		// Items
		
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
		
		/** @var \XenAddons\Showcase\Entity\Item[] $items */
		$items = $itemFinder
			->whereOr(
				['message', 'like', $itemFinder->escapeLike($input['quick_find'], '%?%')],
				['message_s2', 'like', $itemFinder->escapeLike($input['quick_find'], '%?%')],
				['message_s3', 'like', $itemFinder->escapeLike($input['quick_find'], '%?%')],
				['message_s4', 'like', $itemFinder->escapeLike($input['quick_find'], '%?%')],
				['message_s5', 'like', $itemFinder->escapeLike($input['quick_find'], '%?%')],
				['message_s6', 'like', $itemFinder->escapeLike($input['quick_find'], '%?%')]
			)
			->order('item_id')
			->fetch();		
		
		$outputItems = [];
		foreach ($items AS $itemId => $item)
		{
			if (
				preg_match_all($input['regex'], $item->message, $matches)
				|| preg_match_all($input['regex'], $item->message_s2, $matches)
				|| preg_match_all($input['regex'], $item->message_s3, $matches)
				|| preg_match_all($input['regex'], $item->message_s4, $matches)
				|| preg_match_all($input['regex'], $item->message_s5, $matches)
				|| preg_match_all($input['regex'], $item->message_s6, $matches)
			)
			{
				$outputItems[$itemId] = $item->toArray();
				$outputItems[$itemId]['found'] = $matches[0];
				$outputItems[$itemId]['replaced'] = preg_replace($input['regex'], $input['replace'], $outputItems[$itemId]['found']);

				$message = preg_replace($input['regex'], $input['replace'], $item->message);
				$message_s2 = preg_replace($input['regex'], $input['replace'], $item->message_s2);
				$message_s3 = preg_replace($input['regex'], $input['replace'], $item->message_s3);
				$message_s4 = preg_replace($input['regex'], $input['replace'], $item->message_s4);
				$message_s5 = preg_replace($input['regex'], $input['replace'], $item->message_s5);
				$message_s6 = preg_replace($input['regex'], $input['replace'], $item->message_s6);

				if ($input['commit'])
				{
					/** @var \XenAddons\Showcase\Service\Item\Edit $editor */
					$editor = $this->service('XenAddons\Showcase:Item\Edit', $item);

					$editor->setIsAutomated();
					$editor->logEdit(false);
					$editor->logHistory(false);
					$editor->setMessage($message, false);
					$editor->setMessageS2($message_s2, false);
					$editor->setMessageS3($message_s3, false);
					$editor->setMessageS4($message_s4, false);
					$editor->setMessageS5($message_s5, false);
					$editor->setMessageS6($message_s6, false);

					$editor->save();
				}
			}
		}
		if ($input['commit'])
		{
			$outputItems = []; // this will clear the results from the form after processing. 
		}
		
		
		// Item Updates		
		
		$itemUpdateFinder = $this->finder('XenAddons\Showcase:ItemUpdate');
		
		/** @var \XenAddons\Showcase\Entity\ItemUpdate[] $itemUpdates */
		$itemUpdates = $itemUpdateFinder
			->where(
				'message', 'like',
				$itemUpdateFinder->escapeLike(
					$input['quick_find'], '%?%'
				)
			)
			->order('item_update_id')
			->fetch();
		
		$outputItemUpdates = [];
		foreach ($itemUpdates AS $itemUpdateId => $itemUpdate)
		{
			if (preg_match_all($input['regex'], $itemUpdate->message, $matches))
			{
				$outputItemUpdates[$itemUpdateId] = $itemUpdate->toArray();
				$outputItemUpdates[$itemUpdateId]['found'] = $matches[0];
				$outputItemUpdates[$itemUpdateId]['replaced'] = preg_replace($input['regex'], $input['replace'], $outputItemUpdates[$itemUpdateId]['found']);
		
				$message = preg_replace($input['regex'], $input['replace'], $itemUpdate->message);
		
				if ($input['commit'])
				{
					/** @var \XenAddons\Showcase\Service\ItemUpdate\Edit $editor */
					$editor = $this->service('XenAddons\Showcase:ItemUpdate\Edit', $itemUpdate);
		
					$editor->setIsAutomated();
					$editor->logEdit(false);
					$editor->logHistory(false);
					$editor->setMessage($message, false);
		
					$editor->save();
				}
			}
		}
		if ($input['commit'])
		{
			$outputItemUpdates = []; // this will clear the results from the form after processing.
		}
		
		
		// Item Update Replies
		
		$itemUpdateReplyFinder = $this->finder('XenAddons\Showcase:ItemUpdateReply');
		
		/** @var \XenAddons\Showcase\Entity\ItemUpdateReply[] $itemUpdateReplies */
		$itemUpdateReplies = $itemUpdateReplyFinder
			->where(
				'message', 'like',
				$itemUpdateFinder->escapeLike(
					$input['quick_find'], '%?%'
				)
			)
			->order('reply_id')
			->fetch();
		
		$outputItemUpdateReplies = [];
		foreach ($itemUpdateReplies AS $itemUpdateReplyId => $itemUpdateReply)
		{
			if (preg_match_all($input['regex'], $itemUpdateReply->message, $matches))
			{
				$outputItemUpdateReplies[$itemUpdateReplyId] = $itemUpdateReply->toArray();
				$outputItemUpdateReplies[$itemUpdateReplyId]['found'] = $matches[0];
				$outputItemUpdateReplies[$itemUpdateReplyId]['replaced'] = preg_replace($input['regex'], $input['replace'], $outputItemUpdateReplies[$itemUpdateReplyId]['found']);
		
				$message = preg_replace($input['regex'], $input['replace'], $itemUpdateReply->message);
		
				if ($input['commit'])
				{
					/** @var \XenAddons\Showcase\Service\ItemUpdateReply\Editor $editor */
					$editor = $this->service('XenAddons\Showcase:ItemUpdateReply\Editor', $itemUpdateReply);
		
					$editor->setIsAutomated();
					$editor->logEdit(false);
					$editor->logHistory(false);
					$editor->setMessage($message, false);
		
					$editor->save();
				}
			}
		}
		if ($input['commit'])
		{
			$outputItemUpdateReplies = []; // this will clear the results from the form after processing.
		}		
		
		
		
		// Item Comments
		
		$commentFinder = $this->finder('XenAddons\Showcase:Comment');
		
		/** @var \XenAddons\Showcase\Entity\Comment[] $comments */
		$comments = $commentFinder
			->where(
				'message', 'like',
				$commentFinder->escapeLike(
					$input['quick_find'], '%?%'
				)
			)
			->order('comment_id')
			->fetch();
		
		$outputComments = [];
		foreach ($comments AS $commentId => $comment)
		{
			if (preg_match_all($input['regex'], $comment->message, $matches))
			{
				$outputComments[$commentId] = $comment->toArray();
				$outputComments[$commentId]['found'] = $matches[0];
				$outputComments[$commentId]['replaced'] = preg_replace($input['regex'], $input['replace'], $outputComments[$commentId]['found']);
		
				$message = preg_replace($input['regex'], $input['replace'], $comment->message);
		
				if ($input['commit'])
				{
					/** @var \XenAddons\Showcase\Service\Comment\Editor $editor */
					$editor = $this->service('XenAddons\Showcase:Comment\Editor', $comment);
		
					$editor->setIsAutomated();
					$editor->logEdit(false);
					$editor->logHistory(false);
					$editor->setMessage($message, false);
		
					$editor->save();
				}
			}
		}
		if ($input['commit'])
		{
			$outputComments = []; // this will clear the results from the form after processing.
		}
				
		
		// Item Reviews
		
		$itemRatingFinder = $this->finder('XenAddons\Showcase:ItemRating');
		
		/** @var \XenAddons\Showcase\Entity\ItemRating[] $reviews */
		$reviews = $itemRatingFinder
			->where(
				'message', 'like',
				$itemRatingFinder->escapeLike(
					$input['quick_find'], '%?%'
				)
			)
			->where('is_review', 1)
			->order('rating_id')
			->fetch();
		
		$outputReviews = [];
		foreach ($reviews AS $ratingId => $review)
		{
			if (preg_match_all($input['regex'], $review->message, $matches))
			{
				$outputReviews[$ratingId] = $review->toArray();
				$outputReviews[$ratingId]['found'] = $matches[0];
				$outputReviews[$ratingId]['replaced'] = preg_replace($input['regex'], $input['replace'], $outputReviews[$ratingId]['found']);
		
				$message = preg_replace($input['regex'], $input['replace'], $review->message);
		
				if ($input['commit'])
				{
					/** @var \XenAddons\Showcase\Service\Review\Edit $editor */
					$editor = $this->service('XenAddons\Showcase:Review\Edit', $review);
		
					$editor->setIsAutomated();
					$editor->logEdit(false);
					$editor->logHistory(false);
					$editor->setMessage($message, false);
		
					$editor->save();
				}
			}
		}	
		if ($input['commit'])
		{
			$outputReviews = []; // this will clear the results from the form after processing.
		}	
		
		
		// Item Review Replies
		
		$itemRatingReplyFinder = $this->finder('XenAddons\Showcase:ItemRatingReply');
		
		/** @var \XenAddons\Showcase\Entity\ItemRatingReply[] $replies */
		$reviewReplies = $itemRatingReplyFinder
			->where(
				'message', 'like',
				$itemRatingReplyFinder->escapeLike(
					$input['quick_find'], '%?%'
				)
			)
			->order('reply_id')
			->fetch();
		
		$outputReviewReplies = [];
		foreach ($reviewReplies AS $reviewReplyId => $reviewReply)
		{
			if (preg_match_all($input['regex'], $reviewReply->message, $matches))
			{
				$outputReviewReplies[$reviewReplyId] = $reviewReply->toArray();
				$outputReviewReplies[$reviewReplyId]['found'] = $matches[0];
				$outputReviewReplies[$reviewReplyId]['replaced'] = preg_replace($input['regex'], $input['replace'], $outputReviewReplies[$reviewReplyId]['found']);
		
				$message = preg_replace($input['regex'], $input['replace'], $reviewReply->message);
		
				if ($input['commit'])
				{
					/** @var \XenAddons\Showcase\Service\ItemRatingReply\Editor $editor */
					$editor = $this->service('XenAddons\Showcase:ItemRatingReply\Editor', $reviewReply);
		
					$editor->setIsAutomated();
					$editor->logEdit(false);
					$editor->logHistory(false);
					$editor->setMessage($message, false);
		
					$editor->save();
				}
			}
		}
		if ($input['commit'])
		{
			$outputReviewReplies = []; // this will clear the results from the form after processing.
		}

		$viewParams = [
			'input' => $input,
			'items' => $outputItems,
			'itemUpdates' => $outputItemUpdates,
			'itemUpdateReplies' => $outputItemUpdateReplies,
			'itemComments' => $outputComments,
			'itemReviews' => $outputReviews,
			'itemReviewReplies' => $outputReviewReplies
		];
		return $this->view('XenAddons\Showcase:ItemReplace\Index', 'xa_sc_itemreplace_index', $viewParams);
	}
}