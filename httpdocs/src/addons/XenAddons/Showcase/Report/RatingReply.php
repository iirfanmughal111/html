<?php

namespace XenAddons\Showcase\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class RatingReply extends AbstractHandler
{
	protected function canViewContent(Report $report)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$contentInfo = $report->content_info['item'];
		
		if (!empty($contentInfo['category_id']))
		{
			if (!method_exists($visitor, 'hasShowcaseItemCategoryPermission'))
			{
				return false;
			}
		
			return $visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'viewReviews');
		}
		else
		{
			return $visitor->hasPermission('xa_showcase', 'viewReviews');
		}
	}

	protected function canActionContent(Report $report)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();

		$contentInfo = $report->content_info['item'];
		
		if (!empty($contentInfo['category_id']))
		{
			if (!method_exists($visitor, 'hasShowcaseItemCategoryPermission'))
			{
				return false;
			}
		
			return (
				$visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'editAnyReview')
				|| $visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'deleteAnyReview')
			);
		}
		else
		{
			return (
				$visitor->hasPermission('xa_showcase', 'editAnyReview')
				|| $visitor->hasPermission('xa_showcase', 'deleteAnyReview')
			);
		}
	}

	public function setupReportEntityContent(Report $report, Entity $content)
	{
		/** @var \XenAddons\Showcase\Entity\ItemRatingReply $reply */
		$reply = $content;
		
		$rating = $content->ItemRating;
		$item = $rating->Item;
		$category = $item->Category;

		if (!empty($item->prefix_id))
		{
			$title = $item->Prefix->title . ' - ' . $item->title;
		}
		else
		{
			$title = $item->title;
		}

		$report->content_user_id = $reply->user_id;
		$report->content_info = [
			'reply' => [
				'reply_id' => $reply->reply_id,
				'rating_id' => $reply->rating_id,
				'message' => $reply->message
			],
			'rating' => [
				'rating_id' => $rating->rating_id,
				'item_id' => $rating->item_id,
				'rating' => $rating->rating,
				'message' => $rating->message
			],
			'item' => [
				'item_id' => $item->item_id,
				'title' => $title,
				'prefix_id' => $item->prefix_id,
				'category_id' => $item->category_id,
				'user_id' => $item->user_id,
				'username' => $item->username
			],
			'category' => [
				'category_id' => $category->category_id,
				'title' => $category->title
			]
		];
	}

	public function getContentTitle(Report $report)
	{
		return \XF::phrase('xa_sc_item_review_reply_in_x', [
			'title' => \XF::app()->stringFormatter()->censorText($report->content_info['item']['title'])
		]);
	}

	public function getContentMessage(Report $report)
	{
		if (isset($report->content_info['reply']['message']))
		{
			return $report->content_info['reply']['message'];
		}
		else
		{
			if (isset($report->content_info['message']))
			{
				return $report->content_info['message'];
			}
			else
			{
				return 'N/A';
			}
		}
	}

	public function getContentLink(Report $report)
	{
		$info = $report->content_info;

		return \XF::app()->router()->buildLink(
			'canonical:showcase/review-reply',
			[
				'reply_id' => $info['reply']['reply_id']
			]
		);
	}

	public function getEntityWith()
	{
		return ['ItemRating', 'ItemRating.Item', 'ItemRating.Item.Category'];
	}
}