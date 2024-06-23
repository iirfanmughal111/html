<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class License
 * @package DBTech\eCommerce\Repository
 */
class License extends Repository
{
	/**
	 * @param \XF\Mvc\Entity\ArrayCollection|null $entries
	 * @param int $rootId
	 * @return \XF\Tree
	 */
	public function createLicenseTree(?ArrayCollection $entries = null, int $rootId = 0): \XF\Tree
	{
		if ($entries === null)
		{
			$entries = $this->findLicensesForList()->fetch();
		}
		
		return new \XF\Tree($entries, 'parent_license_id', $rootId);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\License $license
	 * @param string $action
	 * @param string $reason
	 * @param array $extra
	 * @param \XF\Entity\User|null $forceUser
	 *
	 * @return bool
	 */
	public function sendModeratorActionAlert(
		\DBTech\eCommerce\Entity\License $license,
		string $action,
		string $reason = '',
		array $extra = [],
		?\XF\Entity\User $forceUser = null
	): bool {
		if (!$forceUser)
		{
			if (!$license->user_id || !$license->User)
			{
				return false;
			}
			
			$forceUser = $license->User;
		}
		
		$extra = array_merge([
			'title' => $license->title,
			'prefix_id' => $license->Product->prefix_id,
			'license_key' => $license->license_key,
			'link' => $this->app()->router('public')->buildLink('nopath:dbtech-ecommerce/licenses/license', $license),
			'reason' => $reason,
			'depends_on_addon_id' => 'DBTech/eCommerce',
		], $extra);
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0,
			'',
			'user',
			$license->user_id,
			"dbt_ecom_license_{$action}",
			$extra
		);
		
		return true;
	}
	
	/**
	 * @return Finder
	 */
	public function findLicensesForList(): Finder
	{
		/** @var \DBTech\eCommerce\Finder\License $finder */
		$finder = $this->finder('DBTech\eCommerce:License');
		
		return $finder->order('purchase_date', 'DESC')->order('license_id', 'DESC');
	}
	
	/**
	 * @param string $licenseKey
	 *
	 * @return Finder
	 */
	public function findLicenseByKey(string $licenseKey): Finder
	{
		/** @var \DBTech\eCommerce\Finder\License $finder */
		$finder = $this->finder('DBTech\eCommerce:License');
		
		return $finder->where('license_key', $licenseKey);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\License $thisLicense
	 *
	 * @return \DBTech\eCommerce\Finder\License
	 * @throws \InvalidArgumentException
	 */
	public function findOtherLicensesByUser(\DBTech\eCommerce\Entity\License $thisLicense): \DBTech\eCommerce\Finder\License
	{
		/** @var \DBTech\eCommerce\Finder\License $productFinder */
		$productFinder = $this->finder('DBTech\eCommerce:License');
		
		$productFinder
			->with([
				'User',
				'Product',
				'Product.Permissions|' . \XF::visitor()->permission_combination_id,
				'Product.Category',
				'Product.Category.Permissions|' . \XF::visitor()->permission_combination_id
			])
			->where('license_state', 'visible')
			->where('user_id', $thisLicense->user_id)
			->where('license_id', '<>', $thisLicense->license_id)
			->setDefaultOrder('purchase_date', 'desc');
		
		return $productFinder;
	}
	
	/**
	 * @param int $userId
	 * @param array|null $viewableCategoryIds
	 * @param array $limits
	 *
	 * @return \DBTech\eCommerce\Finder\License
	 * @throws \InvalidArgumentException
	 */
	public function findLicensesByUser(int $userId, ?array $viewableCategoryIds = null, array $limits = []): \DBTech\eCommerce\Finder\License
	{
		/** @var \DBTech\eCommerce\Finder\License $licenseFinder */
		$licenseFinder = $this->finder('DBTech\eCommerce:License');
		
		$licenseFinder->where('user_id', $userId)
			->setDefaultOrder('purchase_date', 'desc')
			->with('full|category')
		;
		
		if (is_array($viewableCategoryIds))
		{
			// if we have viewable category IDs, we likely have those permissions
			$licenseFinder->where('Product.product_category_id', $viewableCategoryIds);
		}
		else
		{
			$licenseFinder->with('Product.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => $userId == \XF::visitor()->user_id
		], $limits);
		
		if ($limits['visibility'])
		{
			$licenseFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
		}
		
		return $licenseFinder;
	}
	
	/**
	 * @param int $cutOff
	 *
	 * @return \DBTech\eCommerce\Finder\License
	 * @throws \InvalidArgumentException
	 */
	public function findExpiringLicenses(int $cutOff): \DBTech\eCommerce\Finder\License
	{
		/** @var \DBTech\eCommerce\Finder\License $licenseFinder */
		$licenseFinder = $this->finder('DBTech\eCommerce:License');
		
		$licenseFinder->with('full')
			->where('expiry_date', '<>', 0)
			->where('expiry_date', '>', \XF::$time)
			->where('expiry_date', '<', $cutOff)
			->where('user_id', '!=', 0)
			->where('Product.parent_product_id', 0)
		;
		
		return $licenseFinder;
	}
	
	/**
	 * @return \DBTech\eCommerce\Finder\License
	 * @throws \InvalidArgumentException
	 */
	public function findExpiredLicenses(): \DBTech\eCommerce\Finder\License
	{
		/** @var \DBTech\eCommerce\Finder\License $licenseFinder */
		$licenseFinder = $this->finder('DBTech\eCommerce:License');
		
		$licenseFinder->with('full')
			->where('expiry_date', '<>', 0)
			->where('expiry_date', '<', \XF::$time)
			->where('user_id', '!=', 0)
			->where('Product.parent_product_id', 0)
		;
		
		return $licenseFinder;
	}
	
	/**
	 * @param \XF\Entity\User|int $userId
	 * @param null|int $cutOff
	 *
	 * @return \DBTech\eCommerce\Finder\License
	 * @throws \InvalidArgumentException
	 */
	public function findLicensesToRenew($userId, ?int $cutOff = null): \DBTech\eCommerce\Finder\License
	{
		if ($userId instanceof \XF\Entity\User)
		{
			$userId = $userId->user_id;
		}
		
		if ($cutOff === null)
		{
			$option = $this->options()->dbtechEcommerceExpiryReminder;
			
			if ($option['send_reminder'])
			{
				$cutOff = strtotime('+' . $option['expiry_length_amount'] . ' ' . $option['expiry_length_unit'], \XF::$time);
			}
			else
			{
				$cutOff = strtotime('+7 days', \XF::$time);
			}
		}
		
		/** @var \DBTech\eCommerce\Finder\License $licenseFinder */
		$licenseFinder = $this->finder('DBTech\eCommerce:License');
		
		$licenseFinder->with('full')
			->where('license_state', 'visible')
			->where('expiry_date', '<>', 0)
			->where('expiry_date', '<', $cutOff)
			->where('user_id', $userId)
			->where('Product.parent_product_id', 0)
		;
		
		return $licenseFinder;
	}
	
	/**
	 * @param int $userId
	 * @param int[]|int $productId
	 *
	 * @return \DBTech\eCommerce\Finder\License
	 * @throws \InvalidArgumentException
	 */
	public function findLicensesByUserAndProduct(int $userId, $productId): \DBTech\eCommerce\Finder\License
	{
		/** @var \DBTech\eCommerce\Finder\License $licenseFinder */
		$licenseFinder = $this->finder('DBTech\eCommerce:License');
		
		$licenseFinder->where('user_id', $userId)
			->with(['product|category', 'Product.LatestVersion', 'LatestDownloaded'])
			->where('product_id', $productId)
			->setDefaultOrder('purchase_date', 'desc')
		;
		
		return $licenseFinder;
	}
	
	/**
	 * @param int $userId
	 * @param int $productId
	 * @param bool $onlyValid
	 *
	 * @return array|\XF\Mvc\Entity\ArrayCollection
	 */
	public function getLicensesByUserAndProduct(int $userId, int $productId, bool $onlyValid = true)
	{
		return $this->findLicensesByUserAndProduct($userId, $productId)
			->fetch()
			->pluck(function (\DBTech\eCommerce\Entity\License $license) use ($onlyValid): ?array
			{
				if ($onlyValid && !$license->isValid())
				{
					return null;
				}

				$title = $license->title;

				/** @var \XF\CustomField\Set $fieldSet */
				$fieldSet = $license->license_fields;
				$fieldDefinition = $fieldSet->getDefinitionSet()
					->filterGroup('list');
				$definitions = $fieldDefinition->getFieldDefinitions();

				/** @var \XF\CustomField\Definition $definition */
				foreach ($definitions as $fieldDefinition)
				{
					$value = $fieldSet->getFieldValue($fieldDefinition['field_id']);
					$title .= ' - ' . ($value ?: 'N/A');
				}

				return [$license->license_key, $title];
			})
			;
	}
	
	/**
	 * @param int $userId
	 *
	 * @return int
	 */
	public function getUserLicenseCount(int $userId): int
	{
		return (int)$this->db()->fetchOne("
			SELECT COUNT(license_id)
			FROM xf_dbtech_ecommerce_license
			WHERE user_id = ?
				AND license_state = 'visible'
		", $userId);
	}
	
	/**
	 * @param int|null $cutOff
	 *
	 * @throws \Exception
	 */
	public function sendExpiryReminders(?int $cutOff = null)
	{
		$option = $this->options()->dbtechEcommerceExpiryReminder;
		
		if (!$option['send_reminder'])
		{
			return;
		}
		
		if ($cutOff === null)
		{
			$cutOff = strtotime('+' . $option['expiry_length_amount'] . ' ' . $option['expiry_length_unit'], \XF::$time);
		}
		
		$licensesByUser = $this->findExpiringLicenses($cutOff)
			->where('sent_expiring_reminder', 0)
			->fetch()
			->filter(function (\DBTech\eCommerce\Entity\License $license): ?\DBTech\eCommerce\Entity\License
			{
				$canRenew = \XF::asVisitor($license->User, function () use ($license): bool
				{
					return $license->canRenew();
				});
				
				if (!$canRenew)
				{
					return null;
				}
				
				return $license;
			})
			->groupBy('user_id')
		;
		
		/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
		foreach ($licensesByUser as $userId => $licenses)
		{
			foreach ($licenses as $license)
			{
				if (!$license->isExpired())
				{
					// Expired licenses are handled separately
					
					/** @var \DBTech\eCommerce\Service\License\Notifier $notifier */
					$notifier = $this->app()->service('DBTech\eCommerce:License\Notifier', $license);
					$notifier->notify('expiring');
				}
			}
			
			$this->app()->jobManager()->enqueueUnique(
				'dbtEcomExpiryReminder' . $userId,
				'DBTech\eCommerce:LicenseExpiryEmail',
				[
					'criteria' => [
						'no_empty_email' => true,
						'user_id' => $userId,
						'Option'	=> [
							'dbtech_ecommerce_license_expiry_email_reminder' => true
						]
					],
					'licenseIds'  => array_keys($licenses)
				],
				false
			);
		}
	}
	
	/**
	 *
	 * @throws \Exception
	 */
	public function sendExpiredAlerts()
	{
		/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
		$licenses = $this->findExpiredLicenses()
			->with('User', true)
			->where('sent_expired_reminder', 0)
			->fetch()
			->filter(function (\DBTech\eCommerce\Entity\License $license): ?\DBTech\eCommerce\Entity\License
			{
				$canRenew = \XF::asVisitor($license->User, function () use ($license): bool
				{
					return $license->canRenew();
				});
				
				if (!$canRenew)
				{
					return null;
				}
				
				return $license;
			})
		;
		
		foreach ($licenses as $license)
		{
			/** @var \DBTech\eCommerce\Service\License\Notifier $notifier */
			$notifier = $this->app()->service('DBTech\eCommerce:License\Notifier', $license);
			$notifier->notify('expired');
			
			$license->fastUpdate('sent_expired_reminder', true);
		}
	}
	
	/**
	 * @param ArrayCollection $licenses
	 *
	 * @return ArrayCollection
	 */
	public function filterLicensesForApiResponse(ArrayCollection $licenses): ?ArrayCollection
	{
		$fieldId = $this->options()->dbtechEcommerceApiLicenseFilterField;
		if (!$fieldId)
		{
			return $licenses;
		}
		
		$boardUrl = $this->app()->request()->getServer('HTTP_X_DRAGONBYTE_BOARDURL');
		return $licenses->filter(
			function (\DBTech\eCommerce\Entity\License $license) use ($boardUrl, $fieldId): ?\DBTech\eCommerce\Entity\License
			{
				if (empty($license->license_fields_[$fieldId]))
				{
					return null;
				}
			
				if ($this->options()->dbtechEcommerceApiLicenseFilterStrict)
				{
					if ($license->license_fields_[$fieldId] != $boardUrl)
					{
						return null;
					}
				}
				else
				{
					$licenseHost = parse_url($license->license_fields_[$fieldId], PHP_URL_HOST);
					$boardHost = parse_url($boardUrl, PHP_URL_HOST);
					if ($licenseHost != $boardHost)
					{
						return null;
					}
				}
			
				return $license;
			}
		);
	}
}