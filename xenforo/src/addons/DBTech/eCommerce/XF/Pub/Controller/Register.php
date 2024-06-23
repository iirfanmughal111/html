<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Pub\Controller;

/**
 * Class Register
 *
 * @package DBTech\eCommerce\XF\Pub\Controller
 */
class Register extends XFCP_Register
{
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @throws \XF\PrintableException
	 * @throws \XF\PrintableException
	 * @throws \XF\PrintableException
	 */
	protected function finalizeRegistration(\XF\Entity\User $user)
	{
		parent::finalizeRegistration($user);
		
		$this->repository('DBTech\eCommerce:Order')->updateOrderUser(
			$user,
			$this->request->getIp()
		);
	}
}