<?php

namespace XenAddons\Showcase\Spam\Cleaner;

use XF\Spam\Cleaner\AbstractHandler;

class ItemUpdate extends AbstractHandler
{
	public function canCleanUp(array $options = [])
	{
		return !empty($options['delete_messages']);
	}

	public function cleanUp(array &$log, &$error = null)
	{
		$app = \XF::app();

		$updateFinder = $app->finder('XenAddons\Showcase:ItemUpdate');
		$updates = $updateFinder
			->where('user_id', $this->user->user_id)
			->fetch();

		if ($updates->count())
		{
			$updateIds = $updates->pluckNamed('item_update_id');
			$submitter = $app->container('spam.contentSubmitter');
			$submitter->submitSpam('sc_update', $updateIds);

			$deleteType = $app->options()->spamMessageAction == 'delete' ? 'hard' : 'soft';

			$log['sc_update'] = [
				'deleteType' => $deleteType,
				'updateIds' => []
			];

			foreach ($updates AS $updateId => $update)
			{
				$log['sc_update']['updateIds'][] = $updateId;

				/** @var \XenAddons\Showcase\Entity\ItemUpdate $update */
				$update->setOption('log_moderator', false);
				if ($deleteType == 'soft')
				{
					$update->update_state = 'deleted';
					$update->save();
				}
				else
				{
					$update->delete();
				}
			}
		}

		return true;
	}

	public function restore(array $log, &$error = null)
	{
		$updatesFinder = \XF::app()->finder('XenAddons\Showcase:ItemUpdate');

		if ($log['deleteType'] == 'soft')
		{
			$updates = $updatesFinder->where('item_update_id', $log['updateIds'])->fetch();
			foreach ($updates AS $update)
			{
				/** @var \XenAddons\Showcase\Entity\ItemUpdate $update */
				$update->setOption('log_moderator', false);
				$update->update_state = 'visible';
				$update->save();
			}
		}

		return true;
	}
}