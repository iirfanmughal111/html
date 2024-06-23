<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use XF;
use function serialize;
use Truonglv\Groups\App;
use XF\Repository\AbstractField;

class Field extends AbstractField
{
    /**
     * @return array
     */
    public function getDisplayGroups()
    {
        return [
            'above_info' => XF::phrase('tlg_above_group_description'),
            'below_info' => XF::phrase('tlg_below_group_description'),
            'extra_tab' => XF::phrase('tlg_extra_information'),
            'new_tab' => XF::phrase('tlg_own_tab')
        ];
    }

    /**
     * @param int $groupId
     * @return array
     */
    public function getGroupFieldValues($groupId)
    {
        $fields = $this->db()->fetchAll('
			SELECT field_value.*, field.field_type
			FROM xf_tl_group_field_value AS field_value
			INNER JOIN xf_tl_group_field AS field ON (field.field_id = field_value.field_id)
			WHERE field_value.group_id = ?
		', $groupId);

        $values = [];
        foreach ($fields as $field) {
            if ($field['field_type'] == 'checkbox' || $field['field_type'] == 'multiselect') {
                $values[$field['field_id']] = \XF\Util\Php::safeUnserialize($field['field_value']);
            } else {
                $values[$field['field_id']] = $field['field_value'];
            }
        }

        return $values;
    }

    /**
     * @param int $groupId
     * @return void
     */
    public function rebuildGroupFieldValuesCache($groupId)
    {
        $cache = $this->getGroupFieldValues($groupId);

        $this->db()->update(
            'xf_tl_group',
            ['custom_fields' => serialize($cache)],
            'group_id = ?',
            $groupId
        );
    }

    /**
     * @return string
     */
    protected function getRegistryKey()
    {
        return App::FIELD_REGISTRY_KEY_NAME;
    }

    /**
     * @return string
     */
    protected function getClassIdentifier()
    {
        return 'Truonglv\Groups:Field';
    }
}
