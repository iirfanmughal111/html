<?php

namespace FS\AuctionPlugin\Entity;

use XF\Draft;
use XF\Entity\AbstractCategoryTree;
use XF\Entity\Forum;
use XF\Entity\Phrase;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Structure;

class Category extends AbstractCategoryTree
{
    protected $_viewableDescendants = [];

    public function getCategoryListExtras()
    {
        return [
            'listing_count' => $this->listing_count,
            'last_listing_date' => $this->last_listing_date,
            'last_listing_title' => $this->last_listing_title,
            'last_listing_id' => $this->last_listing_id
        ];
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_auction_category';
        $structure->shortName = 'FS\AuctionPlugin:Category';
        $structure->primaryKey = 'category_id';
        $structure->contentType = 'fs_auction_category';
        $structure->columns = [
            'category_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'title' => [
                'type' => self::STR, 'maxLength' => 100,
                'required' => 'please_enter_valid_title'
            ],
            'description' => ['type' => self::STR, 'default' => ''],
            'auctions_count' => ['type' => self::UINT, 'forced' => true, 'default' => 0],
        ];
        $structure->relations = [];
        $structure->getters = [];
        $structure->options = [];

        static::addCategoryTreeStructureElements($structure, [
            'breadcrumb_json' => true
        ]);

        return $structure;
    }
}
