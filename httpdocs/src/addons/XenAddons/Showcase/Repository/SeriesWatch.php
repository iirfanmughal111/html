<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Repository;

class SeriesWatch extends Repository
{
	public function setWatchState(\XenAddons\Showcase\Entity\SeriesItem $series, \XF\Entity\User $user, $action, array $config = [])
	{
		if (!$series->series_id || !$user->user_id)
		{
			throw new \InvalidArgumentException("Invalid series or user");
		}

		$watch = $this->em->find('XenAddons\Showcase:SeriesWatch', [
			'series_id' => $series->series_id,
			'user_id' => $user->user_id
		]);

		switch ($action)
		{
			case 'watch':
				if (!$watch)
				{
					$watch = $this->em->create('XenAddons\Showcase:SeriesWatch');
					$watch->series_id = $series->series_id;
					$watch->user_id = $user->user_id;
				}
				unset($config['series_id'], $config['user_id']);

				$watch->bulkSet($config);
				$watch->save();
				
				$this->updateSeriesRecordWatchCount($series);
				
				break;

			case 'update':
				if ($watch)
				{
					unset($config['series_id'], $config['user_id']);

					$watch->bulkSet($config);
					$watch->save();
				}
				break;

			case 'delete':
				if ($watch)
				{
					$watch->delete();
					
					$this->updateSeriesRecordWatchCount($series);
				}
				break;
					
			default:
				throw new \InvalidArgumentException("Unknown action '$action' (expected: delete/watch/update)");
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
				unset($updates['series_id'], $updates['user_id']);
				return $db->update('xf_xa_sc_series_watch', $updates, 'user_id = ?', $user->user_id);

			case 'delete':
				return $db->delete('xf_xa_sc_series_watch', 'user_id = ?', $user->user_id);

			default:
				throw new \InvalidArgumentException("Unknown action '$action'");
		}
	}
	
	public function updateSeriesRecordWatchCount(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		// we want the total amonut of watchers that does not include the Series Owner (as series owners can watch their own series)
		$watchCount = $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_xa_sc_series_watch
			WHERE series_id = ?
				AND user_id != ?
		", [$series->series_id, $series->user_id]);
	
		// Update the watch_count cache field in the series table
		$this->db()->update('xf_xa_sc_series',	[
			'watch_count' => $watchCount,
		], 'series_id = ?', $series->series_id);
	}
}