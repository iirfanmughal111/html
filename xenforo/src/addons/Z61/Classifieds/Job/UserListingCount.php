<?php

namespace Z61\Classifieds\Job;

use XF\Job\AbstractRebuildJob;

class UserListingCount extends AbstractRebuildJob
{
    protected function getNextIds($start, $batch)
    {
        $db = $this->app->db();

        return $db->fetchAllColumn($db->limit(
            "
				SELECT user_id
				FROM xf_user
				WHERE user_id > ?
				ORDER BY user_id
			", $batch
        ), $start);
    }

    protected function rebuildById($id)
    {
        /** @var \Z61\Classifieds\Repository\Listing $repo */
        $repo = $this->app->repository('Z61\Classifieds:Listing');
        $count = $repo->getUserListingCount($id);

        $this->app->db()->update('xf_user', ['z61_classifieds_listing_count' => $count], 'user_id = ?', $id);
    }

    protected function getStatusType()
    {
        return \XF::phrase('z61_classifieds_listing_counts');
    }
}