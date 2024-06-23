<?php

namespace DBTech\eCommerce\ModeratorLog;

use XF\Entity\ModeratorLog;
use XF\ModeratorLog\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\ModeratorLog
 */
class Download extends AbstractHandler
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
			case 'edit':
				if ($actor->user_id == $content->Product->user_id)
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
			case 'title':
			case 'message':
				return 'edit';

			case 'download_state':
				if ($newValue == 'visible' && $oldValue == 'moderated')
				{
					return 'approve';
				}
				
				if ($newValue == 'visible' && $oldValue == 'deleted')
				{
					return 'undelete';
				}
				
				if ($newValue == 'deleted')
				{
					$reason = $content->DeletionLog ? $content->DeletionLog->delete_reason : '';
					return ['delete_soft', ['reason' => $reason]];
				}
				
				if ($newValue == 'moderated')
				{
					return 'unapprove';
				}
				break;
		}

		return false;
	}
	
	/**
	 * @param ModeratorLog $log
	 * @param Entity $content
	 */
	protected function setupLogEntityContent(ModeratorLog $log, Entity $content)
	{
		/** @var \DBTech\eCommerce\Entity\Download $content */
		$product = $content->Product;

		$log->content_user_id = $product->user_id;
		$log->content_username = $product->User->username;
		$log->content_title = $product->title;
		$log->content_url = \XF::app()->router('public')->buildLink('nopath:dbtech-ecommerce/update', $content);
		$log->discussion_content_type = 'dbtech_ecommerce_download';
		$log->discussion_content_id = $content->download_id;
	}
	
	/**
	 * @param ModeratorLog $log
	 *
	 * @return null|string|string[]|\XF\Phrase
	 */
	public function getContentTitle(ModeratorLog $log)
	{
		return \XF::phrase('dbtech_ecommerce_product_update_in_x', [
			'title' => \XF::app()->stringFormatter()->censorText($log->content_title_)
		]);
	}
}