<?php

namespace XenAddons\Showcase\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $item_id
 * @property string $field_id
 * @property string $field_value
 */
class ItemFieldValue extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_field_value';
		$structure->shortName = 'XenAddons\Showcase:ItemFieldValue';
		$structure->primaryKey = ['item_id', 'field_id'];
		$structure->columns = [
			'item_id' => ['type' => self::UINT, 'required' => true],
			'field_id' => ['type' => self::STR, 'maxLength' => 25,
				'match' => 'alphanumeric'
			],
			'field_value' => ['type' => self::STR, 'default' => '']
		];
		$structure->getters = [];
		$structure->relations = [];

		return $structure;
	}
}