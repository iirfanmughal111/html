<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Admin\Controller;

/**
 * Class User
 * @package DBTech\eCommerce\XF\Admin\Controller
 */
class User extends XFCP_User
{
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return \XF\Mvc\FormAction
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function userSaveProcess(\XF\Entity\User $user)
	{
		$form = parent::userSaveProcess($user);

		$input = $this->filter([
			'option' => [
				'dbtech_ecommerce_email_on_sale' => 'bool',
				'dbtech_ecommerce_order_email_reminder' => 'bool',
				'dbtech_ecommerce_license_expiry_email_reminder' => 'bool'
			],
		]);

		$userOptions = $user->getRelationOrDefault('Option');
		$form->setupEntityInput($userOptions, $input['option']);

		return $form;
	}
}