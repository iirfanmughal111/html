<?php

namespace DBTech\eCommerce\Service\Sale;

use DBTech\eCommerce\Entity\Sale;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\Service\Sale
 */
class Delete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Sale */
	protected $sale;

	/** @var \XF\Entity\User|null */
	protected $user;

	/**
	 * Delete constructor.
	 *
	 * @param \XF\App $app
	 * @param Sale $sale
	 */
	public function __construct(\XF\App $app, Sale $sale)
	{
		parent::__construct($app);
		$this->sale = $sale;
	}

	/**
	 * @return Sale
	 */
	public function getSale(): Sale
	{
		return $this->sale;
	}

	/**
	 * @param \XF\Entity\User|null $user
	 *
	 * @return $this
	 */
	public function setUser(?\XF\Entity\User $user = null): Delete
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * @return \XF\Entity\User|null
	 */
	public function getUser(): ?\XF\Entity\User
	{
		return $this->user;
	}
	
	/**
	 * @param string $type
	 * @param string $reason
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function delete(string $type, string $reason = ''): bool
	{
		$user = $this->user ?: \XF::visitor();

		if ($type == 'soft')
		{
			$result = $this->sale->softDelete($reason, $user);
		}
		else
		{
			$result = $this->sale->delete();
		}
		
		// need to rebuild sales cache
		$this->app->jobManager()->enqueueUnique('dbtEcomSaleRebuild', 'DBTech\eCommerce:SaleRebuild');

		return $result;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function unDelete(): bool
	{
		$user = $this->user ?: \XF::visitor();
		
		$result = $this->sale->unDelete($user);
		
		// need to rebuild sales cache
		$this->app->jobManager()->enqueueUnique('dbtEcomSaleRebuild', 'DBTech\eCommerce:SaleRebuild');
		
		return $result;
	}
}