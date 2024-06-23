<?php

namespace DBTech\eCommerce\EmailStop;

use XF\EmailStop\AbstractHandler;

/**
 * Class License
 *
 * @package DBTech\eCommerce\EmailStop
 */
class License extends AbstractHandler
{
	/**
	 * @param \XF\Entity\User $user
	 * @param $contentId
	 *
	 * @return \XF\Phrase
	 */
	public function getStopOneText(\XF\Entity\User $user, $contentId): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_stop_license_expiry_reminder_emails');
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return \XF\Phrase
	 */
	public function getStopAllText(\XF\Entity\User $user): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_stop_license_expiry_reminder_emails');
	}
	
	/**
	 * @param \XF\Entity\User $user
	 * @param $contentId
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function stopOne(\XF\Entity\User $user, $contentId)
	{
		/** @var \DBTech\eCommerce\XF\Entity\UserOption $userOption */
		$userOption = $user->Option;
		$userOption->dbtech_ecommerce_license_expiry_email_reminder = false;
		$userOption->save();
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function stopAll(\XF\Entity\User $user)
	{
		/** @var \DBTech\eCommerce\XF\Entity\UserOption $userOption */
		$userOption = $user->Option;
		$userOption->dbtech_ecommerce_license_expiry_email_reminder = false;
		$userOption->save();
	}
}