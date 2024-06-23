<?php

namespace Z61\Classifieds\Entity;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int listing_type_id
 * @property string class
 * @property int display_order
 *
 * GETTERS
 * @property \XF\Phrase title
 * @property mixed listing_count
 *
 * RELATIONS
 * @property \Z61\Classifieds\Entity\Listing[] Listings
 * @property \XF\Entity\Phrase MasterTitle
 */
class ListingType extends Entity
{
    /**
     * @return \XF\Phrase
     */
    public function getTitle()
    {
        return \XF::phrase($this->getPhraseName());
    }

    public function getPhraseName()
    {
        return 'z61_listing_type_title.' . $this->listing_type_id;
    }

    public function getMasterPhrase()
    {
        $phrase = $this->MasterTitle;

        if (!$phrase)
        {
            $phrase = $this->_em->create('XF:Phrase');
            $phrase->title = $this->_getDeferredValue(function ()
            {
                return $this->getPhraseName();
            }, 'save');
            $phrase->language_id = 0;
            $phrase->addon_id = '';
        }

        return $phrase;
    }

    protected function _postDelete()
    {
        if ($this->MasterTitle)
        {
            $this->MasterTitle->delete();
        }

        // TODO: delete listings with this listing type?
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_listing_type';
        $structure->shortName = 'Z61\Classifieds:ListingType';
        $structure->primaryKey = 'listing_type_id';
        $structure->contentType = 'classifieds_listing_type';
        $structure->columns = [
            'listing_type_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'css_class' => ['type' => self::STR, 'default' => 'label--green'],
            'display_order' => ['type' => self::UINT, 'default' => 10]
        ];
        $structure->relations = [
            'Listings' => [
                'entity' => 'Z61\Classifieds:Listing',
                'type' => self::TO_MANY,
                'conditions' => 'listing_type_id',
                'order' => 'listing_date',
            ],
            'MasterTitle' => [
                'entity' => 'XF:Phrase',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['language_id', '=', 0],
                    ['title', '=', 'z61_listing_type_title.', '$listing_type_id']
                ]
            ]
        ];
        $structure->getters = [
            'title' => true,
            'description' => true,
            'cost_phrase' => true,
            'purchasable_type_id' => true,
            'listing_count' => true
        ];
        return $structure;
    }

    public function getListingCount()
    {
        return $this->Listings->count();
    }
}