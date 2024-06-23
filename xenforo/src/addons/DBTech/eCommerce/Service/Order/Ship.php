<?php

namespace DBTech\eCommerce\Service\Order;

/**
 * Class Ship
 *
 * @package DBTech\eCommerce\Service\Order
 */
class Ship extends \XF\Service\AbstractService
{
	/** @var \XF\Entity\User */
	protected $user;

	/** @var \DBTech\eCommerce\Entity\Order */
	protected $order;
	
	/** @var bool */
	protected $alert = false;
	
	/** @var string */
	protected $alertReason = '';


	/**
	 * Complete constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Order $order
	 * @param \XF\Entity\User $user
	 */
	public function __construct(\XF\App $app, \DBTech\eCommerce\Entity\Order $order, \XF\Entity\User $user)
	{
		parent::__construct($app);

		$this->user = $user;
		$this->setOrder($order);
	}

	/**
	 * @return \XF\Entity\User
	 */
	public function getUser(): \XF\Entity\User
	{
		return $this->user;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Order $order
	 *
	 * @return $this
	 */
	protected function setOrder(\DBTech\eCommerce\Entity\Order $order): Ship
	{
		$this->order = $order;

		return $this;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\Order
	 */
	public function getOrder(): \DBTech\eCommerce\Entity\Order
	{
		return $this->order;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Ship
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}
	
	/**
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function ship(): bool
	{
		$order = $this->order;
		
		$db = $this->db();
		$db->beginTransaction();
		
		$order->order_state = 'shipped';
		if (!$order->save(true, false))
		{
			$db->rollback();
			return false;
		}
		
		$db->commit();
		
		if ($this->alert)
		{
			/** @var \DBTech\eCommerce\Service\Order\Notifier $notifier */
			$notifier = $this->app->service('DBTech\eCommerce:Order\Notifier', $order);
			$notifier->notify('shipped', [
				'reason' => $this->alertReason
			]);
			
			$params = [
				'order' => $order,
				'note' => $this->alertReason
			];
			
			if ($order->user_id || $order->ShippingAddress->email)
			{
				$mail = $this->app
					->mailer()
					->newMail()
					->setTemplate('dbtech_ecommerce_order_shipped', $params)
				;
				
				if ($order->user_id)
				{
					$mail->setToUser($order->User);
				}
				else
				{
					$mail->setTo($order->ShippingAddress->email, $order->ShippingAddress->business_title);
				}
				
				$mail->send();
			}
		}
		
		return true;
	}
}