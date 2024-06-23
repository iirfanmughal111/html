<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;
use XF\Import\Data\HasDeletionLogTrait;

/**
 * Class Coupon
 *
 * @package DBTech\eCommerce\Import\Data
 */
class Coupon extends AbstractEmulatedData
{
	use HasDeletionLogTrait;
	
	/**
	 * @var null
	 */
	protected $title = null;
	
	/**
	 * @var array
	 */
	protected $products = [];
	
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'coupon';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:Coupon';
	}
	
	/**
	 * @param $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * @param int $productId
	 * @param string $value
	 */
	public function addProduct($productId, $value = '')
	{
		$this->products[$productId] = [
			'product_id' => $productId,
			'product_value' => $value,
		];
	}
	
	/**
	 * @param $oldId
	 *
	 * @return null|void
	 */
	protected function preSave($oldId)
	{
		if ($this->title === null)
		{
			throw new \LogicException("Must call setTitle with a non-null value to save a coupon");
		}
	}
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		$this->insertStateRecord($this->coupon_state, $this->start_date);
		
		$this->insertMasterPhrase('dbtech_ecommerce_coupon_title.' . $newId, $this->title ?: '');
		
		if ($this->products)
		{
			/** @var \DBTech\eCommerce\Repository\ProductCoupon $repo */
			$repo = $this->repository('DBTech\eCommerce:ProductCoupon');
			$repo->updateContentAssociations($newId, $this->products);
		}
	}
}