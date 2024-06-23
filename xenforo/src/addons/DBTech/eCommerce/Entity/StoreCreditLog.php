<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $store_credit_log_id
 * @property int $order_id
 * @property int $store_credit_amount
 * @property int $log_date
 * @property int $user_id
 * @property int $ip_id
 * @property array $log_details
 *
 * GETTERS
 * @property \XF\Phrase $reason_phrase
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Order $Order
 * @property \XF\Entity\User $User
 * @property \XF\Entity\Ip $Ip
 */
class StoreCreditLog extends Entity
{
	/**
	 * @return \XF\Phrase
	 */
	public function getReasonPhrase(): \XF\Phrase
	{
		if (!isset($this->log_details['reason']))
		{
			return \XF::phrase('dbtech_ecommerce_unknown_reason');
		}

		switch ($this->log_details['reason'])
		{
			case 'admin_adjust':
				$user = $this->_em->find('XF:User', $this->log_details['admin_user_id']);
				
				return \XF::phrase('dbtech_ecommerce_adjusted_by_user', ['user' => $user ? $user->username : \XF::phrase('unknown_user')]);

			case 'auto_adjust':
				return \XF::phrase('dbtech_ecommerce_automatically_adjusted', ['reason' => $this->log_details['adjust_reason']]);

			default:
				return \XF::phrase('dbtech_ecommerce_unknown_reason');
		}
	}

	/**
	 *
	 */
	protected function _preSave()
	{
		if ($this->store_credit_amount == 0)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_number_other_than_zero'), 'store_credit_amount');
		}
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postSave()
	{
		if ($this->user_id && $this->isInsert())
		{
			$this->db()->query('
				UPDATE xf_user
				SET dbtech_ecommerce_store_credit = GREATEST(0, dbtech_ecommerce_store_credit + ?)
				WHERE user_id = ?
			', [$this->store_credit_amount, $this->user_id]);
		}
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_store_credit_log';
		$structure->shortName = 'DBTech\eCommerce:StoreCreditLog';
		$structure->contentType = 'dbtech_ecommerce_credit';
		$structure->primaryKey = 'store_credit_log_id';
		$structure->columns = [
			'store_credit_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'order_id' => ['type' => self::UINT, 'default' => 0],
			'store_credit_amount' => ['type' => self::INT, 'required' => true, 'writeOnce' => true],
			'log_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'log_details' => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->behaviors = [];
		$structure->getters = [
			'reason_phrase' => true
		];
		$structure->relations = [
			'Order' => [
				'entity' => 'DBTech\eCommerce:Order',
				'type' => self::TO_ONE,
				'conditions' => 'order_id',
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