<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $user_id
 * @property int $license_length_amount
 * @property string $license_length_unit
 * @property array $available_products
 *
 * GETTERS
 * @property \XF\Phrase $length
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductDistributorValue[] $Products
 */
class Distributor extends Entity
{
	/**
	 * @return bool
	 */
	public function isLifetime(): bool
	{
		return $this->license_length_amount == 0 OR $this->license_length_unit === '';
	}
	
	/**
	 * @return \XF\Phrase
	 */
	public function getLength(): \XF\Phrase
	{
		if ($this->isLifetime())
		{
			return \XF::phrase('dbtech_ecommerce_renewal_period_lifetime');
		}
		
		if ($this->license_length_amount == 1)
		{
			return \XF::phrase('dbtech_ecommerce_renewal_period_one_' . $this->license_length_unit);
		}
		
		return \XF::phrase('dbtech_ecommerce_renewal_period_x_' . $this->license_length_unit, [
			'length' => $this->license_length_amount
		]);
	}
	
	/**
	 * @return int
	 */
	public function getEffectiveMaxLength(): int
	{
		if ($this->isLifetime())
		{
			return 0;
		}
		
		return strtotime('+' . $this->license_length_amount . ' ' . $this->license_length_unit, \XF::$time);
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postSave()
	{
		$this->db()->query('
			UPDATE xf_user
			SET dbtech_ecommerce_is_distributor = 1
			WHERE user_id = ?
		', $this->user_id);
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postDelete()
	{
		$this->db()->query('
			UPDATE xf_user
			SET dbtech_ecommerce_is_distributor = 0
			WHERE user_id = ?
		', $this->user_id);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_distributor';
		$structure->shortName = 'DBTech\eCommerce:Distributor';
		$structure->primaryKey = 'user_id';
		$structure->columns = [
			'user_id' => ['type' => self::UINT, 'required' => true],
			'license_length_amount' => ['type' => self::UINT, 'max' => 255, 'required' => true],
			'license_length_unit' => ['type' => self::STR, 'default' => '',
							  'allowedValues' => ['day', 'month', 'year', '']
			],
			'available_products' => ['type' => self::JSON_ARRAY, 'default' => []]
		];
		$structure->behaviors = [];
		$structure->getters = [
			'length' => true
		];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Products' => [
				'entity' => 'DBTech\eCommerce:ProductDistributorValue',
				'type' => self::TO_MANY,
				'conditions' => 'user_id',
				'primary' => true,
				'key' => 'product_id',
				'cascadeDelete' => true
			]
		];

		return $structure;
	}
}