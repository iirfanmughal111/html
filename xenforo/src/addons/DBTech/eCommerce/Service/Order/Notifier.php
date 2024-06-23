<?php

namespace DBTech\eCommerce\Service\Order;

use DBTech\eCommerce\Entity\Order;
use XF\Service\AbstractService;

/**
 * Class Notifier
 *
 * @package DBTech\eCommerce\Service\Order
 */
class Notifier extends AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Order */
	protected $order;
	
	
	/**
	 * Notifier constructor.
	 *
	 * @param \XF\App $app
	 * @param Order $order
	 */
	public function __construct(\XF\App $app, Order $order)
	{
		parent::__construct($app);

		$this->order = $order;
	}
	
	/**
	 * @param string $action
	 * @param array $extra
	 */
	public function notify(string $action, array $extra = [])
	{
		$order = $this->order;
		
		$extra = array_merge([
			'link' => $this->app->router('public')->buildLink('nopath:dbtech-ecommerce/account/order', $order),
			'depends_on_addon_id' => 'DBTech/eCommerce',
		], $extra);
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->app->repository('XF:UserAlert');
		$alertRepo->alert(
			$order->User,
			0,
			'',
			'dbtech_ecommerce_order',
			$order->order_id,
			$action,
			$extra
		);
	}
}