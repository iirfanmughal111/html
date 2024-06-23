<?php

namespace XenAddons\Showcase\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $item_read_id
 * @property int $user_id
 * @property int $item_id
 * @property int $item_read_date
 * 
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \XenAddons\Showcase\Entity\Item $Item
 */
class ItemRead extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_read';
		$structure->shortName = 'XenAddons\Showcase:ItemRead';
		$structure->primaryKey = 'item_read_id';
		$structure->columns = [
			'item_read_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'item_id' => ['type' => self::UINT, 'required' => true],
			'item_read_date' => ['type' => self::UINT, 'required' => true]
		];
		$structure->getters = [];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Item' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => 'item_id',
				'primary' => true
			],
		];
		$structure->options = [];

		return $structure;
	}
}