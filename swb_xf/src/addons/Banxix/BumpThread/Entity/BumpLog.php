<?php

namespace Banxix\BumpThread\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int id
 * @property int thread_id
 * @property int user_id
 * @property int bump_date
 *
 * RELATIONS
 * @property \Banxix\BumpThread\XF\Entity\Thread Thread
 * @property \Banxix\BumpThread\XF\Entity\User User
 */
class BumpLog extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_bump_thread_log';
		$structure->shortName = 'Banxix:BumpLog';
		$structure->primaryKey = 'id';
		$structure->columns = [
			'id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'thread_id' => ['type' => self::UINT, 'required' => true, 'api' => true],
			'user_id' => ['type' => self::UINT, 'required' => true, 'api' => true],
			'bump_date' => ['type' => self::UINT, 'required' => true, 'default' => \XF::$time, 'api' => true],
		];
		$structure->relations = [
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true,
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true,
				'api' => true
			],
		];

		return $structure;
	}
}