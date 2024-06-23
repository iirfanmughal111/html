<?php

namespace DBTech\eCommerce\Import\Importer;

use XF\Import\Importer\AbstractImporter;

/**
 * Class AbstractCoreImporter
 *
 * @package DBTech\eCommerce\Import\Importer
 */
abstract class AbstractCoreImporter extends AbstractImporter
{
	/**
	 * @return bool
	 */
	public function canRetainIds(): bool
	{
		$db = $this->app->db();
		
		$maxAddressId = $db->fetchOne("SELECT MAX(address_id) FROM xf_dbtech_ecommerce_address");
		if ($maxAddressId)
		{
			return false;
		}
		
		$maxCategoryId = $db->fetchOne("SELECT MAX(category_id) FROM xf_dbtech_ecommerce_category");
		if ($maxCategoryId > 1)
		{
			return false;
		}
		
		$maxCommissionId = $db->fetchOne("SELECT MAX(commission_id) FROM xf_dbtech_ecommerce_commission");
		if ($maxCommissionId)
		{
			return false;
		}
		
		$maxCouponnId = $db->fetchOne("SELECT MAX(coupon_id) FROM xf_dbtech_ecommerce_coupon");
		if ($maxCouponnId)
		{
			return false;
		}
		
		$maxDiscountId = $db->fetchOne("SELECT MAX(discount_id) FROM xf_dbtech_ecommerce_discount");
		if ($maxDiscountId)
		{
			return false;
		}
		
		$maxDistributorId = $db->fetchOne("SELECT MAX(user_id) FROM xf_dbtech_ecommerce_distributor");
		if ($maxDistributorId)
		{
			return false;
		}
		
		$maxDownloadId = $db->fetchOne("SELECT MAX(download_id) FROM xf_dbtech_ecommerce_download");
		if ($maxDownloadId)
		{
			return false;
		}
		
		$maxLicenseId = $db->fetchOne("SELECT MAX(license_id) FROM xf_dbtech_ecommerce_license");
		if ($maxLicenseId)
		{
			return false;
		}
		
		$maxOrderId = $db->fetchOne("SELECT MAX(order_id) FROM xf_dbtech_ecommerce_order");
		if ($maxOrderId)
		{
			return false;
		}
		
		$maxProductId = $db->fetchOne("SELECT MAX(product_id) FROM xf_dbtech_ecommerce_product");
		if ($maxProductId)
		{
			return false;
		}
		
		$maxSaleId = $db->fetchOne("SELECT MAX(sale_id) FROM xf_dbtech_ecommerce_sale");
		if ($maxSaleId)
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function resetDataForRetainIds()
	{
		// category 1 created by default in the installer so we need to remove this if retaining IDs
		$category = $this->em()->find('DBTech\eCommerce:Category', 1);
		if ($category)
		{
			$category->delete();
		}
	}
	
	/**
	 * @param array $stepsRun
	 *
	 * @return array
	 */
	public function getFinalizeJobs(array $stepsRun): array
	{
		return [
			'XF:User',
			'XF:PermissionRebuild',
			'DBTech\eCommerce:Category',
			'DBTech\eCommerce:Product',
			'DBTech\eCommerce:Download',
			'DBTech\eCommerce:License',
			'DBTech\eCommerce:UserProductCount',
			'DBTech\eCommerce:UserLicenseCount',
			'DBTech\eCommerce:AmountSpent',
			'DBTech\eCommerce:Commission'
		];
	}
}