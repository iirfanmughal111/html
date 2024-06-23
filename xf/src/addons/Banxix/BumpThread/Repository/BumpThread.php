<?php

namespace Banxix\BumpThread\Repository;

use XF\Mvc\Entity\Repository;

class BumpThread extends Repository
{
	public function bump(\XF\Entity\Thread $thread)
	{
		$db = $this->db();

		$db->query("
			UPDATE xf_thread
			SET last_post_date = ?
			WHERE thread_id = ?
		", [\XF::$time, $thread->thread_id]);

		$db->query("
			UPDATE xf_post
			SET post_date = ?
			WHERE post_id = ?
		", [\XF::$time, $thread->last_post_id]);
	}

	public function log($threadId, $userId)
	{
		$this->db()->query("
			INSERT INTO xf_bump_thread_log
				(thread_id, user_id, bump_date)
			VALUES
				(?, ?, ?)
			",
			[$threadId, $userId, \XF::$time]
		);
	}

	public function userLastBump($threadId, $userId)
	{
		return $this->db()->fetchOne("
			SELECT bump_date FROM xf_bump_thread_log
			WHERE thread_id = ? AND user_id = ?
			ORDER BY id DESC LIMIT 1
		", [$threadId, $userId]);
	}

	public function userTodayBumpCount($userId)
	{
		return $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_bump_thread_log
			WHERE user_id = ?
			AND FROM_UNIXTIME(bump_date, '%Y-%m-%d') = ?
		", [$userId, date('Y-m-d', \XF::$time)]);
	}

	/**
	 * @param \XF\Finder\Thread|\Banxix\BumpThread\XF\Finder\Thread $finder
	 */
	public function bumpedThreadsInForum(\XF\Finder\Thread $finder)
	{
		$finder->sqlJoin("(
				SELECT thread_id, max(bump_date) as bump_date 
				FROM xf_bump_thread_log AS bump_log
				GROUP BY thread_id 
			)", 'bump_log', ['thread_id', 'bump_date'], false, true);

		$finder->sqlJoinConditions('bump_log', ['thread_id']);
	}

	public function hasNodePermission($user, $nodeId)
	{
		if (\XF::options()->bump_thread_reverse_time_limit)
		{
			$permissions = $this->fetchFloodRatePermissions(
				'XF:PermissionEntryContent', $user, 'node', $nodeId
			);

			if (empty($permissions))
			{
				$permissions = $this->fetchFloodRatePermissions('XF::PermissionEntry', $user);
			}

			return empty($permissions) ? 0 : max(min($permissions), 0);
		}
		else
		{
			return max($user->hasNodePermission($nodeId, 'bumpFloodRate'), 0);
		}
	}

	private function fetchFloodRatePermissions($name, $user, $contentType = null, $contentId = null)
	{
		$userGroups = array_merge($user->secondary_group_ids, [$user->user_group_id]);

		$finder = \XF::finder($name)
			->where('permission_group_id', 'forum')
			->where('permission_id', 'bumpFloodRate')
			->whereOr([
				['user_group_id', $userGroups],
				['user_id', $user->user_id]
			]);

		if ($contentType)
		{
			$finder->where('content_type', 'node');

			if ($contentId)
			{
				$finder->where('content_id', $contentId);
			}
		}

		$results = [];
		$permissions = $finder->fetchColumns('permission_value_int');

		foreach ($permissions as $permission)
		{
			$results[] = $permission['permission_value_int'];
		}

		return $results;
	}
}