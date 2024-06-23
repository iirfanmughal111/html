<?php

namespace XenAddons\Showcase\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class ItemUpdateReply extends AbstractHandler
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
		
			return $visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'viewUpdates');
		}
		else
		{
			return $visitor->hasPermission('xa_showcase', 'viewUpdates');
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
				$visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'editAny') // piggy back off of item permissions
				|| $visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'deleteAny') // piggy back off of item permissions
			);
		}
		else
		{
			return (
				$visitor->hasPermission('xa_showcase', 'editAny') // piggy back off of item permissions
				|| $visitor->hasPermission('xa_showcase', 'deleteAny') // piggy back off of item permissions
			);
		}
	}

	public function setupReportEntityContent(Report $report, Entity $content)
	{
		/** @var \XenAddons\Showcase\Entity\ItemUpdateReply $reply */
		$reply = $content;
		
		$update = $content->ItemUpdate;
		$item = $update->Item;
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
				'item_update_id' => $reply->item_update_id,
				'message' => $reply->message
			],
			'update' => [
				'item_update_id' => $update->item_update_id,
				'title' => $update->title,
				'item_id' => $update->item_id,
				'message' => $update->message
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
		return \XF::phrase('xa_sc_item_update_reply_in_x', [
			'title' => \XF::app()->stringFormatter()->censorText($report->content_info['update']['title']),
			'item_title' => \XF::app()->stringFormatter()->censorText($report->content_info['item']['title'])
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
			'canonical:showcase/update-reply',
			[
				'reply_id' => $info['reply']['reply_id']
			]
		);
	}

	public function getEntityWith()
	{
		return ['ItemUpdate', 'ItemUpdate.Item', 'ItemUpdate.Item.Category'];
	}
}