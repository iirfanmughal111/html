<?php

namespace XenAddons\Showcase\ModeratorLog;

use XF\Entity\ModeratorLog;
use XF\ModeratorLog\AbstractHandler;
use XF\Mvc\Entity\Entity;

class ItemUpdateReply extends AbstractHandler
{
	public function isLoggable(Entity $content, $action, \XF\Entity\User $actor)
	{
		switch ($action)
		{
			case 'edit':
				if ($actor->user_id == $content->user_id)
				{
					return false;
				}
		}

		return parent::isLoggable($content, $action, $actor);
	}

	protected function getLogActionForChange(Entity $content, $field, $newValue, $oldValue)
	{
		switch ($field)
		{
			case 'message':
				return 'edit';
				
			case 'reply_state':
				if ($newValue == 'visible' && $oldValue == 'moderated')
				{
					return 'approve';
				}
				else if ($newValue == 'visible' && $oldValue == 'deleted')
				{
					return 'undelete';
				}
				else if ($newValue == 'deleted')
				{
					$reason = $content->DeletionLog ? $content->DeletionLog->delete_reason : '';
					return ['delete_soft', ['reason' => $reason]];
				}
				else if ($newValue == 'moderated')
				{
					return 'unapprove';
				}
				break;
		}

		return false;
	}

	protected function setupLogEntityContent(ModeratorLog $log, Entity $content)
	{
		/** @var \XenAddons\Showcase\Entity\ItemUpdateReply $content */
		
		$update = $content->ItemUpdate;
		$item = $update->Item;

		$log->content_user_id = $content->user_id;
		$log->content_username = $content->User ? $content->User->username : '';
		$log->content_title = $item->title . ': ' . $update->title;
		$log->content_url = \XF::app()->router('public')->buildLink('nopath:showcase/update-reply', $content);
		$log->discussion_content_type = 'sc_item';
		$log->discussion_content_id = $item->item_id;
	}

	public function getContentTitle(ModeratorLog $log)
	{
		return \XF::phrase('xa_sc_item_update_reply_in_x', [
			'title' => \XF::app()->stringFormatter()->censorText($log->content_title_)
		]);
	}
}