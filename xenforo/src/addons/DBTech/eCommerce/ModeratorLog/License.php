<?php

namespace DBTech\eCommerce\ModeratorLog;

use XF\Entity\ModeratorLog;
use XF\ModeratorLog\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class License
 *
 * @package DBTech\eCommerce\ModeratorLog
 */
class License extends AbstractHandler
{
	/**
	 * @param Entity $content
	 * @param $action
	 * @param \XF\Entity\User $actor
	 *
	 * @return bool
	 */
	public function isLoggable(Entity $content, $action, \XF\Entity\User $actor): bool
	{
		switch ($action)
		{
			case 'license_fields':
				if ($actor->user_id == $content->user_id)
				{
					return false;
				}
		}

		return parent::isLoggable($content, $action, $actor);
	}
	
	/**
	 * @param Entity $content
	 * @param $field
	 * @param $newValue
	 * @param $oldValue
	 *
	 * @return array|bool|string
	 */
	protected function getLogActionForChange(Entity $content, $field, $newValue, $oldValue)
	{
		switch ($field)
		{
			case 'license_fields':
				return 'license_fields_edit';

			case 'license_state':
				if ($newValue == 'visible')
				{
					if ($oldValue == 'moderated')
					{
						return 'approve';
					}
					
					if ($oldValue == 'deleted')
					{
						return 'undelete';
					}
				}
				elseif ($newValue == 'deleted')
				{
					$reason = $content->DeletionLog ? $content->DeletionLog->delete_reason : '';
					return ['delete_soft', ['reason' => $reason]];
				}
				elseif ($newValue == 'moderated')
				{
					return 'unapprove';
				}
				break;

			case 'user_id':
				$oldUser = \XF::em()->find('XF:User', $oldValue);
				$from = $oldUser ? $oldUser->username : '';
				return ['reassign', ['from' => $from]];
		}

		return false;
	}
	
	/**
	 * @param ModeratorLog $log
	 * @param Entity $content
	 */
	protected function setupLogEntityContent(ModeratorLog $log, Entity $content)
	{
		/** @var \DBTech\eCommerce\Entity\License $content */
		$log->content_user_id = $content->user_id;
		$log->content_username = $content->User->username;
		$log->content_title = $content->title;
		$log->content_url = \XF::app()->router('public')->buildLink('nopath:dbtech-ecommerce/account/license', $content);
		$log->discussion_content_type = 'dbtech_ecommerce_license';
		$log->discussion_content_id = $content->license_id;
	}
	
	/**
	 * @param ModeratorLog $log
	 *
	 * @return array
	 */
	protected function getActionPhraseParams(ModeratorLog $log): array
	{
		if ($log->action == 'edit')
		{
			return ['elements' => implode(', ', array_keys($log->action_params))];
		}
		
		return parent::getActionPhraseParams($log);
	}
}