<?php

namespace DBTech\eCommerce\Repository;

use DBTech\eCommerce\Exception\CountryCodeMismatchException;
use DBTech\eCommerce\Exception\InvalidVatNumberException;
use XF\Mvc\Entity\Repository;

/**
 * Class Address
 * @package DBTech\eCommerce\Repository
 */
class Address extends Repository
{
	/**
	 * @return \DBTech\eCommerce\Finder\Address|\XF\Mvc\Entity\Finder
	 */
	public function findAddressesForList()
	{
		return $this->finder('DBTech\eCommerce:Address')
			->where('user_id', \XF::visitor()->user_id)
			->order('is_default', 'desc')
			->orderTitle();
	}
	
	/**
	 * @return array|\XF\Mvc\Entity\ArrayCollection
	 */
	public function getAddressTitlePairs()
	{
		$addresses = $this->findAddressesForList();
		return $addresses->fetch()->pluckNamed('title', 'address_id');
	}
	
	/**
	 * @param string $vatId
	 * @param \DBTech\eCommerce\Entity\Address $address
	 * @param null $error
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function validateVatId(string $vatId, \DBTech\eCommerce\Entity\Address $address, &$error = null): bool
	{
		/** @var Country $countryRepo */
		$countryRepo = $this->repository('DBTech\eCommerce:Country');
		
		try
		{
			$countryRepo->validateVatIdForCountry($vatId, $address->country_code);
		}
		catch (InvalidVatNumberException $e)
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_invalid_vat_number');
			return false;
		}
		catch (CountryCodeMismatchException $e)
		{
			$error = \XF::phraseDeferred('dbtech_ecommerce_billing_country_vat_country_mismatch');
			return false;
		}
		
		if ($this->options()->dbtechEcommerceSalesTax['enhancedVatValidation'])
		{
			/** @var Country $countryRepo */
			$countryRepo = $this->repository('DBTech\eCommerce:Country');
			try
			{
				$countryRepo->validateBillingAddressCountry($address->country_code);
			}
			catch (CountryCodeMismatchException $e)
			{
				$error = \XF::phraseDeferred('dbtech_ecommerce_billing_country_ip_mismatch');
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Address $address
	 * @param string $action
	 * @param string $reason
	 * @param array $extra
	 * @param \XF\Entity\User|null $forceUser
	 *
	 * @return bool
	 */
	public function sendModeratorActionAlert(
		\DBTech\eCommerce\Entity\Address $address,
		string $action,
		string $reason = '',
		array $extra = [],
		?\XF\Entity\User $forceUser = null
	): bool {
		if (!$forceUser)
		{
			if (!$address->user_id || !$address->User)
			{
				return false;
			}
			
			$forceUser = $address->User;
		}
		
		$extra = array_merge([
			'title' => $address->title,
			'link' => $this->app()->router('public')->buildLink('nopath:dbtech-ecommerce/account/address-book/view', $address),
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
			$address->user_id,
			"dbt_ecom_address_{$action}",
			$extra
		);
		
		return true;
	}
}