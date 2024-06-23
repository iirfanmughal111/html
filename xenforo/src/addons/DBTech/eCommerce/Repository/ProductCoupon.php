<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ProductCoupon
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductCoupon extends Repository
{
	/**
	 * @param int $couponId
	 * @param array $couponInfo
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateContentAssociations(int $couponId, array $couponInfo)
	{
		$db = $this->db();
		$db->beginTransaction();

		$db->delete('xf_dbtech_ecommerce_product_coupon_value', 'coupon_id = ?', $couponId);

		$map = [];

		foreach ($couponInfo AS $info)
		{
			$map[] = [
				'coupon_id' => $couponId,
				'product_id' => $info['product_id'],
				'product_value' => $info['product_value']
			];
		}

		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_product_coupon_value', $map, false, false, 'IGNORE');
		}

		$this->rebuildContentAssociationCache([$couponId]);

		$db->commit();
	}
	
	/**
	 * @param array $couponIds
	 */
	public function rebuildContentAssociationCache(array $couponIds)
	{
		if (!$couponIds)
		{
			return;
		}

		$newCache = [];

		$couponAssociations = $this->finder('DBTech\eCommerce:ProductCouponValue')
			->where('coupon_id', $couponIds);
		
		/** @var \DBTech\eCommerce\Entity\ProductCouponValue $couponValue */
		foreach ($couponAssociations->fetch() AS $couponValue)
		{
			$newCache[$couponValue->coupon_id][] = $couponValue->toArray();
		}

		foreach ($couponIds AS $couponId)
		{
			if (!isset($newCache[$couponId]))
			{
				$newCache[$couponId] = [];
			}
		}

		$this->updateAssociationCache($newCache);
	}

	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$couponIds = array_keys($cache);
		$coupons = $this->em->findByIds('DBTech\eCommerce:Coupon', $couponIds);

		foreach ($coupons AS $coupon)
		{
			/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
			$coupon->product_discounts = $cache[$coupon->coupon_id];
			$coupon->saveIfChanged();
		}
	}
}