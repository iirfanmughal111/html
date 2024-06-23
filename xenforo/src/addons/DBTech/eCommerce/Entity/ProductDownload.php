<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $product_download_id
 * @property int $download_id
 * @property int $user_id
 * @property int $product_id
 * @property int $last_download_date
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\Download $Download
 * @property \XF\Entity\User $User
 */
class ProductDownload extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_download';
		$structure->shortName = 'DBTech\eCommerce:ProductDownload';
		$structure->primaryKey = 'product_download_id';
		$structure->columns = [
			'product_download_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'download_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'product_id' => ['type' => self::UINT, 'required' => true],
			'last_download_date' => ['type' => self::UINT, 'default' => \XF::$time]
		];
		$structure->getters = [];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			],
			'Download' => [
				'entity' => 'DBTech\eCommerce:Download',
				'type' => self::TO_ONE,
				'conditions' => 'download_id',
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