<?php


namespace Z61\Classifieds\Entity;


use XF\Entity\AbstractField;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property string field_id
 * @property int display_order
 * @property string field_type
 * @property array field_choices
 * @property string match_type
 * @property array match_params
 * @property int max_length
 * @property bool required
 * @property string display_template
 * @property string display_group
 *
 * GETTERS
 * @property \XF\Phrase title
 * @property \XF\Phrase description
 *
 * RELATIONS
 * @property \XF\Entity\Phrase MasterTitle
 * @property \XF\Entity\Phrase MasterDescription
 * @property \Z61\Classifieds\Entity\CategoryField[] CategoryFields
 */
class ListingField extends AbstractField
{
    protected function getClassIdentifier()
    {
        return 'Z61\Classifieds:ListingField';
    }

    protected static function getPhrasePrefix()
    {
        return 'classifieds_listing_field';
    }

    protected function _postDelete()
    {
        /** @var \Z61\Classifieds\Repository\CategoryField $repo */
        $repo = $this->repository('Z61\Classifieds:CategoryField');
        $repo->removeFieldAssociations($this);

        $this->db()->delete('xf_z61_classifieds_listing_field_value', 'field_id = ?', $this->field_id);

        parent::_postDelete();
    }

    public static function getStructure(Structure $structure)
    {
        self::setupDefaultStructure(
            $structure,
            'xf_z61_classifieds_listing_field',
            'Z61\Classifieds:ListingField',
            [
                'groups' => ['above', 'below', 'extra'],
            ]
        );

        $structure->relations['CategoryFields'] = [
            'entity' => 'Z61\Classifieds:CategoryField',
            'type' => self::TO_MANY,
            'conditions' => 'field_id'
        ];

        return $structure;
    }
}