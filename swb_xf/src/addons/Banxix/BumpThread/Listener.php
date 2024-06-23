<?php

namespace Banxix\BumpThread;

use XF\Mvc\Entity\Entity;

/**
 * Class Listener
 *
 * @package Banxix\BumpThread
 */
class Listener
{
	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function threadEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->columns['bump_thread_disabled'] = ['type' => Entity::BOOL, 'default' => false, 'api' => true];

		$structure->relations['BumpLog'] = [
			'entity' => 'Banxix\BumpThread:BumpLog',
			'type' => Entity::TO_MANY,
			'conditions' => 'thread_id',
			'order' => 'bump_date',
		];
	}

	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function userEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->relations['BumpLog'] = [
			'entity' => 'Banxix\BumpThread:BumpLog',
			'type' => Entity::TO_MANY,
			'conditions' => 'user_id',
			'order' => 'bump_date',
		];
	}

}