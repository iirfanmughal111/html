<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class Move
 *
 * @package DBTech\eCommerce\Service\Product
 */
class Move extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;
	
	/** @var bool */
	protected $alert = false;

	/** @var string */
	protected $alertReason = '';
	
	/** @var bool */
	protected $notifyWatchers = false;
	
	/** @var null */
	protected $prefixId;
	
	/** @var array */
	protected $extraSetup = [];


	/**
	 * Move constructor.
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
	public function setSendAlert(bool $alert, ?string $reason = null): Move
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}

	/**
	 * @param int|null $prefixId
	 *
	 * @return $this
	 */
	public function setPrefix(?int $prefixId): Move
	{
		$this->prefixId = $prefixId;

		return $this;
	}

	/**
	 * @param bool $value
	 *
	 * @return $this
	 */
	public function setNotifyWatchers(bool $value = true): Move
	{
		$this->notifyWatchers = $value;

		return $this;
	}

	/**
	 * @param callable $extra
	 *
	 * @return $this
	 */
	public function addExtraSetup(callable $extra): Move
	{
		$this->extraSetup[] = $extra;

		return $this;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function move(\DBTech\eCommerce\Entity\Category $category): bool
	{
		$user = \XF::visitor();

		$product = $this->product;
		$oldCategory = $product->Category;

		$moved = ($product->product_category_id != $category->category_id);

		foreach ($this->extraSetup AS $extra)
		{
			$extra($product, $category);
		}

		$product->product_category_id = $category->category_id;
		if ($this->prefixId !== null)
		{
			$product->prefix_id = $this->prefixId;
		}

		if (!$product->preSave())
		{
			throw new \XF\PrintableException($product->getErrors());
		}

		$db = $this->db();
		$db->beginTransaction();

		$product->save(true, false);

		$db->commit();

		if ($moved && $product->isVisible() && $this->alert && $product->user_id != $user->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\Product $productRepo */
			$productRepo = $this->repository('DBTech\eCommerce:Product');
			$productRepo->sendModeratorActionAlert($this->product, 'move', $this->alertReason);
		}

		if ($moved && $this->notifyWatchers)
		{
			/** @var \DBTech\eCommerce\Service\Product\Notify $notifier */
			$notifier = $this->service('DBTech\eCommerce:Product\Notify', $product);
			if ($oldCategory)
			{
				$notifier->skipUsersWatchingCategory($oldCategory);
			}
			$notifier->notifyAndEnqueue(3);
		}
		
		// Enqueue permission rebuild
		$this->app->jobManager()->enqueueUnique('permissionRebuild', 'XF:PermissionRebuild');
		
		if ($this->app->get('app.classType') == 'Pub')
		{
			// Immediately rebuild permissions
			$this->app->jobManager()
				->runUnique('permissionRebuild', 2)
			;
		}
		
		return $moved;
	}
}