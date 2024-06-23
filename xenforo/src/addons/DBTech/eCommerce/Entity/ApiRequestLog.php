<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $api_request_log_id
 * @property string $api_key
 * @property int $user_id
 * @property string $ip_address
 * @property int $log_date
 * @property string $request_uri
 * @property string $referrer
 * @property string $board_url
 * @property string $http_host
 * @property string $software
 * @property string $software_version
 * @property array $raw_data
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 */
class ApiRequestLog extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_api_request_log';
		$structure->shortName = 'DBTech\eCommerce:ApiRequestLog';
		$structure->primaryKey = 'api_request_log_id';
		$structure->columns = [
			'api_request_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'api_key' => ['type' => self::STR, 'required' => true],
			'user_id' => ['type' => self::UINT, 'default' => 0],
			'ip_address' => ['type' => self::BINARY, 'maxLength' => 16],
			'log_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'request_uri' => ['type' => self::STR, 'required' => true],
			'referrer' => ['type' => self::STR, 'default' => ''],
			'board_url' => ['type' => self::STR, 'required' => true],
			'http_host' => ['type' => self::STR, 'required' => true],
			'software' => ['type' => self::STR, 'required' => true],
			'software_version' => ['type' => self::STR, 'required' => true],
			'raw_data' => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			]
		];

		return $structure;
	}
}