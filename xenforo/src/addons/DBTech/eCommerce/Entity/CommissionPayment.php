<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $commission_payment_id
 * @property int $commission_id
 * @property int $user_id
 * @property int $ip_id
 * @property int $payment_date
 * @property float $payment_amount
 * @property string $message
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Commission $Commission
 * @property \XF\Entity\User $User
 * @property \XF\Entity\Ip $Ip
 */
class CommissionPayment extends Entity
{
	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postSave()
	{
		if ($this->isInsert())
		{
			$this->db()->query('
				UPDATE xf_dbtech_ecommerce_commission
				SET total_payments = total_payments + ?,
					last_paid_date = GREATEST(last_paid_date, ?)
				WHERE commission_id = ?
			', [$this->payment_amount, $this->payment_date, $this->commission_id]);
		}
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postDelete()
	{
		$this->db()->query('
			UPDATE xf_dbtech_ecommerce_commission
			SET total_payments = GREATEST(0, CAST(total_payments AS SIGNED) - ?)
			WHERE commission_id = ?
		', [$this->payment_amount, $this->commission_id]);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_commission_payment';
		$structure->shortName = 'DBTech\eCommerce:CommissionPayment';
		$structure->contentType = 'dbtech_ecommerce_comm';
		$structure->primaryKey = 'commission_payment_id';
		$structure->columns = [
			'commission_payment_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'commission_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'payment_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'payment_amount' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0],
			'message' => ['type' => self::STR, 'default' => '']
		];
		$structure->behaviors = [];
		$structure->getters = [];
		$structure->relations = [
			'Commission' => [
				'entity' => 'DBTech\eCommerce:Commission',
				'type' => self::TO_ONE,
				'conditions' => 'commission_id',
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