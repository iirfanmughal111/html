<?php

namespace DBTech\eCommerce\Entity;

use XF\CustomField\Set;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $download_log_id
 * @property int $product_id
 * @property int $license_id
 * @property int $download_id
 * @property string $product_version
 * @property int $log_date
 * @property int $user_id
 * @property int $ip_id
 * @property array $license_fields_
 * @property array $log_details
 *
 * GETTERS
 * @property Set $license_fields
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\License $License
 * @property \DBTech\eCommerce\Entity\Download $Download
 * @property \XF\Entity\User $User
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\DownloadLogFieldValue[] $LicenseFields
 * @property \XF\Entity\Ip $Ip
 */
class DownloadLog extends Entity
{
	/**
	 * @return Set
	 */
	public function getLicenseFields(): Set
	{
		$fieldDefinitions = $this->app()->container('customFields.dbtechEcommerceLicenses');

		return new Set($fieldDefinitions, $this, 'license_fields');
	}

	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_download_log';
		$structure->shortName = 'DBTech\eCommerce:DownloadLog';
		$structure->contentType = 'dbtech_ecommerce_download';
		$structure->primaryKey = 'download_log_id';
		$structure->columns = [
			'download_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'product_id' => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'license_id' => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'download_id' => ['type' => self::UINT, 'required' => true, 'default' => 0],
			'product_version' => ['type' => self::STR, 'required' => true],
			'log_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'ip_id' => ['type' => self::UINT, 'default' => 0],
			'license_fields' => ['type' => self::JSON_ARRAY, 'default' => []],
			'log_details' => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->behaviors = [
			'XF:CustomFieldsHolder' => [
				'column' => 'license_fields',
				'valueTable' => 'xf_dbtech_ecommerce_download_log_field_value'
			]
		];
		$structure->getters = [
			'license_fields' => true
		];
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
			'LicenseFields' => [
				'entity' => 'DBTech\eCommerce:DownloadLogFieldValue',
				'type' => self::TO_MANY,
				'conditions' => 'download_log_id',
				'key' => 'field_id'
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