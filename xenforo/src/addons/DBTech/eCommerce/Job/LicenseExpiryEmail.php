<?php

namespace DBTech\eCommerce\Job;

use XF\Job\UserEmail;

/**
 * Class LicenseExpiryEmail
 *
 * @package DBTech\eCommerce\Job
 */
class LicenseExpiryEmail extends UserEmail
{
	/** @var null|\DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection */
	protected $licenses;
	
	
	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \LogicException
	 */
	protected function setupData(array $data): array
	{
		if (empty($data['licenseIds']))
		{
			throw new \LogicException('Cannot trigger this job without any licenses.');
		}
		
		/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
		$licenses = $this->app->finder('DBTech\eCommerce:License')
			->with('full')
			->where('license_id', $data['licenseIds'])
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
		if (!$licenses)
		{
			throw new \LogicException('Cannot trigger this job without any purchasable licenses.');
		}
		
		$this->licenses = $licenses;
		
		$this->defaultData = array_merge($this->extraDefaultData, $this->defaultData);
		
		$data['email']['email_format'] = 'html';
		$data['email']['email_title'] = '{phrase:dbtech_ecommerce_reminder_x_licenses_expiring_at_y_title}';
		$data['email']['email_body'] = '{phrase:dbtech_ecommerce_reminder_x_licenses_expiring_at_y_body}';
		
		$options = \XF::options();
		
		$data['email']['from_name'] = $options->emailSenderName ? $options->emailSenderName : $options->boardTitle;
		$data['email']['from_email'] = $options->defaultEmailAddress;
		
		return parent::setupData($data);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 */
	protected function executeAction(\XF\Entity\User $user)
	{
		if (!$user->Option->dbtech_ecommerce_license_expiry_email_reminder)
		{
			return;
		}
		
		parent::executeAction($user);
		
		foreach ($this->licenses as $license)
		{
			$license->fastUpdate([
				'sent_expiring_reminder' => 1
			]);
		}
	}
	
	/**
	 * @param \XF\Entity\User $user
	 * @param $escape
	 *
	 * @return array
	 */
	protected function prepareTokens(\XF\Entity\User $user, $escape): array
	{
		$unsubLink = $this->app->router('public')->buildLink('canonical:email-stop/content', $user, ['t' => 'dbtech_ecommerce_license']);
		$licenseLink = $this->app->router('public')->buildLink('canonical:dbtech-ecommerce/licenses');
		$renewLink = $this->app->router('public')->buildLink('canonical:dbtech-ecommerce/licenses/renew');
		
		/** @var \XF\Language $language */
		$language = $this->app->language($user->language_id);
		
		$includeList = count($this->licenses) > 0;
		
		$licenseInfo = $includeList ? '<ul>' : '<p>';
		foreach ($this->licenses as $license)
		{
			[$expiryDate, $expiryTime] = $language->getDateTimeParts($license->expiry_date);
			
			$licenseInfo .= ($includeList ? '<li>' : '') .
				$language->renderPhrase('dbtech_ecommerce_license_expiry_item', [
					'title' => $license->Product->full_title,
					'license_key' => $license->license_key,
					'license_link' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce/licenses/license', $license),
					'expiry_date' => $expiryDate,
					'expiry_time' => $expiryTime
				]) .
				($includeList ? '</li>' : '</p>');
		}
		
		if ($includeList)
		{
			$licenseInfo .= '</ul>';
		}
		
		$tokens = [
			'{numLicenses}' => $this->licenses->count(),
			'{boardTitle}' => \XF::options()->boardTitle,
			'{name}' => $user->username,
			'{email}' => $user->email,
			'{id}' => $user->user_id,
			'{unsub}' => $unsubLink,
			'{licenseArea}' => $licenseLink,
			'{renewLink}' => $renewLink
		];
		
		if ($escape)
		{
			array_walk($tokens, function (&$value)
			{
				if (is_string($value))
				{
					$value = htmlspecialchars($value);
				}
			});
		}
		
		// This one shouldn't get escaped
		$tokens['{licenses}'] = $licenseInfo;
		
		return $tokens;
	}
}