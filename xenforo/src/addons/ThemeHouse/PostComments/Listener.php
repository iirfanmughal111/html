<?php

namespace ThemeHouse\PostComments;

use XF\Mvc\Entity\Entity;

/**
 * Class Listener
 *
 * @package ThemeHouse\PostComments
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
		$structure->columns['thpostcomments_root_reply_count'] = ['type' => Entity::UINT, 'default' => 0];
	}


	/**
	 * Allows direct modification of the Entity structure.
	 *
	 * Event hint: Fully qualified name of the root class that was called.
	 *
	 * @param \XF\Mvc\Entity\Manager $em Entity Manager object.
	 * @param \XF\Mvc\Entity\Structure $structure Entity Structure object.
	 */
	public static function postEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->columns += [
			'thpostcomments_parent_post_id' => ['type' => Entity::UINT, 'required' => true, 'default' => 0, 'api' => true],
			'thpostcomments_root_post_id' => ['type' => Entity::UINT, 'required' => true, 'default' => 0, 'api' => true],
			'thpostcomments_lft' => ['type' => Entity::UINT, 'api' => true],
			'thpostcomments_rgt' => ['type' => Entity::UINT, 'api' => true],
			'thpostcomments_depth' => ['type' => Entity::UINT, 'api' => true]
		];

		$structure->behaviors += [
			'ThemeHouse\PostComments:CommentTreeStructured' => [
				'parentField' => 'thpostcomments_parent_post_id',
				'orderField' => 'position',
				'rootField' => 'thpostcomments_root_post_id',
			]
		];

		$structure->relations += [
			'RootPost' => [
				'entity' => 'XF:Post',
				'type' => Entity::TO_ONE,
				'conditions' => 'thpostcomments_root_post_id',
				'primary' => true,
			],
			'ParentPost' => [
				'entity' => 'XF:Post',
				'type' => Entity::TO_ONE,
				'conditions' => 'thpostcomments_parent_post_id',
				'primary' => true,
			],
			'Comments' => [
				'entity' => 'XF:Post',
				'type' => Entity::TO_MANY,
				'conditions' => [
					['thpostcomments_parent_post_id', '=', '$post_id']
				]
			]
		];
	}


	/**
	 * Fired inside the importers container in the Import sub-container. Add-ons can use this to add
	 * additional importer classes to the importer list. The class names can be fully qualified or the
	 * short class version e.g. AddOn:ClassName.
	 *
	 * @param \XF\SubContainer\Import $container Import sub-container object.
	 * @param \XF\Container $parentContainer Global App object.
	 * @param array $importers Array of importers.
	 */
	public static function importImporterClasses(\XF\SubContainer\Import $container, \XF\Container $parentContainer, array &$importers)
	{
		$importers[] = 'ThemeHouse\PostComments:TruonglvPostReply';
		$importers[] = 'ThemeHouse\PostComments:CMFThread';
	}

}