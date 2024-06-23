<?php

namespace FS\AuctionPlugin\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Util\Arr;
use XF\Entity\BookmarkTrait;
use XF\Entity\LinkableInterface;


class AuctionListing extends Entity implements LinkableInterface
{

    use  BookmarkTrait;

    protected function canBookmarkContent(&$error = null)
    {

        return true;
    }

    public function getContentPublicRoute()
    {
        return 'auction/view-auction';
    }

    public function getContentTitle(string $context = '')
    {
        if ($this->title) {

            return $this->title;
        }
    }
    public function getContentUrl(bool $canonical = false, array $extraParams = [], $hash = null)
    {

        $route = $canonical ? 'canonical:auction/view-auction' : 'auction/view-auction';

        return $this->app()->router('public')->buildLink($route, $this, $extraParams, $hash);
    }
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_auction_listing';
        $structure->shortName = 'FS\AuctionPlugin:AuctionListing';
        $structure->contentType = 'fs_auction';
        $structure->primaryKey = 'auction_id';
        $structure->columns = [
            'auction_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'category_id' => [
                'type' => self::UINT,
                'required' => 'fs_auctions_please_enter_valid_category'
            ],
            'thread_id' => [
                'type' => self::UINT,
                'required' => true,
                'default' => 0
            ],
            'last_bumping' => ['type' => self::UINT, 'default' => \XF::$time],
            'bumping_counts' => ['type' => self::UINT, 'default' => 0],
        ];

        $structure->relations = [
            'Category' => [
                'entity' => 'FS\AuctionPlugin:Category',
                'type' => self::TO_ONE,
                'conditions' => 'category_id',
            ],
            'ShipVia' => [
                'entity' => 'FS\AuctionPlugin:ShipsVia',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['via_id', '=', '$ships_via'],
                ]
            ],

            'ShipTerm' => [
                'entity' => 'FS\AuctionPlugin:ShipTerms',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['term_id', '=', '$shipping_term'],
                ]
            ],

            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],
            'Prefix' => [
                'entity' => 'XF:ThreadPrefix',
                'type' => self::TO_ONE,
                'conditions' => 'prefix_id',
                'primary' => true
            ],
            'Thread' => [
                'entity' => 'XF:Thread',
                'type' => self::TO_ONE,
                'conditions' => 'thread_id',
                'primary' => true
            ],

            'Attachment' => [
                'entity' => 'XF:Attachment',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'fs_auction'],
                    ['content_id', '=', '$auction_id']
                ],
                'with' => 'Data',
            ]
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];
        static::addBookmarkableStructureElements($structure);

        return $structure;
    }
}
