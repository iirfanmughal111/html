<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $coupon_log_id
 * @property int $order_id
 * @property int $order_item_id
 * @property int $product_id
 * @property int $coupon_id
 * @property float $coupon_discounts
 * @property string $currency
 * @property int $log_date
 * @property int $user_id
 * @property int $ip_id
 * @property array $log_details
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Order $Order
 * @property \DBTech\eCommerce\Entity\OrderItem $OrderItem
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\Coupon $Coupon
 * @property \XF\Entity\User $User
 * @property \XF\Entity\Ip $Ip
 */
class CouponLog extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_coupon_log';
		$structure->shortName = 'DBTech\eCommerce:CouponLog';
		$structure->primaryKey = 'coupon_log_id';
		$structure->columns = [
			'coupon_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'order_id' => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'order_item_id' => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'product_id' => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'coupon_id' => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'coupon_discounts' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'currency' => ['type' => self::STR, 'maxLength' => 3,
						   'default' => \XF::options()->dbtechEcommerceCurrency
			],
			'log_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'log_details' => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->behaviors = [];
		$structure->getters = [];
		$structure->relations = [
			'Order' => [
				'entity' => 'DBTech\eCommerce:Order',
				'type' => self::TO_ONE,
				'conditions' => 'order_id',
				'primary' => true
			],
			'OrderItem' => [
				'entity' => 'DBTech\eCommerce:OrderItem',
				'type' => self::TO_ONE,
				'conditions' => 'order_item_id',
				'primary' => true
			],
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			],
			'Coupon' => [
				'entity' => 'DBTech\eCommerce:Coupon',
				'type' => self::TO_ONE,
				'conditions' => 'coupon_id',
				'primary' => true
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Ip' => [
				'entity' => 'XF:Ip',
				'type' => self::TO_ONE,
				'conditions' => 'ip_id',
				'primary' => true
			]
		];

		return $structure;
	}
}