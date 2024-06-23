<?php

namespace Z61\Classifieds\Repository;

use XF\Repository\AbstractPrefix;

class ListingPrefix extends AbstractPrefix
{
    protected function getRegistryKey()
    {
        return 'classifiedsListings';
    }

    protected function getClassIdentifier()
    {
        return 'Z61\Classifieds:ListingPrefix';
    }
}