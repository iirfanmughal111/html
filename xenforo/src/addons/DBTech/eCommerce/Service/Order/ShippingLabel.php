<?php

namespace DBTech\eCommerce\Service\Order;

use DBTech\eCommerce\Entity\Order;
use DBTech\eCommerce\Pdf;
use XF\Util\File;

/**
 * Class ShippingLabel
 *
 * @package DBTech\eCommerce\Service\Order
 */
class ShippingLabel extends \XF\Service\AbstractService
{
	/** @var \XF\Entity\User */
	protected $user;
	
	/** @var \DBTech\eCommerce\Entity\Order */
	protected $order;
	
	
	/**
	 * Complete constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Order $order
	 * @param \XF\Entity\User $user
	 */
	public function __construct(\XF\App $app, Order $order, \XF\Entity\User $user)
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
	protected function setOrder(Order $order): ShippingLabel
	{
		$this->order = $order;

		return $this;
	}
	
	/**
	 * @return \DBTech\eCommerce\Entity\Order
	 */
	public function getOrder(): Order
	{
		return $this->order;
	}
	
	/**
	 * @param bool $force
	 *
	 * @throws \Exception
	 */
	public function generate(bool $force = false)
	{
		if ($force === true)
		{
			$this->delete();
		}

		if (\XF::fs()->has($this->getShippingLabelAbstractPath()))
		{
			return;
		}
		
		$order = $this->order;
		
		if (!$order->hasPhysicalProduct())
		{
			return;
		}
		
		// Needed to generate the shipping label properly
		$dateTime = \XF::language()
			->getDateTimeParts($this->order->order_date)
		;
		
		$pdf = new Pdf\ShippingLabel('landscape');
		
		// General configuration
		$pdf->setOrder($order);
		$pdf->setLanguageId($this->user->language_id);
		$pdf->setInvoiceId('INV' . $this->order->order_id);
		$pdf->setDate($dateTime[0]);
		$pdf->setTime($dateTime[1]);
		
		// Add the shipping label body
		$pdf->Body();
		
		// Output the shipping label to the specified file
		$pdf->writePdf('ORD' . $this->order->order_id . '.pdf');
	}
	
	/**
	 *
	 */
	public function delete()
	{
		File::deleteFromAbstractedPath($this->getShippingLabelAbstractPath());
	}
	
	/**
	 * @return string
	 */
	public function getShippingLabelFileName(): string
	{
		return sprintf(
			'ORD%d.pdf',
			$this->order->order_id
		);
	}

	/**
	 * @return string
	 */
	public function getShippingLabelAbstractPath(): string
	{
		return sprintf(
			'internal-data://dbtechEcommerce/shippingLabels/ORD%d.pdf',
			$this->order->order_id
		);
	}
}