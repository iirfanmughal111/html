<?php

namespace Z61\Classifieds\Cron;

class ExpireListings
{
    public static function runCleanUp()
    {
        $app = \XF::app();

        $closeListings = \XF::options()->z61ClassifiedsCloseExpiredListings;

        if (!$closeListings)
        {
            return;
        }
        /** @var \Z61\Classifieds\Repository\Listing $listing */
        $listing = $app->repository('Z61\Classifieds:Listing');
        $listing->expireListingsPastExpiration();
    }
}