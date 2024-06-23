<?php

namespace XenAddons\Showcase\Warning;

use XF\Entity\Warning;
use XF\Mvc\Entity\Entity;
use XF\Warning\AbstractHandler;

class ItemUpdateReply extends AbstractHandler
{
	public function getStoredTitle(Entity $entity)
	{
		// TODO an update has a TITLE, so combine that with the Item Title 
		
		return $entity->ItemUpdate->Item ? $entity->ItemUpdate->Item->title : '';
	}

	public function getDisplayTitle($title)
	{
		return \XF::phrase('xa_sc_item_update_reply_in_x', ['title' => $title]);
	}

	public function getContentForConversation(Entity $entity)
	{
		return $entity->message;
	}

	public function getContentUser(Entity $entity)
	{
		return $entity->User;
	}

	public function canViewContent(Entity $entity, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\Item $entity */
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
			$message = $options['message'] ?? '';
			if (is_string($message) && strlen($message))
			{
				$entity->warning_message = $message;
				$entity->save();
			}
		}
		else if ($action == 'delete')
		{
			$reason = $options['reason'] ?? '';
			if (!is_string($reason))
			{
				$reason = '';
			}

			/** @var \XenAddons\Showcase\Service\ItemUpdateReply\Deleter $deleter */
			$deleter = \XF::app()->service('XenAddons\Showcase:ItemUpdateReply\Deleter', $entity);
			$deleter->delete('soft', $reason);
		}
	}

	protected function canWarnPublicly(Entity $entity)
	{
		return true;
	}

	protected function canDeleteContent(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\Item $entity */
		return $entity->canDelete('soft');
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();
		return ['User', 'ItemUpdate', 'ItemUpdate.Item', 'ItemUpdate.Item.Category', 'ItemUpdate.Item.Category.Permissions|' . $visitor->permission_combination_id];
	}
}