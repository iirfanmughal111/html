<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class UserOption
 * @package DBTech\eCommerce\XF\Entity
 */
class UserOption extends XFCP_UserOption
{
	protected function _setupDefaults()
	{
		parent::_setupDefaults();

		$options = \XF::options();
		$defaults = $options->registrationDefaults;
		
		$this->dbtech_ecommerce_email_on_sale = isset($defaults['dbtech_ecommerce_email_on_sale'])
			? (bool)$defaults['dbtech_ecommerce_email_on_sale']
			: false;
		
		$this->dbtech_ecommerce_order_email_reminder = isset($defaults['dbtech_ecommerce_order_email_reminder'])
			? (bool)$defaults['dbtech_ecommerce_order_email_reminder']
			: false;
		
		$this->dbtech_ecommerce_license_expiry_email_reminder = isset($defaults['dbtech_ecommerce_license_expiry_email_reminder'])
			? (bool)$defaults['dbtech_ecommerce_license_expiry_email_reminder']
			: false;
	}

	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 * @noinspection PhpMissingReturnTypeInspection
	 */
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);
		
		$structure->columns['dbtech_ecommerce_email_on_sale'] = ['type' => self::BOOL, 'default' => true];
		$structure->columns['dbtech_ecommerce_order_email_reminder'] = ['type' => self::BOOL, 'default' => true];
		$structure->columns['dbtech_ecommerce_license_expiry_email_reminder'] = ['type' => self::BOOL, 'default' => true];

		return $structure;
	}
}