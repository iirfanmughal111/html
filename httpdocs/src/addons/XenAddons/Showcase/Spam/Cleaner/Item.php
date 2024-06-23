<?php

namespace XenAddons\Showcase\Spam\Cleaner;

use XF\Spam\Cleaner\AbstractHandler;

class Item extends AbstractHandler
{
	public function canCleanUp(array $options = [])
	{
		return !empty($options['action_threads']);
	}

	public function cleanUp(array &$log, &$error = null)
	{
		$app = \XF::app();

		$itemsFinder = $app->finder('XenAddons\Showcase:Item');
		$items = $itemsFinder
			->where('user_id', $this->user->user_id)
			->fetch();

		if ($items->count())
		{
			$itemIds = $items->pluckNamed('item_id');
			$submitter = $app->container('spam.contentSubmitter');
			$submitter->submitSpam('sc_item', $itemIds);

			$deleteType = $app->options()->spamMessageAction == 'delete' ? 'hard' : 'soft';

			$log['sc_item'] = [
				'deleteType' => $deleteType,
				'itemIds' => []
			];

			foreach ($items AS $itemId => $item)
			{
				$log['sc_item']['itemIds'][] = $itemId;

				/** @var \XenAddons\Showcase\Entity\Item $item */
				$item->setOption('log_moderator', false);
				if ($deleteType == 'soft')
				{
					$item->item_state = 'deleted';
					$item->save();
				}
				else
				{
					$item->delete();
				}
			}
		}

		return true;
	}

	public function restore(array $log, &$error = null)
	{
		$itemsFinder = \XF::app()->finder('XenAddons\Showcase:Item');

		if ($log['deleteType'] == 'soft')
		{
			$items = $itemsFinder->where('item_id', $log['itemIds'])->fetch();
			foreach ($items AS $item)
			{
				/** @var \XenAddons\Showcase\Entity\Item $item */
				$item->setOption('log_moderator', false);
				$item->item_state = 'visible';
				$item->save();
			}
		}

		return true;
	}
}