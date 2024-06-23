<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractField;
use XF\Mvc\ParameterBag;

/**
 * Class LicenseField
 * @package DBTech\eCommerce\Admin\Controller
 */
class LicenseField extends AbstractField
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomLicense');
	}

	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:LicenseField';
	}

	/**
	 * @return string
	 */
	protected function getLinkPrefix(): string
	{
		return 'dbtech-ecommerce/licenses/fields';
	}

	/**
	 * @return string
	 */
	protected function getTemplatePrefix(): string
	{
		return 'dbtech_ecommerce_license_field';
	}
}