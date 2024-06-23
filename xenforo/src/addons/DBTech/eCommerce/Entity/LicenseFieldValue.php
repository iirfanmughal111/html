<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $license_id
 * @property string $field_id
 * @property string $field_value
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\License $License
 */
class LicenseFieldValue extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_license_field_value';
		$structure->shortName = 'DBTech\eCommerce:LicenseFieldValue';
		$structure->primaryKey = ['license_id', 'field_id'];
		$structure->columns = [
			'license_id' => ['type' => self::UINT, 'required' => true],
			'field_id' => ['type' => self::STR, 'maxLength' => 25,
				'match' => 'alphanumeric'
			],
			'field_value' => ['type' => self::STR, 'default' => '']
		];
		$structure->getters = [];
		$structure->relations = [
			'License' => [
				'entity' => 'DBTech\eCommerce:License',
				'type' => self::TO_ONE,
				'conditions' => 'license_id',
				'primary' => true
			],
		];

		return $structure;
	}
}