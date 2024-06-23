<?php

namespace Z61\Classifieds\Entity;

use XF\Entity\AbstractPrefixGroup;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null prefix_group_id
 * @property int display_order
 *
 * GETTERS
 * @property \XF\Phrase|string title
 *
 * RELATIONS
 * @property \XF\Entity\Phrase MasterTitle
 * @property \Z61\Classifieds\Entity\ListingPrefix[] Prefixes
 */
class ListingPrefixGroup extends AbstractPrefixGroup
{
    protected function getClassIdentifier()
    {
        return 'Z61\Classifieds:ListingPrefix';
    }

    protected static function getContentType()
    {
        return 'classifieds_listing';
    }

    public static function getStructure(Structure $structure)
    {
        self::setupDefaultStructure(
            $structure,
            'xf_z61_classifieds_listing_prefix_group',
            'Z61\Classifieds:ListingPrefixGroup',
            'Z61\Classifieds:ListingPrefix'
        );

        return $structure;
    }
}