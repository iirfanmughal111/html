<?php

namespace DBTech\eCommerce\Service\Product;

/**
 * Class DeleteCleanUp
 *
 * @package DBTech\eCommerce\Service\Product
 */
class DeleteCleanUp extends \XF\Service\AbstractService
{
	use \XF\MultiPartRunnerTrait;
	
	/** @var int */
	protected $productId;

	/** @var */
	protected $title;
	
	/** @var array */
	protected $steps = [
		'stepDeleteOrderItems',
		'stepDeletePurchaseLog',
		'stepDeleteDownloadLog',
		'stepDeleteChildProducts',
		'stepDeleteLicenses',
		'stepDeleteDownloads',
	];
	
	
	/**
	 * DeleteCleanUp constructor.
	 *
	 * @param \XF\App $app
	 * @param int $productId
	 * @param string $title
	 */
	public function __construct(\XF\App $app, int $productId, string $title)
	{
		parent::__construct($app);

		$this->productId = $productId;
		$this->title = $title;
	}
	
	/**
	 * @return array
	 */
	protected function getSteps(): array
	{
		return $this->steps;
	}
	
	/**
	 * @param int $maxRunTime
	 *
	 * @return \XF\ContinuationResult
	 */
	public function cleanUp(int $maxRunTime = 0): \XF\ContinuationResult
	{
		$this->db()->beginTransaction();
		$result = $this->runLoop($maxRunTime);
		$this->db()->commit();

		return $result;
	}

	/**
	 * @param int|null $lastOffset
	 * @param int $maxRunTime
	 *
	 * @return int|null
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function stepDeleteOrderItems(?int $lastOffset, int $maxRunTime): ?int
	{
		$start = microtime(true);
		
		/** @var \DBTech\eCommerce\Entity\OrderItem[] $orderItems */
		$finder = $this->finder('DBTech\eCommerce:OrderItem')
			->where('product_id', $this->productId)
			->order('order_item_id');
		
		if ($lastOffset !== null)
		{
			$finder->where('order_item_id', '>', $lastOffset);
		}
		
		$maxFetch = 1000;
		$orderItems = $finder->fetch($maxFetch);
		$fetchedOrderItems = count($orderItems);
		
		if (!$fetchedOrderItems)
		{
			return null; // done or nothing to do
		}
		
		foreach ($orderItems AS $orderItem)
		{
			$lastOffset = $orderItem->order_item_id;
			
			$orderItem->delete();
			
			if ($maxRunTime && microtime(true) - $start > $maxRunTime)
			{
				return $lastOffset; // continue at this position
			}
		}
		
		if ($fetchedOrderItems == $maxFetch)
		{
			return $lastOffset; // more to do
		}
		
		return null;
	}
	
	/**
	 * @param int|null $lastOffset
	 * @param int $maxRunTime
	 *
	 * @return int|null
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function stepDeletePurchaseLog(?int $lastOffset, int $maxRunTime): ?int
	{
		$start = microtime(true);
		
		/** @var \DBTech\eCommerce\Entity\PurchaseLog[] $purchaseLogs */
		$finder = $this->finder('DBTech\eCommerce:PurchaseLog')
			->where('product_id', $this->productId)
			->order('purchase_log_id');
		
		if ($lastOffset !== null)
		{
			$finder->where('purchase_log_id', '>', $lastOffset);
		}
		
		$maxFetch = 1000;
		$purchaseLogs = $finder->fetch($maxFetch);
		$fetchedPurchaseLogs = count($purchaseLogs);
		
		if (!$fetchedPurchaseLogs)
		{
			return null; // done or nothing to do
		}
		
		foreach ($purchaseLogs AS $purchaseLog)
		{
			$lastOffset = $purchaseLog->purchase_log_id;
			
			$purchaseLog->delete();
			
			if ($maxRunTime && microtime(true) - $start > $maxRunTime)
			{
				return $lastOffset; // continue at this position
			}
		}
		
		if ($fetchedPurchaseLogs == $maxFetch)
		{
			return $lastOffset; // more to do
		}
		
		return null;
	}
	
	/**
	 * @param int|null $lastOffset
	 * @param int $maxRunTime
	 *
	 * @return int|null
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function stepDeleteDownloadLog(?int $lastOffset, int $maxRunTime): ?int
	{
		$start = microtime(true);
		
		/** @var \DBTech\eCommerce\Entity\DownloadLog[] $downloadLogs */
		$finder = $this->finder('DBTech\eCommerce:DownloadLog')
			->where('product_id', $this->productId)
			->order('download_log_id');
		
		if ($lastOffset !== null)
		{
			$finder->where('download_log_id', '>', $lastOffset);
		}
		
		$maxFetch = 1000;
		$downloadLogs = $finder->fetch($maxFetch);
		$fetchedDownloadLogs = count($downloadLogs);
		
		if (!$fetchedDownloadLogs)
		{
			return null; // done or nothing to do
		}
		
		foreach ($downloadLogs AS $downloadLog)
		{
			$lastOffset = $downloadLog->download_log_id;
			
			$downloadLog->delete();
			
			if ($maxRunTime && microtime(true) - $start > $maxRunTime)
			{
				return $lastOffset; // continue at this position
			}
		}
		
		if ($fetchedDownloadLogs == $maxFetch)
		{
			return $lastOffset; // more to do
		}
		
		return null;
	}
	
	/**
	 * @param int|null $lastOffset
	 * @param int $maxRunTime
	 *
	 * @return int|null
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function stepDeleteChildProducts(?int $lastOffset, int $maxRunTime): ?int
	{
		$start = microtime(true);

		/** @var \DBTech\eCommerce\Entity\Product[] $products */
		$finder = $this->finder('DBTech\eCommerce:Product')
			->where('parent_product_id', $this->productId)
			->order('product_id');

		if ($lastOffset !== null)
		{
			$finder->where('product_id', '>', $lastOffset);
		}

		$maxFetch = 1000;
		$products = $finder->fetch($maxFetch);
		$fetchedProducts = count($products);

		if (!$fetchedProducts)
		{
			return null; // done or nothing to do
		}

		foreach ($products AS $product)
		{
			$lastOffset = $product->product_id;

			$product->setOption('log_moderator', false);
			$product->delete();

			if ($maxRunTime && microtime(true) - $start > $maxRunTime)
			{
				return $lastOffset; // continue at this position
			}
		}

		if ($fetchedProducts == $maxFetch)
		{
			return $lastOffset; // more to do
		}
		
		return null;
	}
	
	/**
	 * @param int|null $lastOffset
	 * @param int $maxRunTime
	 *
	 * @return int|null
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function stepDeleteLicenses(?int $lastOffset, int $maxRunTime): ?int
	{
		$start = microtime(true);
		
		/** @var \DBTech\eCommerce\Entity\License[] $licenses */
		$finder = $this->finder('DBTech\eCommerce:License')
			->where('product_id', $this->productId)
			->order('license_id');
		
		if ($lastOffset !== null)
		{
			$finder->where('license_id', '>', $lastOffset);
		}
		
		$maxFetch = 1000;
		$licenses = $finder->fetch($maxFetch);
		$fetchedLicenses = count($licenses);
		
		if (!$fetchedLicenses)
		{
			return null; // done or nothing to do
		}
		
		foreach ($licenses AS $license)
		{
			$lastOffset = $license->license_id;
			
			$license->setOption('log_moderator', false);
			$license->delete();
			
			if ($maxRunTime && microtime(true) - $start > $maxRunTime)
			{
				return $lastOffset; // continue at this position
			}
		}
		
		if ($fetchedLicenses == $maxFetch)
		{
			return $lastOffset; // more to do
		}
		
		return null;
	}
	
	/**
	 * @param int|null $lastOffset
	 * @param int $maxRunTime
	 *
	 * @return int|null
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 */
	protected function stepDeleteDownloads(?int $lastOffset, int $maxRunTime): ?int
	{
		$start = microtime(true);
		
		/** @var \DBTech\eCommerce\Entity\Download[] $downloads */
		$finder = $this->finder('DBTech\eCommerce:Download')
			->where('product_id', $this->productId)
			->order('download_id');
		
		if ($lastOffset !== null)
		{
			$finder->where('download_id', '>', $lastOffset);
		}
		
		$maxFetch = 1000;
		$downloads = $finder->fetch($maxFetch);
		$fetchedDownloads = count($downloads);
		
		if (!$fetchedDownloads)
		{
			return null; // done or nothing to do
		}
		
		foreach ($downloads AS $download)
		{
			$lastOffset = $download->download_id;
			
			$download->delete();
			
			if ($maxRunTime && microtime(true) - $start > $maxRunTime)
			{
				return $lastOffset; // continue at this position
			}
		}
		
		if ($fetchedDownloads == $maxFetch)
		{
			return $lastOffset; // more to do
		}
		
		return null;
	}
}