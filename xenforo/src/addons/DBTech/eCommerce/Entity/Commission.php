<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $commission_id
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property int $last_paid_date
 * @property float $total_payments
 * @property array $product_commissions
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductCommissionValue[] $Products
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\CommissionPayment[] $Payments
 */
class Commission extends Entity
{
	/**
	 * @return bool
	 */
	public function rebuildCounters(): bool
	{
		$this->rebuildLastPayment();
		$this->rebuildTotalPayments();
		
		return true;
	}
	
	/**
	 * @return int
	 */
	public function rebuildLastPayment(): int
	{
		$this->last_paid_date = $this->db()->fetchOne('
			SELECT MAX(payment_date)
			FROM xf_dbtech_ecommerce_commission_payment
			WHERE commission_id = ?
		', $this->commission_id) ?: 0;
		
		return $this->last_paid_date;
	}
	
	/**
	 * @return bool|mixed|null
	 */
	public function rebuildTotalPayments()
	{
		$this->total_payments = $this->db()->fetchOne('
			SELECT SUM(payment_amount)
			FROM xf_dbtech_ecommerce_commission_payment
			WHERE commission_id = ?
		', $this->commission_id) ?: 0;
		
		return $this->total_payments;
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_commission';
		$structure->shortName = 'DBTech\eCommerce:Commission';
		$structure->primaryKey = 'commission_id';
		$structure->columns = [
			'commission_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'name' => ['type' => self::STR, 'required' => true, 'maxLength' => 100],
			'email' => ['type' => self::STR, 'maxLength' => 120],
			'last_paid_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'total_payments' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0],
			'product_commissions' => ['type' => self::JSON_ARRAY, 'default' => []]
		];
		$structure->behaviors = [];
		$structure->getters = [];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Products' => [
				'entity' => 'DBTech\eCommerce:ProductCommissionValue',
				'type' => self::TO_MANY,
				'conditions' => 'commission_id',
				'primary' => true,
				'key' => 'product_id',
				'cascadeDelete' => true
			],
			'Payments' => [
				'entity' => 'DBTech\eCommerce:CommissionPayment',
				'type' => self::TO_MANY,
				'conditions' => 'commission_id',
				'primary' => true,
				'cascadeDelete' => true
			]
		];

		return $structure;
	}
}