<?php

namespace XenAddons\Showcase\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $item_id
 * @property int $feature_date
 *
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Item $Item
 */
class ItemFeature extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_feature';
		$structure->shortName = 'XenAddons\Showcase:ItemFeature';
		$structure->primaryKey = 'item_id';
		$structure->columns = [
			'item_id' => ['type' => self::UINT, 'required' => true],
			'feature_date' => ['type' => self::UINT, 'default' => \XF::$time]
		];
		$structure->getters = [];
		$structure->relations = [
			'Item' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => 'item_id',
				'primary' => true
			]
		];

		return $structure;
	}
}