<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int|null $serial_key_id
 * @property int $product_id
 * @property int $license_id
 * @property int $user_id
 * @property string $serial_key
 * @property int $serial_date
 * @property bool $available
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\License $License
 * @property \XF\Entity\User $User
 */
class SerialKey extends Entity
{
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_serial_key';
		$structure->shortName = 'DBTech\eCommerce:SerialKey';
		$structure->primaryKey = 'serial_key_id';
		$structure->columns = [
			'serial_key_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'product_id'    => ['type' => self::UINT, 'required' => true],
			'license_id'    => ['type' => self::UINT, 'required' => true],
			'user_id'       => ['type' => self::UINT, 'required' => true],
			'serial_key'    => [
				'type'     => self::STR, 'maxLength' => 50,
				'required' => true,
				'unique'   => 'dbtech_ecommerce_serial_keys_must_be_unique',
			],
			'serial_date'   => ['type' => self::UINT, 'default' => \XF::$time],
			'available'     => ['type' => self::BOOL, 'default' => true],
		];
		$structure->getters = [];
		$structure->relations = [
			'Product' => [
				'entity'     => 'DBTech\eCommerce:Product',
				'type'       => self::TO_ONE,
				'conditions' => 'product_id',
				'primary'    => true
			],
			'License' => [
				'entity'     => 'DBTech\eCommerce:License',
				'type'       => self::TO_ONE,
				'conditions' => 'license_id',
				'primary'    => true
			],
			'User'    => [
				'entity'     => 'XF:User',
				'type'       => self::TO_ONE,
				'conditions' => 'user_id',
				'primary'    => true
			]
		];

		return $structure;
	}
}