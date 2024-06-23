<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\ControllerPlugin;

/**
 * Class Login
 * @package DBTech\eCommerce\XF\Service\User
 */
class Login extends XFCP_Login
{
	/**
	 * @param \XF\Entity\User $user
	 * @param $remember
	 */
	public function completeLogin(\XF\Entity\User $user, $remember)
	{
		parent::completeLogin($user, $remember);
		
		$this->repository('DBTech\eCommerce:Order')->updateOrderUser(
			$user,
			$this->request->getIp()
		);
	}
}