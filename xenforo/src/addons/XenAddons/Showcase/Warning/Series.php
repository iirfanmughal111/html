<?php

namespace XenAddons\Showcase\Warning;

use XF\Entity\Warning;
use XF\Mvc\Entity\Entity;
use XF\Warning\AbstractHandler;

class Series extends AbstractHandler
{
	public function getStoredTitle(Entity $entity)
	{
		return $entity->title;
	}
	
	public function getDisplayTitle($title)
	{
		return \XF::phrase('xa_sc_series_x', ['title' => $title]);
	}
	
	public function getContentForConversation(Entity $entity)
	{
		return $entity->message;
	}
	
	public function getContentUrl(Entity $entity, $canonical = false)
	{
		return \XF::app()->router('public')->buildLink(($canonical ? 'canonical:' : '') . 'showcase/series', $entity);
	}
	
	public function getContentUser(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\SeriesItem $entity */
		return $entity->User;
	}
	
	public function canViewContent(Entity $entity, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\SeriesItem $entity */
		return $entity->canView();
	}
	
	public function onWarning(Entity $entity, Warning $warning)
	{
		$entity->warning_id = $warning->warning_id;
		$entity->save();
	}
	
	public function onWarningRemoval(Entity $entity, Warning $warning)
	{
		$entity->warning_id = 0;
		$entity->warning_message = '';
		$entity->save();
	}
	
	public function takeContentAction(Entity $entity, $action, array $options)
	{
		if ($action == 'public')
		{
			$message = isset($options['message']) ? $options['message'] : '';
			if (is_string($message) && strlen($message))
			{
				$entity->warning_message = $message;
				$entity->save();
			}
		}
		else if ($action == 'delete')
		{
			$reason = isset($options['reason']) ? $options['reason'] : '';
			if (!is_string($reason))
			{
				$reason = '';
			}
	
			/** @var \XenAddons\Showcase\Service\SeriesItem\Deleter $deleter */
			$deleter = \XF::app()->service('XenAddons\Showcase:SeriesItem\Deleter', $entity);
			$deleter->delete('soft', $reason);
		}
	}
	
	protected function canWarnPublicly(Entity $entity)
	{
		return true;
	}
	
	protected function canDeleteContent(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\SeriesItem $entity */
		return $entity->canDelete('soft');
	}
}