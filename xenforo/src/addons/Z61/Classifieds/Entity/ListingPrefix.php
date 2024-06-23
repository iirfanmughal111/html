<?php

namespace Z61\Classifieds\Entity;

use XF\Entity\AbstractPrefix;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null prefix_id
 * @property int prefix_group_id
 * @property int display_order
 * @property int materialized_order
 * @property string css_class
 * @property array allowed_user_group_ids
 *
 * GETTERS
 * @property \XF\Phrase|string title
 * @property array category_ids
 *
 * RELATIONS
 * @property \XF\Entity\Phrase MasterTitle
 * @property \Z61\Classifieds\Entity\ListingPrefixGroup PrefixGroup
 * @property \Z61\Classifieds\Entity\CategoryPrefix[] CategoryPrefixes
 */
class ListingPrefix extends AbstractPrefix
{
    protected function getClassIdentifier()
    {
        return 'Z61\Classifieds:ListingPrefix';
    }

    protected static function getContentType()
    {
        return 'classifieds_listing';
    }

    /**
     * @return array
     */
    public function getCategoryIds()
    {
        if (!$this->prefix_id)
        {
            return [];
        }

        return $this->db()->fetchAllColumn("
			SELECT category_id
			FROM xf_z61_classifieds_category_prefix
			WHERE prefix_id = ?
		", $this->prefix_id);
    }

    protected function _postDelete()
    {
        parent::_postDelete();

        $this->repository('Z61\Classifieds:CategoryPrefix')->removePrefixAssociations($this);
    }

    public static function getStructure(Structure $structure)
    {
        self::setupDefaultStructure($structure, 'xf_z61_classifieds_listing_prefix', 'Z61\Classifieds:ListingPrefix');

        $structure->getters['category_ids'] = true;

        $structure->relations['CategoryPrefixes'] = [
            'entity' => 'Z61\Classifieds:CategoryPrefix',
            'type' => self::TO_MANY,
            'conditions' => 'prefix_id'
        ];

        return $structure;
    }
}