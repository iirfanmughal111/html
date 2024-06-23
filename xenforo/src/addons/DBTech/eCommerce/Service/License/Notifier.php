<?php

namespace DBTech\eCommerce\Service\License;

use DBTech\eCommerce\Entity\License;
use XF\Service\AbstractService;

/**
 * Class Notifier
 *
 * @package DBTech\eCommerce\Service\License
 */
class Notifier extends AbstractService
{
	/** @var \DBTech\eCommerce\Entity\License */
	protected $license;


	/**
	 * Notifier constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\License $license
	 */
	public function __construct(\XF\App $app, License $license)
	{
		parent::__construct($app);

		$this->license = $license;
	}
	
	/**
	 * @param string $action
	 * @param array $extra
	 */
	public function notify(string $action, array $extra = [])
	{
		$license = $this->license;
		
		$extra = array_merge([
			'title' => $license->title,
			'prefix_id' => $license->Product->prefix_id,
			'license_key' => $license->license_key,
			'link' => $this->app->router('public')->buildLink('nopath:dbtech-ecommerce/licenses/license', $license),
			'depends_on_addon_id' => 'DBTech/eCommerce',
		], $extra);
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->app->repository('XF:UserAlert');
		$alertRepo->alert(
			$license->User,
			0,
			'',
			'dbtech_ecommerce_license',
			$license->license_id,
			$action,
			$extra
		);
	}
}