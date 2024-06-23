<?php

namespace XenAddons\Showcase\ModeratorLog;

use XF\Entity\ModeratorLog;
use XF\ModeratorLog\AbstractHandler;
use XF\Mvc\Entity\Entity;

class SeriesPart extends AbstractHandler
{
	public function isLoggable(Entity $content, $action, \XF\Entity\User $actor)
	{
		switch ($action)
		{
			case 'edit':
		}

		return parent::isLoggable($content, $action, $actor);
	}

	protected function getLogActionForChange(Entity $content, $field, $newValue, $oldValue)
	{
		switch ($field)
		{
			case 'display_order':
				return 'edit';
		}

		return false;
	}

	protected function setupLogEntityContent(ModeratorLog $log, Entity $content)
	{
		/** @var \XenAddons\Showcase\Entity\SeriesPart $content */
		$series = $content->Series;
		
		/** @var \XF\Entity\Thread $content */
		$log->content_user_id = $content->user_id;
		$log->content_username = $content->User ? $content->User->username : '';
		$log->content_title = $content->Item->title;
		$log->content_url = \XF::app()->router('public')->buildLink('nopath:showcase/series', $content);
		$log->discussion_content_type = 'sc_series_part';
		$log->discussion_content_id = $content->series_id;
	}

	protected function getActionPhraseParams(ModeratorLog $log)
	{
		if ($log->action == 'edit')
		{
			return ['elements' => implode(', ', array_keys($log->action_params))];
		}
		else
		{
			return parent::getActionPhraseParams($log);
		}
	}
}