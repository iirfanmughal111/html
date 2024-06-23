<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\PrintableException;
use XF\Util\Arr;

class ItemRating extends Repository
{
	public function findRatingsInItem(\XenAddons\Showcase\Entity\Item $item, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemRating $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemRating');
		$finder->inItem($item, $limits)
			->where('is_review', 0)
			->setDefaultOrder('rating_date', 'desc');
	
		return $finder;
	}
	
	public function findReviewsInItem(\XenAddons\Showcase\Entity\Item $item, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemRating $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemRating');
		$finder->inItem($item, $limits)
			->where('is_review', 1)
			->setDefaultOrder('rating_date', 'desc');

		return $finder;
	}

	public function findLatestReviews(array $viewableCategoryIds = null, $cutOffDays = null)
	{
		/** @var \XenAddons\Showcase\Finder\ItemRating $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemRating');

		$finder->where([
				'Item.item_state' => 'visible',
				'rating_state' => 'visible',
				'is_review' => 1
			])
			->with('Item', true)
			->with(['Item.Category', 'User'])
			->setDefaultOrder('rating_date', 'desc');

		if (is_array($viewableCategoryIds))
		{
			$finder->where('Item.category_id', $viewableCategoryIds);
		}
		else
		{
			$finder->with('Item.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		if ($cutOffDays)
		{
			$cutOffDate = \XF::$time - ($cutOffDays * 86400);
			$finder->where('rating_date', '>', $cutOffDate);
		}
		else
		{
			if ($this->options()->xaScLatestReviewsCutOffDays > 0)
			{
				$cutOffDate = \XF::$time - ($this->options()->xaScLatestReviewsCutOffDays * 86400);
				$finder->where('rating_date', '>', $cutOffDate);
			}	
		}

		return $finder;
	}
	
	public function findLatestReviewsForWidget(array $viewableCategoryIds = null, $cutOffDays = null)
	{
		/** @var \XenAddons\Showcase\Finder\ItemRating $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemRating');
	
		$finder->where([
				'Item.item_state' => 'visible',
				'rating_state' => 'visible',
				'is_review' => 1
			])
			->with('Item', true)
			->with(['Item.Category', 'User'])
			->setDefaultOrder('rating_date', 'desc');
	
		if (is_array($viewableCategoryIds))
		{
			$finder->where('Item.category_id', $viewableCategoryIds);
		}
		else
		{
			$finder->with('Item.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		if ($cutOffDays)
		{
			$cutOffDate = \XF::$time - ($cutOffDays * 86400);
			$finder->where('rating_date', '>', $cutOffDate);
		}
	
		return $finder;
	}	

	/**
	 * Returns the ratings for a given item by a given user. This should normally return one.
	 * In general, only a bug would have it return more than one but the code is written so that this can be resolved.
	 *
	 * @param $itemId
	 * @param $userId
	 *
	 * @return \XF\Mvc\Entity\ArrayCollection
	 */
	public function getRatingsForItemByUser($itemId, $userId)
	{
		/** @var \XenAddons\Showcase\Finder\ItemRating $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemRating');
		$finder->where([
			'item_id' => $itemId,
			'user_id' => $userId,
			'is_review' => 0
		])->order('rating_date', 'desc');

		return $finder->fetch();
	}
	
	public function findReviewsForUser(\XF\Entity\User $user)
	{
		/** @var \XenAddons\Showcase\Finder\ItemRating $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemRating');
		$finder->where([
			'user_id' => $user->user_id,
			'rating_state' => 'visible',
			'is_review' => 1
		]);
	
		return $finder;
	}
	
	public function findReviewsForAuthorReviewList(\XF\Entity\User $user, array $viewableCategoryIds = null)
	{
		/** @var \XenAddons\Showcase\Finder\ItemRating $finder */
		$reviewFinder = $this->finder('XenAddons\Showcase:ItemRating');
	
		// we don't want to fetch any anonymous reviews in this function!
	
		$reviewFinder->where([
				'user_id' => $user->user_id,
				'Item.item_state' => 'visible',
				'rating_state' => 'visible',
				'is_review' => 1,
				'is_anonymous' => 0,
			])
			->with('Item', true)
			->with(['Item.Category', 'User'])
			->setDefaultOrder('rating_date', 'desc');
	
		if (is_array($viewableCategoryIds))
		{
			$reviewFinder->where('Item.category_id', $viewableCategoryIds);
		}
		else
		{
			$reviewFinder->with('Item.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
	
		if ($user)
		{
			$reviewFinder->with('Reactions|' . $user->user_id);
			$reviewFinder->with('Item.ReplyBans|' . $user->user_id);
		}
	
		return $reviewFinder;
	}	
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemRating $rating
	 * @param array $limits
	 *
	 * @return \XenAddons\Showcase\Finder\ItemRatingReply
	 */
	public function findItemRatingReplies(\XenAddons\Showcase\Entity\ItemRating $rating, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemRatingReply $replyFinder */
		$replyFinder = $this->finder('XenAddons\Showcase:ItemRatingReply');
		$replyFinder->setDefaultOrder('reply_date');
		$replyFinder->forItemRating($rating, $limits);
	
		return $replyFinder;
	}
	
	public function findNewestRepliesForItemRating(\XenAddons\Showcase\Entity\ItemRating $rating, $newerThan, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemRatingReply $replyFinder */
		$replyFinder = $this->finder('XenAddons\Showcase:ItemRatingReply');
		$replyFinder
			->setDefaultOrder('reply_date', 'DESC')
			->forItemRating($rating, $limits)
			->newerThan($newerThan);
	
		return $replyFinder;
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemRating[] $itemRatings
	 */
	public function addRepliesToItemRatings($itemRatings)
	{
		$replyFinder = $this->finder('XenAddons\Showcase:ItemRatingReply');
	
		$visitor = \XF::visitor();
	
		$ids = [];
		foreach ($itemRatings AS $itemRatingId => $itemRating)
		{
			$replyIds = $itemRating->latest_reply_ids;
			foreach ($replyIds AS $replyId => $state)
			{
				$replyId = intval($replyId);
	
				switch ($state[0])
				{
					case 'visible':
						$ids[] = $replyId;
						break;
	
					case 'moderated':
						if ($itemRating->canViewModeratedReplies())
						{
							// can view all moderated comments
							$ids[] = $replyId;
						}
						else if ($visitor->user_id && $visitor->user_id == $state[1])
						{
							// can view your own moderated comments
							$ids[] = $replyId;
						}
						break;
	
					case 'deleted':
						if ($itemRating->canViewDeletedReplies())
						{
							$ids[] = $replyId;
	
							$replyFinder->with('DeletionLog');
						}
						break;
				}
			}
		}
	
		if ($ids)
		{
			$replyFinder->with('full');
	
			$replies = $replyFinder
				->where('reply_id', $ids)
				->order('reply_date')
				->fetch()
				->groupBy('rating_id');
	
			foreach ($itemRatings AS $itemRatingId => $itemRating)
			{
				$itemRatingReplies = isset($replies[$itemRatingId]) ? $replies[$itemRatingId] : [];
				$itemRatingReplies = $this->em->getBasicCollection($itemRatingReplies)
					->filterViewable()
					->slice(-3, 3);
	
				$itemRating->setLatestReplies($itemRatingReplies->toArray());
			}
		}
	
		return $itemRatings;
	}
	
	public function addRepliesToItemRating(\XenAddons\Showcase\Entity\ItemRating $rating)
	{
		$id = $rating->rating_id;
		$result = $this->addRepliesToItemRatings([$id => $rating]);
		return $result[$id];
	}
	
	public function getLatestReplyCache(\XenAddons\Showcase\Entity\ItemRating $rating)
	{
		$replies = $this->finder('XenAddons\Showcase:ItemRatingReply')
			->where('rating_id', $rating->rating_id)
			->order('reply_date', 'DESC')
			->limit(20)
			->fetch();
	
		$visCount = 0;
		$latestReplies = [];
	
		/** @var \XenAddons\Showcase\Entity\ItemRatingReply $reply */
		foreach ($replies AS $replyId => $reply)
		{
			if ($reply->reply_state == 'visible')
			{
				$visCount++;
			}
	
			$latestReplies[$replyId] = [$reply->reply_state, $reply->user_id];
	
			if ($visCount === 3)
			{
				break;
			}
		}
	
		return array_reverse($latestReplies, true);
	}
	
	public function getReviewAttachmentConstraints()
	{
		$options = $this->options();
	
		return [
			'extensions' => Arr::stringToArray($options->xaScReviewAllowedFileExtensions),
			'size' => $options->xaScReviewAttachmentMaxFileSize * 1024,
			'width' => $options->attachmentMaxDimensions['width'],
			'height' => $options->attachmentMaxDimensions['height']
		];
	}
	
	public function getReviewsImagesForItem(\XenAddons\Showcase\Entity\Item $item, $forItemGallery = false)
	{
		$db = $this->db();
	
		$ids = $db->fetchAllColumn("
			SELECT rating_id
			FROM xf_xa_sc_item_rating
			WHERE item_id = ?
			AND is_review = 1	
			AND rating_state = 'visible'
			AND attach_count > 0
			ORDER BY rating_id
			", $item->item_id
		);
	
		// when fetching for the Reviews Images Gallery, we only want to fetch if there are at least 2 reviews with images.
		// when fetching for inclusion in the Item Gallery, we want to fetch even if there is only 1 review with images (which is what the $forItemGallery is used for).
	
		if (($ids && count($ids) > 1) || ($ids && $forItemGallery))
		{
			$attachments = $this->finder('XF:Attachment')
				->where([
					'content_type' => 'sc_rating',
					'content_id' => $ids
				])
				->order('attach_date')
				->fetch();
		}
		else
		{
			$attachments = $this->em->getEmptyCollection();
		}
	
		return $attachments;
	}

	public function sendModeratorActionAlert(\XenAddons\Showcase\Entity\ItemRating $rating, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null)
	{
		$item = $rating->Item;

		if (!$item || !$item->user_id || !$item->User)
		{
			return false;
		}

		if (!$forceUser)
		{
			if (!$rating->user_id || !$rating->User)
			{
				return false;
			}
		
			$forceUser = $rating->User;
		}
		
		$extra = array_merge([
			'title' => $item->title,
			'prefix_id' => $item->prefix_id,
			'link' => $this->app()->router('public')->buildLink('nopath:showcase/review', $rating),
			'itemLink' => $this->app()->router('public')->buildLink('nopath:showcase', $item),
			'reason' => $reason
		], $extra);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0, '',
			'user', $forceUser->user_id,
			"sc_rating_{$action}", $extra,
			['dependsOnAddOnId' => 'XenAddons/Showcase']
		);

		return true;
	}

	public function sendReviewAlertToItemAuthor(\XenAddons\Showcase\Entity\ItemRating $rating)
	{
		if (!$rating->isVisible() || !$rating->is_review)
		{
			return false;
		}

		$item = $rating->Item;
		$itemAuthor = $item->User;

		if (!$itemAuthor)
		{
			return false;
		}

		if ($rating->is_anonymous)
		{
			$senderId = 0;
			$senderName = \XF::phrase('anonymous')->render('raw');
		}
		else
		{
			$senderId = $rating->user_id;
			$senderName = $rating->User ? $rating->User->username : \XF::phrase('unknown')->render('raw');
		}

		$alertRepo = $this->repository('XF:UserAlert');
		return $alertRepo->alert(
			$itemAuthor, $senderId, $senderName, 'sc_rating', $rating->rating_id, 'review'
		);
	}

	
	// This is for review replies only!
	
	public function sendReplyModeratorActionAlert(\XenAddons\Showcase\Entity\ItemRatingReply $reply, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null)
	{
		if (!$forceUser)
		{
			if (!$reply->user_id || !$reply->User)
			{
				return false;
			}
		
			$forceUser = $reply->User;
		}
	
		/** @var \XenAddons\Showcase\Entity\ItemRating $itemRating */
		$itemRating = $reply->ItemRating;
		$item = $itemRating->Item;
		if (!$itemRating)
		{
			return false;
		}
	
		$router = $this->app()->router('public');
	
		$extra = array_merge([
			'title' => $item->title,
			'prefix_id' => $item->prefix_id,
			'link' => $router->buildLink('nopath:showcase/review-reply', $reply),
			'itemLink' => $router->buildLink('nopath:showcase', $item),
			'reviewLink' => $router->buildLink('nopath:showcase/review', $itemRating),
			'reason' => $reason
		], $extra);
	
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0, '',
			'user', $forceUser->user_id,
			"sc_rating_reply_{$action}", $extra
		);
	
		return true;
	}
	
	
	public function getUserReviewCount($userId)
	{
		return $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_xa_sc_item_rating
			WHERE user_id = ?
				AND is_review = 1
				AND rating_state = 'visible'
		", $userId);
	}
}