<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use function sprintf;
use XF\Entity\AbstractField;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property string $field_id
 * @property int $display_order
 * @property string $field_type
 * @property array $field_choices
 * @property string $match_type
 * @property array $match_params
 * @property int $max_length
 * @property bool $required
 * @property string $display_template
 * @property string $display_group
 *
 * GETTERS
 * @property \XF\Phrase $title
 * @property \XF\Phrase $description
 *
 * RELATIONS
 * @property \XF\Entity\Phrase $MasterTitle
 * @property \XF\Entity\Phrase $MasterDescription
 * @property \XF\Mvc\Entity\AbstractCollection|\Truonglv\Groups\Entity\CategoryField[] $CategoryFields
 */
class Field extends AbstractField
{
    /**
     * @return string
     */
    protected static function getPhrasePrefix()
    {
        return 'tlg_field';
    }

    /**
     * @return string
     */
    protected function getClassIdentifier()
    {
        return 'Truonglv\Groups:Field';
    }

    /**
     * @param mixed $title
     * @return string
     */
    public function getPhraseName($title)
    {
        return sprintf(
            '%s_%s_%s',
            static::getPhrasePrefix(),
            (bool) $title ? 'title' : 'desc',
            $this->field_id
        );
    }

    /**
     * @param mixed $choice
     * @return string
     */
    public function getChoicePhraseName($choice)
    {
        return sprintf(
            '%s_choice_%s_%s',
            static::getPhrasePrefix(),
            $this->field_id,
            $choice
        );
    }

    public static function getStructure(Structure $structure)
    {
        static::setupDefaultStructure(
            $structure,
            'xf_tl_group_field',
            'Truonglv\Groups:Field',
            [
                'groups' => ['above_info', 'below_info', 'extra_tab', 'new_tab']
            ]
        );

        $structure->relations['CategoryFields'] = [
            'type' => self::TO_MANY,
            'entity' => 'Truonglv\Groups:CategoryField',
            'conditions' => 'field_id'
        ];

        $structure->relations['MasterTitle']['conditions'][1][2] = static::getPhrasePrefix() . '_title_';
        $structure->relations['MasterDescription']['conditions'][1][2] = static::getPhrasePrefix() . '_desc_';

        return $structure;
    }

    protected function _postDelete()
    {
        parent::_postDelete();

        $db = $this->db();

        $db->delete(
            'xf_tl_group_category_field',
            'field_id = ?',
            $this->field_id
        );

        $db->delete(
            'xf_tl_group_field_value',
            'field_id = ?',
            $this->field_id
        );
    }
}
