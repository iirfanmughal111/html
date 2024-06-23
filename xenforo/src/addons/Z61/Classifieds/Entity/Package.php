<?php

namespace Z61\Classifieds\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null package_id
 * @property int display_order
 * @property bool active
 * @property float cost_amount
 * @property string cost_currency
 * @property int length_amount
 * @property string length_unit
 * @property array payment_profile_ids
 *
 * GETTERS
 * @property \XF\Phrase title
 * @property \XF\Phrase|string cost_phrase
 * @property string purchasable_type_id
 *
 * RELATIONS
 * @property \XF\Entity\Phrase MasterTitle
 */
class Package extends Entity
{
    public function canPurchase()
    {
        return true;
    }

    /**
     * @return \XF\Phrase
     */
    public function getTitle()
    {
        return \XF::phrase($this->getPhraseName());
    }

    public function getPhraseName()
    {
        return 'z61_package_title.' . $this->package_id;
    }

    public function getMasterPhrase()
    {
        $phrase = $this->MasterTitle;

        if (!$phrase)
        {
            $phrase = $this->_em->create('XF:Phrase');
            $phrase->title = $this->_getDeferredValue(function() { return $this->getPhraseName(); }, 'save');
            $phrase->language_id = 0;
            $phrase->addon_id = '';
        }

        return $phrase;
    }

    /**
     * @return \XF\Phrase|string
     */
    public function getCostPhrase()
    {
        $cost = $this->app()->data('XF:Currency')->languageFormat($this->cost_amount, $this->cost_currency);
        $phrase = $cost;

        if ($this->length_unit)
        {
            if ($this->length_amount > 1)
            {
                $phrase = \XF::phrase("x_for_y_{$this->length_unit}s", [
                    'cost' => $cost,
                    'length' => $this->length_amount
                ]);
            }
            else
            {
                $phrase = \XF::phrase("x_for_one_{$this->length_unit}", [
                    'cost' => $cost
                ]);
            }
        }

        return $phrase;
    }

    public function getExpirationTime($time = null)
    {
        if (empty($time))
        {
            $time = time();
        }

        return strtotime('+'. $this->length_amount. ' '. $this->length_unit, $time);
    }

    protected function _postDelete()
    {
        if ($this->MasterTitle)
        {
            $this->MasterTitle->delete();
        }

        // TODO: delete listings with this package?
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_package';
        $structure->shortName = 'Z61\Classifieds:Package';
        $structure->primaryKey = 'package_id';
        $structure->columns = [
            'package_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'display_order' => ['type' => self::UINT, 'default' => 0],
            'active' => ['type' => self::BOOL, 'default' => true],
            'cost_amount' => [
                'type' => self::FLOAT, 'required' => true,
                'verify' => 'verifyCostAmount'
            ],
            'cost_currency' => ['type' => self::STR, 'required' => true],
            'length_amount' => ['type' => self::UINT, 'max' => 255, 'required' => true],
            'length_unit' => ['type' => self::STR, 'default' => '',
                'allowedValues' => ['day', 'month', 'year', '']
            ],
            'payment_profile_ids' => ['type' => self::LIST_COMMA,
                'list' => ['type' => 'posint', 'unique' => true, 'sort' => SORT_NUMERIC]
            ]
        ];
        $structure->behaviors = [];
        $structure->getters = [
            'title' => true,
            'description' => true,
            'cost_phrase' => true,
            'purchasable_type_id' => true
        ];
        $structure->relations = [
            'MasterTitle' => [
                'entity' => 'XF:Phrase',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['language_id', '=', 0],
                    ['title', '=', 'z61_package_title.', '$package_id']
                ]
            ]
        ];

        return $structure;
    }

    protected function verifyCostAmount(&$choice)
    {
        //if ($choice > 0 && empty($this->payment_profile_ids))
        //{
        //    $this->error(\XF::phrase('z61_classifieds_paid_packages_must_provide_at_least_one_payment_profile'));
        //    return false;
        //}

        return true;
    }

    /**
     * @return \Z61\Classifieds\Repository\Package
     */
    protected function getPackageRepo()
    {
        return $this->repository('Z61\Classifieds:Package');
    }
}