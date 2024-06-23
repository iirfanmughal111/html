<?php


namespace Z61\Classifieds\Entity;


use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int listing_feature_id
 * @property int listing_id
 * @property int user_id
 * @property int date
 * @property int expiration_date
 * @property string|null purchase_request_key
 *
 * RELATIONS
 * @property \Z61\Classifieds\Entity\Listing Listing
 * @property \XF\Entity\User User
 * @property \XF\Entity\PurchaseRequest PurchaseRequest
 */
class ListingFeature extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_listing_feature';
        $structure->shortName = 'Z61\Classifieds:ListingFeature';
        $structure->primaryKey = 'listing_id';
        $structure->columns = [
            'listing_feature_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'listing_id' => ['type' => self::UINT, 'required' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'date' => ['type' => self::UINT, 'default' => \XF::$time],
            'expiration_date' => ['type' => self::UINT, 'default' => null],
            'purchase_request_key' => ['type' => self::STR, 'maxLength' => 32, 'nullable' => true],
        ];
        $structure->getters = [];
        $structure->relations = [
            'Listing' => [
                'entity' => 'Z61\Classifieds:Listing',
                'type' => self::TO_ONE,
                'conditions' => 'listing_id',
                'primary' => true
            ],
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'PurchaseRequest' => [
                'entity' => 'XF:PurchaseRequest',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['request_key', '=', '$purchase_request_key']
                ]
            ]
        ];

        return $structure;
    }
}