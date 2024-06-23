<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $distributor_log_id
 * @property int $distributor_id
 * @property int $product_id
 * @property int $license_id
 * @property int $log_date
 * @property int $user_id
 * @property int $ip_id
 * @property array $log_details
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\License $License
 * @property \XF\Entity\User $Distributor
 * @property \XF\Entity\User $Recipient
 * @property \XF\Entity\Ip $Ip
 */
class DistributorLog extends Entity
{
	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postSave()
	{
		$this->db()->query('
			UPDATE xf_dbtech_ecommerce_product_distributor_value
			SET available_licenses = IF(available_licenses > 0, available_licenses - 1, available_licenses)
			WHERE user_id = ?
				AND product_id = ?
		', [$this->distributor_id, $this->product_id]);
		
		/** @var \DBTech\eCommerce\Repository\ProductDistributor $repo */
		$repo = $this->repository('DBTech\eCommerce:ProductDistributor');
		$repo->rebuildContentAssociationCache([$this->distributor_id]);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_distributor_log';
		$structure->shortName = 'DBTech\eCommerce:DistributorLog';
		$structure->primaryKey = 'distributor_log_id';
		$structure->columns = [
			'distributor_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'distributor_id' => ['type' => self::UINT, 'required' => true],
			'product_id' => ['type' => self::UINT, 'required' => true],
			'license_id' => ['type' => self::UINT, 'required' => true],
			'log_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'log_details' => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->behaviors = [];
		$structure->getters = [];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			],
			'License' => [
				'entity' => 'DBTech\eCommerce:License',
				'type' => self::TO_ONE,
				'conditions' => 'license_id',
				'primary' => true
			],
			'Distributor' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => [
					['user_id', '=', '$distributor_id']
				],
				'primary' => true
			],
			'Recipient' => [
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