<?php

namespace Z61\Classifieds\Cron;

class Views
{
    public static function runViewUpdate()
    {
        $app = \XF::app();

        /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
        $listingRepo = $app->repository('Z61\Classifieds:Listing');
        $listingRepo->batchUpdateListingViews();

    }
}