<?php

namespace Z61\Classifieds\Job;

use XF\Job\AbstractRebuildJob;
use Z61\Classifieds\Entity\Listing;

class ListingTypeRebuild extends AbstractRebuildJob
{
    protected $defaultData = [
        'listing_type_id' => 0,
        'new_listing_type_id' => 0
    ];

    protected function getNextIds($start, $batch)
    {
        $db = $this->app->db();

        return $db->fetchAllColumn($db->limit(
            "
				SELECT listing_id
				FROM xf_z61_classifieds_listing
				WHERE listing_type_id = ? AND listing_id > ?
				ORDER BY listing_id
			", $batch
        ), [
            $this->data['listing_type_id'],
            $start
        ]);
    }

    protected function rebuildById($id)
    {
        /** @var Listing $listing */
        $listing = $this->app->em()->find('Z61\Classifieds:Listing', $id);
        if (!$listing)
        {
            return;
        }

        $listing->listing_type_id = $this->data['new_listing_type_id'];
        $listing->save();
    }

    protected function getStatusType()
    {
        return \XF::phrase('z61_classifieds_listings');
    }


}