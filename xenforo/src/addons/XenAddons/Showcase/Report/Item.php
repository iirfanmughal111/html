<?php

namespace XenAddons\Showcase\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class Item extends AbstractHandler
{
	protected function canViewContent(Report $report)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$contentInfo = $report->content_info;
		
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
			return false;
		}
	}

	protected function canActionContent(Report $report)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$contentInfo = $report->content_info;
		
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
			return false;
		}	
	}

	public function setupReportEntityContent(Report $report, Entity $content)
	{
		/** @var \XenAddons\Showcase\Entity\Item $content */
		
		$report->content_user_id = $content->user_id;
		$report->content_info = [
			'category_id' => $content->category_id,
			'category_title' => $content->Category->title,
			'item_id' => $content->item_id,
			'title' => $content->title,
			'message' => $content->message,
			'user_id' => $content->user_id,
			'username' => $content->username
		];
	}

	public function getContentTitle(Report $report)
	{
		$contentInfo = $report->content_info;

		$params = [
			'title' => \XF::app()->stringFormatter()->censorText($contentInfo['title'])
		];
		
		if (!isset($contentInfo['category_title']))
		{
			$contentInfo['category_title'] = 'N/A';
		}

		return \XF::phrase('xa_sc_item_x_in_category_y', $params + [
			'category' => $contentInfo['category_title']
		]);
	}

	public function getContentMessage(Report $report)
	{
		if (isset($report->content_info['message']))
		{
			return $report->content_info['message'];
		}
		else 
		{
			if (isset($report->content_info['description']))
			{
				return $report->content_info['description'];
			}
			else
			{
				return 'N/A';
			}
		}
	}

	public function getContentLink(Report $report)
	{
		return \XF::app()->router()->buildLink('canonical:showcase', $report->content_info);
	}
}