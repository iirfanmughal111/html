<?php

namespace Z61\Classifieds\Repository;

use XF\Repository\AbstractField;

class ListingField extends AbstractField
{
    protected function getRegistryKey()
    {
        return 'classifiedsListingFields';
    }

    protected function getClassIdentifier()
    {
        return 'Z61\Classifieds:ListingField';
    }

    public function getDisplayGroups()
    {
        return [
            'above' => \XF::phrase('z61_classifieds_above_listing_content'),
            'below' => \XF::phrase('z61_classifieds_below_listing_content'),
            'extra' => \XF::phrase('z61_classifieds_extra_information_tab'),
        ];
    }
}