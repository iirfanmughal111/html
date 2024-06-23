<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Repository;

class ItemWatch extends Repository
{
	public function autoWatchScItem(\XenAddons\Showcase\Entity\Item $item, \XF\Entity\User $user, $onCreation = false)
	{
		$userField = $onCreation ? 'creation_watch_state' : 'interaction_watch_state';

		if (!$item->item_id || !$user->user_id || !$user->Option->getValue($userField))
		{
			return null;
		}

		$watch = $this->em->find('XenAddons\Showcase:ItemWatch', [
			'item_id' => $item->item_id,
			'user_id' => $user->user_id
		]);
		if ($watch)
		{
			return null;
		}
		
		/** @var \XenAddons\Showcase\Entity\ItemWatch $watch */
		$watch = $this->em->create('XenAddons\Showcase:ItemWatch');
		$watch->item_id = $item->item_id;
		$watch->user_id = $user->user_id;
		$watch->email_subscribe = ($user->Option->getValue($userField) == 'watch_email');

		try
		{
			$watch->save();
		}
		catch (\XF\Db\DuplicateKeyException $e)
		{
			return null;
		}

		return $watch;
	}
	
	public function isValidWatchState($state)
	{
		switch ($state)
		{
			case 'watch':
			case 'update':
			case 'delete':
	
			default:
				return false;
		}
	}

	public function setWatchState(\XenAddons\Showcase\Entity\Item $item, \XF\Entity\User $user, $action, array $config = [])
	{
		if (!$item->item_id || !$user->user_id)
		{
			throw new \InvalidArgumentException("Invalid item or user");
		}

		$watch = $this->em->find('XenAddons\Showcase:ItemWatch', [
			'item_id' => $item->item_id,
			'user_id' => $user->user_id
		]);

		switch ($action)
		{
			case 'watch':
				if (!$watch)
				{
					$watch = $this->em->create('XenAddons\Showcase:ItemWatch');
					$watch->item_id = $item->item_id;
					$watch->user_id = $user->user_id;
				}
				unset($config['item_id'], $config['user_id']);

				$watch->bulkSet($config);
				$watch->save();
				
				$this->updateItemRecordWatchCount($item);
				
				break;

			case 'update':
				if ($watch)
				{
					unset($config['item_id'], $config['user_id']);

					$watch->bulkSet($config);
					$watch->save();
				}
				break;

			case 'delete':
				if ($watch)
				{
					$watch->delete();
					
					$this->updateItemRecordWatchCount($item);
				}
				break;

			default:
				throw new \InvalidArgumentException("Unknown action '$action' (expected: delete/watch)");
		}
	}

	public function setWatchStateForAll(\XF\Entity\User $user, $action, array $updates = [])
	{
		if (!$user->user_id)
		{
			throw new \InvalidArgumentException("Invalid user");
		}

		$db = $this->db();

		switch ($action)
		{
			case 'update':
				unset($updates['item_id'], $updates['user_id']);
				return $db->update('xf_xa_sc_item_watch', $updates, 'user_id = ?', $user->user_id);

			case 'delete':
				return $db->delete('xf_xa_sc_item_watch', 'user_id = ?', $user->user_id);

			default:
				throw new \InvalidArgumentException("Unknown action '$action'");
		}
	}
	
	public function updateItemRecordWatchCount(\XenAddons\Showcase\Entity\Item $item)
	{
		// we want the total amonut of watchers that does not include the Item Owner (as item owners can watch their own items)
		$watchCount = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_xa_sc_item_watch
			WHERE item_id = ?
				AND user_id != ?
		", [$item->item_id,$item->user_id]);
	
		// Update the watch_count cache field in the item table
		$this->db()->update('xf_xa_sc_item',	[
			'watch_count' => $watchCount,
		], 'item_id = ?', $item->item_id);
	}
}