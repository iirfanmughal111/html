<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;
use XF\Mvc\Reply\View;

abstract class AbstractController extends \XF\Pub\Controller\AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$visitor->canViewShowcaseItems())
		{
			throw $this->exception($this->noPermission());
		}
		
		if ($this->options()->xaScOverrideStyle)
		{
			$this->setViewOption('style_id', $this->options()->xaScOverrideStyle);
		}
	}

	protected function postDispatchController($action, ParameterBag $params, AbstractReply &$reply)
	{
		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			$viewParams = $reply->getParams();
			$category = null;

			if (isset($viewParams['category']))
			{
				$category = $viewParams['category'];
			}
			if (isset($viewParams['item']))
			{
				$category = $viewParams['item']->Category;
			}
			if ($category)
			{
				$reply->setContainerKey('scCategory-' . $category->category_id);
				
				if ($category->style_id)
				{
					$reply->setViewOption('style_id', $category->style_id);
				}
			}
		}
	}
	
	/**
	 * @param $itemId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\Item
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableItem($itemId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
	
		$extraWith[] = 'User';
		$extraWith[] = 'Category';
		$extraWith[] = 'Category.Permissions|' . $visitor->permission_combination_id;
		$extraWith[] = 'Discussion';
		$extraWith[] = 'Discussion.Forum';
		$extraWith[] = 'Discussion.Forum.Node';
		$extraWith[] = 'Discussion.Forum.Node.Permissions|' . $visitor->permission_combination_id;
	
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $this->em()->find('XenAddons\Showcase:Item', $itemId, $extraWith);
		if (!$item)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_item_not_found')));
		}
	
		if (!$item->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $item;
	}	
	
	/**
	 * @param integer $pageId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\ItemPage
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewablePage($pageId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
	
		$extraWith[] = 'Item';
		$extraWith[] = 'Item.User';
		$extraWith[] = 'Item.Category';
		$extraWith[] = 'Item.Category.Permissions|' . $visitor->permission_combination_id;
	
		/** @var \XenAddons\Showcase\Entity\ItemPage $page */
		$page = $this->em()->find('XenAddons\Showcase:ItemPage', $pageId, $extraWith);
		if (!$page)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_item_page_not_found')));
		}
	
		if (!$page->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $page;
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

	/**
	 * @param integer $commentId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\Comment
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableComment($commentId, array $extraWith = [])
	{
		/** @var \XenAddons\Showcase\Entity\Comment $comment */
		$comment = $this->em()->find('XenAddons\Showcase:Comment', $commentId, $extraWith);

		if (!$comment)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_comment_not_found')));
		}
		if (!$comment->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		return $comment;
	}
	
	/**
	 * @param integer $ratingId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\ItemRating
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableReview($ratingId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
	
		$extraWith[] = 'Item';
		$extraWith[] = 'Item.User';
		$extraWith[] = 'Item.Category';
		$extraWith[] = 'Item.Category.Permissions|' . $visitor->permission_combination_id;
	
		/** @var \XenAddons\Showcase\Entity\ItemRating $review */
		$review = $this->em()->find('XenAddons\Showcase:ItemRating', $ratingId, $extraWith);
		if (!$review)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_review_not_found')));
		}
	
		if (!$review->canView($error) || !$review->is_review)
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $review;
	}
	
	/**
	 * @param $replyId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\ItemRatingReply
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableReviewReply($replyId, array $extraWith = [])
	{
		$extraWith[] = 'User';
		$extraWith[] = 'ItemRating';
		array_unique($extraWith);
	
		/** @var \XenAddons\Showcase\Entity\ItemRatingReply $reply */
		$reply = $this->em()->find('XenAddons\Showcase:ItemRatingReply', $replyId, $extraWith);
		if (!$reply)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_reply_not_found')));
		}
		if (!$reply->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $reply;
	}	
	
	// THis is used for Ratings Only and will be expanded in a future version!
	/**
	 * @param $ratingId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\ItemRating
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableRating($ratingId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
	
		$extraWith[] = 'Item';
		$extraWith[] = 'Item.User';
		$extraWith[] = 'Item.Category';
		$extraWith[] = 'Item.Category.Permissions|' . $visitor->permission_combination_id;
	
		/** @var \XenAddons\Showcase\Entity\ItemRating $rating */
		$rating = $this->em()->find('XenAddons\Showcase:ItemRating', $ratingId, $extraWith);
		if (!$rating)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_rating_not_found')));
		}
	
		if (!$rating->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $rating;
	}	
	
	/**
	 * @param integer $seriesId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\SeriesItem
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableSeries($seriesId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
	
		$extraWith[] = 'LastItem';
		$extraWith[] = 'User';
	
		/** @var \XenAddons\Showcase\Entity\SeriesItem $series */
		$series = $this->em()->find('XenAddons\Showcase:SeriesItem', $seriesId, $extraWith);
		if (!$series)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_series_not_found')));
		}
	
		if (!$series->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $series;
	}
	
	/**
	 * @param integer $partId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\SeriesPart
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableSeriesPart($partId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
	
		$extraWith[] = 'Series';
		$extraWith[] = 'Series.User';
		$extraWith[] = 'Item';
		$extraWith[] = 'Item.User';
		$extraWith[] = 'Item.Category';
		$extraWith[] = 'Item.Category.Permissions|' . $visitor->permission_combination_id;
	
		/** @var \XenAddons\Showcase\Entity\SeriesPart $part */
		$part = $this->em()->find('XenAddons\Showcase:SeriesPart', $partId, $extraWith);
		if (!$part)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_series_part_not_found')));
		}
	
		if (!$part->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $part;
	}
		
	/**
	 * @param $updateId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\ItemUpdate
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableUpdate($itemUpdateId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
	
		$extraWith[] = 'Item';
		$extraWith[] = 'Item.User';
		$extraWith[] = 'Item.Category';
		$extraWith[] = 'Item.Category.Permissions|' . $visitor->permission_combination_id;
	
		/** @var \XenAddons\Showcase\Entity\ItemUpdate $update */
		$update = $this->em()->find('XenAddons\Showcase:ItemUpdate', $itemUpdateId, $extraWith);
		if (!$update)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_update_not_found')));
		}
	
		if (!$update->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $update;
	}
	
	/**
	 * @param $replyId
	 * @param array $extraWith
	 *
	 * @return \XenAddons\Showcase\Entity\ItemUpdateReply
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableUpdateReply($replyId, array $extraWith = [])
	{
		$extraWith[] = 'User';
		$extraWith[] = 'ItemUpdate';
		array_unique($extraWith);
	
		/** @var \XenAddons\Showcase\Entity\ItemUpdateReply $reply */
		$reply = $this->em()->find('XenAddons\Showcase:ItemUpdateReply', $replyId, $extraWith);
		if (!$reply)
		{
			throw $this->exception($this->notFound(\XF::phrase('xa_sc_requested_reply_not_found')));
		}
		if (!$reply->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		return $reply;
	}

	/**
	 * @param $threadId
	 * @param array $extraWith
	 *
	 * @return \XF\Entity\Thread
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableThread($threadId, array $extraWith = [])
	{
		$visitor = \XF::visitor();
	
		$extraWith[] = 'Forum';
		$extraWith[] = 'Forum.Node';
		$extraWith[] = 'Forum.Node.Permissions|' . $visitor->permission_combination_id;
	
		/** @var \XF\Entity\Thread $thread */
		$thread = $this->em()->find('XF:Thread', $threadId, $extraWith);
		if (!$thread)
		{
			throw $this->exception($this->notFound(\XF::phrase('requested_thread_not_found')));
		}
	
		if (!$thread->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		$this->plugin('XF:Node')->applyNodeContext($thread->Forum->Node);
		$this->setContentKey('thread-' . $thread->thread_id);
	
		return $thread;
	}

	/**
	 * @return \XenAddons\Showcase\Repository\Category
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('XenAddons\Showcase:Category');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\Comment
	 */
	protected function getCommentRepo()
	{
		return $this->repository('XenAddons\Showcase:Comment');
	}

	/**
	 * @return \XenAddons\Showcase\Repository\Item
	 */
	protected function getItemRepo()
	{
		return $this->repository('XenAddons\Showcase:Item');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\ItemPage
	 */
	protected function getPageRepo()
	{
		return $this->repository('XenAddons\Showcase:ItemPage');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\ItemUpdate
	 */
	protected function getItemUpdateRepo()
	{
		return $this->repository('XenAddons\Showcase:ItemUpdate');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\ItemRating
	 */
	protected function getRatingRepo()
	{
		return $this->repository('XenAddons\Showcase:ItemRating');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\Series
	 */
	protected function getSeriesRepo()
	{
		return $this->repository('XenAddons\Showcase:Series');
	}
	
	/**
	 * @return \XenAddons\Showcase\Repository\SeriesPart
	 */
	protected function getSeriesPartRepo()
	{
		return $this->repository('XenAddons\Showcase:SeriesPart');
	}	
}