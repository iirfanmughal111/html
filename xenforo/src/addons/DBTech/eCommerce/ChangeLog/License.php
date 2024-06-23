<?php

namespace DBTech\eCommerce\ChangeLog;

use XF\ChangeLog\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class License
 * @package DBTech\eCommerce\ChangeLog
 */
class License extends AbstractHandler
{
	/** @var array */
	protected $userMap = [];
	
	/** @var \DBTech\eCommerce\Entity\LicenseField[] */
	protected $customFields;

	/**
	 * @return array
	 */
	protected function getLabelMap(): array
	{
		return [
			'user_id'             => 'dbtech_ecommerce_license_owner',
			'purchase_date'       => 'dbtech_ecommerce_purchase_date',
			'expiry_date'         => 'dbtech_ecommerce_expiry_date',
			'license_key'         => 'dbtech_ecommerce_license_key',
			'license_state'       => 'status',
		];
	}

	/**
	 * @return array
	 */
	protected function getFormatterMap(): array
	{
		return [
			'user_id'       => 'formatUser',
			'purchase_date' => 'formatDateTime',
			'expiry_date'   => 'formatDateTime',
			'license_state' => 'formatLicenseState',
		];
	}

	/**
	 * @return array
	 */
	protected function getPrefixHandlers(): array
	{
		return [
			'license_fields' => ['labelCustomField', 'formatCustomField']
		];
	}

	/**
	 * @param Entity $entity
	 * @return int|mixed|null
	 */
	public function getDefaultEditUserId(Entity $entity): ?int
	{
		$userId = \XF::visitor()->user_id;
		if (!$userId && isset($entity->user_id))
		{
			// guest edits should be treated as self edits; these are mostly system level edits
			$userId = $entity->user_id;
		}

		return $userId;
	}
	
	/**
	 * @param int $userId
	 *
	 * @return string|int
	 */
	protected function formatUser(int $userId)
	{
		if (isset($this->userMap[$userId]))
		{
			return $this->userMap[$userId];
		}
		
		if ($user = \XF::em()->find('XF:User', $userId))
		{
			$this->userMap[$userId] = $user->username;
		}
		
		return isset($this->userMap[$userId]) ? $this->userMap[$userId] : $userId;
	}
	
	/**
	 * @param string $value
	 *
	 * @return string|\XF\Phrase
	 */
	protected function formatLicenseState(string $value)
	{
		switch ($value)
		{
			case 'visible': return \XF::phrase('dbtech_ecommerce_in_good_standing');
			case 'deleted': return \XF::phrase('deleted');
			default: return $value;
		}
	}

	/**
	 * @param string $field
	 * @return null|\XF\Phrase
	 */
	protected function labelCustomField(string $field): ?\XF\Phrase
	{
		return $this->labelCustomFieldGeneric('DBTech\eCommerce:LicenseField', $field);
	}

	/**
	 * @param string $field
	 * @param $value
	 * @return string
	 */
	protected function formatCustomField(string $field, $value): string
	{
		return $this->formatCustomFieldGeneric('DBTech\eCommerce:LicenseField', $field, $value);
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return ['Product', 'User'];
	}
}