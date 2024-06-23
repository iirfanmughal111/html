<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\ChangeLog;

/**
 * Class User
 * @package DBTech\eCommerce\XF\ChangeLog
 */
class User extends XFCP_User
{
	/**
	 * @return array
	 */
	protected function getLabelMap()
	{
		$previous = parent::getLabelMap();
		
		$previous['dbtech_ecommerce_tos_accept'] = 'dbtech_ecommerce_accepted_terms_of_service';
		$previous['dbtech_ecommerce_email_on_sale'] = 'dbtech_ecommerce_ecommerce_sale_notifications';
		$previous['dbtech_ecommerce_order_email_reminder'] = 'dbtech_ecommerce_ecommerce_order_reminders';
		$previous['dbtech_ecommerce_license_expiry_email_reminder'] = 'dbtech_ecommerce_ecommerce_license_expiry_reminders';

		return $previous;
	}
	
	/**
	 * @return array
	 */
	protected function getFormatterMap()
	{
		$previous = parent::getFormatterMap();
		
		$previous['dbtech_ecommerce_tos_accept'] = 'formatDateTime';
		$previous['dbtech_ecommerce_email_on_sale'] = 'formatYesNo';
		$previous['dbtech_ecommerce_order_email_reminder'] = 'formatYesNo';
		$previous['dbtech_ecommerce_license_expiry_email_reminder'] = 'formatYesNo';

		return $previous;
	}
	
	/**
	 * @return array
	 */
	protected function getProtectedFields()
	{
		$previous = parent::getProtectedFields();
		
		$previous['dbtech_ecommerce_tos_accept'] = true;
		
		return $previous;
	}
}