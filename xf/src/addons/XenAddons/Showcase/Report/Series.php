<?php

namespace XenAddons\Showcase\Report;

use XF\Entity\Report;
use XF\Mvc\Entity\Entity;
use XF\Report\AbstractHandler;

class Series extends AbstractHandler
{
	protected function canViewContent(Report $report)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$contentInfo = $report->content_info;
		
		if (!method_exists($visitor, 'hasShowcaseSeriesPermission'))
		{
			return false;
		}
		
		return $visitor->hasShowcaseSeriesPermission('view');
	}

	protected function canActionContent(Report $report)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$contentInfo = $report->content_info;

		if (!method_exists($visitor, 'hasShowcaseSeriesPermission'))
		{
			return false;
		}
		
		return (
			$visitor->hasShowcaseSeriesPermission('editAny')
			|| $visitor->hasShowcaseSeriesPermission('deleteAny')
		);
	}

	public function setupReportEntityContent(Report $report, Entity $content)
	{
		/** @var \XenAddons\Showcase\Entity\SeriesItem $content */
		
		$report->content_user_id = $content->user_id;
		$report->content_info = [
			'series_id' => $content->series_id,
			'title' => $content->title,
			'message' => $content->message,
			'description' => $content->description,
			'user_id' => $content->user_id,
			'username' => $content->User ? $content->User->username : '',
		];
	}

	public function getContentTitle(Report $report)
	{
		$contentInfo = $report->content_info;

		$params = [
			'title' => \XF::app()->stringFormatter()->censorText($contentInfo['title'])
		];

		return \XF::phrase('xa_sc_series_x', $params + []);
	}

	public function getContentMessage(Report $report)
	{
		if (isset($report->content_info['message']))
		{
			return $report->content_info['message'];
		}
		elseif (isset($report->content_info['description']))
		{
			return $report->content_info['description'];
		}
		else
		{
			return 'N/A';
		}			
	}

	public function getContentLink(Report $report)
	{
		return \XF::app()->router()->buildLink('canonical:showcase/series', $report->content_info);
	}
}