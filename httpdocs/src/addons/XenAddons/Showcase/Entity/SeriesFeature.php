<?php

namespace XenAddons\Showcase\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $series_id
 * @property int $feature_date
 *
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\SeriesItem $Series
 */
class SeriesFeature extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_series_feature';
		$structure->shortName = 'XenAddons\Showcase:SeriesFeature';
		$structure->primaryKey = 'series_id';
		$structure->columns = [
			'series_id' => ['type' => self::UINT, 'required' => true],
			'feature_date' => ['type' => self::UINT, 'default' => \XF::$time]
		];
		$structure->getters = [];
		$structure->relations = [
			'Series' => [
				'entity' => 'XenAddons\Showcase:SeriesItem',
				'type' => self::TO_ONE,
				'conditions' => 'series_id',
				'primary' => true
			]
		];

		return $structure;
	}
}