<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $user_id
 * @property int $product_id
 * @property bool $email_subscribe
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \XF\Entity\User $User
 */
class ProductWatch extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_watch';
		$structure->shortName = 'DBTech\eCommerce:ProductWatch';
		$structure->primaryKey = ['user_id', 'product_id'];
		$structure->columns = [
			'user_id' => ['type' => self::UINT, 'required' => true],
			'product_id' => ['type' => self::UINT, 'required' => true],
			'email_subscribe' => ['type' => self::BOOL, 'default' => false]
		];
		$structure->getters = [];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
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