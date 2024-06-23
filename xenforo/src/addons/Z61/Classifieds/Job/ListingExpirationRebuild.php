<?php

namespace Z61\Classifieds\Job;

use XF\Job\AbstractJob;
use Z61\Classifieds\Entity\Category;
use Z61\Classifieds\Entity\Listing;

class ListingExpirationRebuild extends AbstractJob
{
    const HOUR = 86400;
    protected $defaultData = [
        'category_id' => [],
        'batch' => 50
    ];


    public function run($maxRunTime)
    {
        /** @var Category $category */
        $category = $this->app->find('Z61\Classifieds:Category', $this->data['category_id']);

        $db = $this->app->db();

        $timeToAdd = $category->expiration_days * self::HOUR;

        $listingIds = $db->fetchAllColumn('
			SELECT listing_id
			FROM xf_z61_classifieds_listing
			WHERE category_id = ? and listing_state = ? and TIMESTAMPADD(DAY, ?, FROM_UNIXTIME(listing_date))  > expiration_date
			ORDER BY listing_date
		', [
		    $category->category_id,
            'visible',
            $timeToAdd,
        ]);

        if (!$category->listing_count)
        {
            return $this->complete();
        }

        if (!is_array($listingIds))
        {
            $listingIds = [$listingIds];
        }


        $batchSize = max(1, intval($this->data['batch']));
        $s = microtime(true);

        do
        {
            $batch = array_slice($listingIds, 0, $batchSize);
            $listingIds = array_slice($listingIds, count($batch));

            $db->beginTransaction();

            foreach($batch as $listingId)
            {
                /** @var Listing $listing */
                $listing = $this->app->find('Z61\Classifieds:Listing', $listingId, 'Category');

                $newExpiration = new \DateTime($listing->listing_date);
                $days = $category->expiration_days;
                if (empty($days))
                {
                    $days = 30;
                }

                $dateInterval = new \DateInterval('P' . $days . 'D');
                $newExpiration->add($dateInterval);

                $listing->fastUpdate('expiration_date', $newExpiration->getTimestamp());
            }

            if (microtime(true) - $s >= $maxRunTime)
            {
                break;
            }

            $db->commit();
        }
        while ($listingIds);

        if (!$listingIds)
        {
            return $this->complete();
        }

        $this->data['content_ids'] = $listingIds;

        return $this->resume();
    }

    public function getStatusMessage()
    {
        $actionPhrase = \XF::phrase('rebuilding');
        $typePhrase = \XF::phrase('z61_classifieds_listings');
        return sprintf('%s... %s', $actionPhrase, $typePhrase);
    }

    public function canCancel()
    {
        return false;
    }

    public function canTriggerByChoice()
    {
        return false;
    }
}