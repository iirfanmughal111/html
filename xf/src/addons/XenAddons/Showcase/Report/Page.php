<?php

namespace XenAddons\Showcase\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class Page extends AbstractHandler
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
		
			return $visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'view');
		}
		else
		{
			return $visitor->hasPermission('xa_showcase', 'view');
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
				$visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'editAny')
				|| $visitor->hasShowcaseItemCategoryPermission($contentInfo['category_id'], 'deleteAny')
			);
		}
		else
		{
			return (
				$visitor->hasPermission('xa_showcase', 'editAny')
				|| $visitor->hasPermission('xa_showcase', 'deleteAny')
			);
		}
	}

	public function setupReportEntityContent(Report $report, Entity $content)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $page */
		$page = $content;
		$item = $content->Item;
		$category = $item->Category;

		if (!empty($item->prefix_id))
		{
			$itemTitle = $item->Prefix->title . ' - ' . $item->title;
		}
		else
		{
			$itemTitle = $item->title;
		}

		$report->content_user_id = $page->user_id;
		$report->content_info = [
			'page' => [
				'page_id' => $page->page_id,
				'item_id' => $page->item_id,
				'title' => $page->title,
				'message' => $page->message,
				'user_id' => $page->user_id,
				'username' => $page->username
			],
			'item' => [
				'item_id' => $item->item_id,
				'title' => $itemTitle,
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
		return \XF::phrase('xa_sc_item_page_in_x', [
			'page_title' => \XF::app()->stringFormatter()->censorText($report->content_info['page']['title']),
			'item_title' => \XF::app()->stringFormatter()->censorText($report->content_info['item']['title'])
		]);
	}

	public function getContentMessage(Report $report)
	{
		if (isset($report->content_info['page']['message']))
		{
			return $report->content_info['page']['message'];
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
			'canonical:showcase/page',
			[
				'item_id' => $info['item']['item_id'],
				'item_title' => $info['item']['title'],
				'page_id' => $info['page']['page_id'],
				'title' => $info['page']['title'],
			]
		);
	}

	public function getEntityWith()
	{
		return ['Item', 'Item.Category'];
	}
}