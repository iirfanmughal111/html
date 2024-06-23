<?php

namespace XenAddons\Showcase\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $user_id
 * @property int $item_id
 * @property bool $email_subscribe
 *
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Item $Item
 * @property \XF\Entity\User $User
 */
class ItemWatch extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_watch';
		$structure->shortName = 'XenAddons\Showcase:ItemWatch';
		$structure->primaryKey = ['user_id', 'item_id'];
		$structure->columns = [
			'user_id' => ['type' => self::UINT, 'required' => true],
			'item_id' => ['type' => self::UINT, 'required' => true],
			'email_subscribe' => ['type' => self::BOOL, 'default' => false]
		];
		$structure->getters = [];
		$structure->relations = [
			'Item' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => 'item_id',
				'primary' => true
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
		];

		return $structure;
	}
}