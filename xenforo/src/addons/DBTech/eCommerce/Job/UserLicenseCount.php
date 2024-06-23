<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class UserLicenseCount
 *
 * @package DBTech\eCommerce\Job
 */
class UserLicenseCount extends AbstractRebuildJob
{
	/**
	 * @param $start
	 * @param $batch
	 *
	 * @return array
	 */
	protected function getNextIds($start, $batch): array
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			'
				SELECT user_id
				FROM xf_user
				WHERE user_id > ?
				ORDER BY user_id
			',
			$batch
		), $start);
	}

	/**
	 * @param $id
	 */
	protected function rebuildById($id)
	{
		/** @var \DBTech\eCommerce\Repository\License $repo */
		$repo = $this->app->repository('DBTech\eCommerce:License');
		$count = $repo->getUserLicenseCount($id);

		$this->app->db()->update('xf_user', ['dbtech_ecommerce_license_count' => $count], 'user_id = ?', $id);
	}

	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_license_counts');
	}
}