<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Pub\Controller;

use XF\Mvc\FormAction;

/**
 * Class Account
 *
 * @package DBTech\eCommerce\XF\Pub\Controller
 */
class Account extends XFCP_Account
{
	/**
	 * @param \XF\Entity\User $visitor
	 *
	 * @return FormAction
	 * @throws \InvalidArgumentException
	 */
	protected function accountDetailsSaveProcess(\XF\Entity\User $visitor)
	{
		$form = parent::accountDetailsSaveProcess($visitor);
		$this->_saveDbtechEcommerceEmailPreferences($form, $visitor);
		return $form;
	}
	
	/**
	 * @param \XF\Entity\User $visitor
	 *
	 * @return FormAction
	 * @throws \InvalidArgumentException
	 */
	protected function preferencesSaveProcess(\XF\Entity\User $visitor)
	{
		$form = parent::preferencesSaveProcess($visitor);
		$this->_saveDbtechEcommerceEmailPreferences($form, $visitor);
		return $form;
	}
	
	/**
	 * @param \XF\Entity\User $visitor
	 *
	 * @return FormAction
	 * @throws \InvalidArgumentException
	 */
	protected function savePrivacyProcess(\XF\Entity\User $visitor)
	{
		$form = parent::savePrivacyProcess($visitor);
		$this->_saveDbtechEcommerceEmailPreferences($form, $visitor);
		return $form;
	}
	
	/**
	 * @param FormAction $form
	 * @param \XF\Entity\User $visitor
	 */
	protected function _saveDbtechEcommerceEmailPreferences(FormAction $form, \XF\Entity\User $visitor)
	{
		$input = $this->filter([
			'option' => [
				'dbtech_ecommerce_email_on_sale'                 => 'bool',
				'dbtech_ecommerce_order_email_reminder'          => 'bool',
				'dbtech_ecommerce_license_expiry_email_reminder' => 'bool',
			],
		]);
		
		$userOptions = $visitor->getRelationOrDefault('Option');
		$form->setupEntityInput($userOptions, $input['option']);
	}
}