<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class Reassign
 *
 * @package DBTech\eCommerce\Service\Product
 */
class Reassign extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;
	
	/** @var bool */
	protected $alert = false;
	
	/** @var string */
	protected $alertReason = '';
	
	
	/**
	 * Reassign constructor.
	 *
	 * @param \XF\App $app
	 * @param Product $product
	 */
	public function __construct(\XF\App $app, Product $product)
	{
		parent::__construct($app);
		$this->product = $product;
	}
	
	/**
	 * @return Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Reassign
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}
	
	/**
	 * @param \XF\Entity\User $newUser
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function reassignTo(\XF\Entity\User $newUser): bool
	{
		$product = $this->product;
		$oldUser = $product->User;
		$reassigned = ($product->user_id != $newUser->user_id);
		
		$product->user_id = $newUser->user_id;
		$product->username = $newUser->username;
		$product->save();
		
		if ($reassigned && $product->isVisible() && $this->alert)
		{
			if (\XF::visitor()->user_id != $oldUser->user_id)
			{
				/** @var \DBTech\eCommerce\Repository\Product $productRepo */
				$productRepo = $this->repository('DBTech\eCommerce:Product');
				$productRepo->sendModeratorActionAlert(
					$product,
					'reassign_from',
					$this->alertReason,
					['to' => $newUser->username],
					$oldUser
				);
			}
			
			if (\XF::visitor()->user_id != $newUser->user_id)
			{
				/** @var \DBTech\eCommerce\Repository\Product $productRepo */
				$productRepo = $this->repository('DBTech\eCommerce:Product');
				$productRepo->sendModeratorActionAlert(
					$product,
					'reassign_to',
					$this->alertReason,
					[],
					$newUser
				);
			}
		}
		
		return $reassigned;
	}
}