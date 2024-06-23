<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $purchase_log_id
 * @property int $order_id
 * @property int $order_item_id
 * @property int $product_id
 * @property int $license_id
 * @property int $quantity
 * @property int $log_date
 * @property int $user_id
 * @property int $ip_id
 * @property float $cost_amount
 * @property string $currency
 * @property string $log_type
 * @property array $log_details
 *
 * GETTERS
 * @property string $title
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Order $Order
 * @property \DBTech\eCommerce\Entity\OrderItem $OrderItem
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\License $License
 * @property \XF\Entity\User $User
 * @property \XF\Entity\Ip $Ip
 */
class PurchaseLog extends Entity
{
	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		if ($this->License)
		{
			return $this->License->getFullTitle();
		}
		
		if ($this->Product)
		{
			return $this->Product->title;
		}
		
		return '';
	}
	
	/**
	 * @return \XF\Phrase
	 */
	public function getLogTypePhrase(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_purchase_log_type.' . $this->log_type);
	}
	
	/**
	 *
	 */
	protected function rebuildAmountSpent()
	{
		/** @var \DBTech\eCommerce\Repository\Purchase $repo */
		$repo = $this->repository('DBTech\eCommerce:Purchase');
		$amount = $repo->getAmountSpentForUser($this->user_id);
		
		$this->db()->update('xf_user', ['dbtech_ecommerce_amount_spent' => $amount > 0 ? $amount : 0], 'user_id = ?', $this->user_id);
	}
	
	/**
	 *
	 */
	protected function _postSave()
	{
		$this->rebuildAmountSpent();
	}
	
	/**
	 *
	 */
	protected function _postDelete()
	{
		$this->rebuildAmountSpent();
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_purchase_log';
		$structure->shortName = 'DBTech\eCommerce:PurchaseLog';
		$structure->contentType = 'dbtech_ecommerce_purchase';
		$structure->primaryKey = 'purchase_log_id';
		$structure->columns = [
			'purchase_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'order_id'        => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'order_item_id'   => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'product_id'      => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'license_id'      => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'quantity'        => ['type' => self::UINT, 'default' => 1],
			'log_date'        => ['type' => self::UINT, 'default' => \XF::$time],
			'user_id'         => ['type' => self::UINT, 'default' => 0],
			'ip_id'           => ['type' => self::UINT, 'default' => 0],
			'cost_amount'     => ['type' => self::FLOAT, 'required' => true],
			'currency'        => [
				'type'    => self::STR, 'maxLength' => 3,
				'default' => \XF::options()->dbtechEcommerceCurrency
			],
			'log_type'        => [
				'type'          => self::STR, 'default' => 'new',
				'allowedValues' => ['new', 'upgrade', 'renew', 'reversal', 'refunded']
			],
			'log_details'     => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->behaviors = [];
		$structure->getters = [
			'title' => true
		];
		$structure->relations = [
			'Order'     => [
				'entity'     => 'DBTech\eCommerce:Order',
				'type'       => self::TO_ONE,
				'conditions' => 'order_id',
				'primary'    => true
			],
			'OrderItem' => [
				'entity'     => 'DBTech\eCommerce:OrderItem',
				'type'       => self::TO_ONE,
				'conditions' => 'order_item_id',
				'primary'    => true
			],
			'Product'   => [
				'entity'     => 'DBTech\eCommerce:Product',
				'type'       => self::TO_ONE,
				'conditions' => 'product_id',
				'primary'    => true
			],
			'License'   => [
				'entity'     => 'DBTech\eCommerce:License',
				'type'       => self::TO_ONE,
				'conditions' => 'license_id',
				'primary'    => true
			],
			'User'      => [
				'entity'     => 'XF:User',
				'type'       => self::TO_ONE,
				'conditions' => 'user_id',
				'primary'    => true
			],
			'Ip'        => [
				'entity'     => 'XF:Ip',
				'type'       => self::TO_ONE,
				'conditions' => 'ip_id',
				'primary'    => true
			]
		];

		return $structure;
	}
}