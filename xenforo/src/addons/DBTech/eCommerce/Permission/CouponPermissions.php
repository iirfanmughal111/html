<?php

namespace DBTech\eCommerce\Permission;

use XF\Permission\FlatContentPermissions;

/**
 * Class CouponPermissions
 *
 * @package DBTech\eCommerce\Permission
 */
class CouponPermissions extends FlatContentPermissions
{
	/**
	 * @return string
	 */
	protected function getContentType(): string
	{
		return 'dbtech_ecommerce_coupon';
	}
	
	/**
	 * @return \XF\Phrase
	 */
	public function getAnalysisTypeTitle(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_coupon_permissions');
	}
	
	/**
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	public function getContentList(): \XF\Mvc\Entity\AbstractCollection
	{
		/** @var \DBTech\eCommerce\Repository\Coupon $entryRepo */
		$entryRepo = $this->builder->em()->getRepository('DBTech\eCommerce:Coupon');
		return $entryRepo->findEntriesForPermissionList()->fetch();
	}
	
	/**
	 * @param \XF\Entity\Permission $permission
	 *
	 * @return bool
	 */
	public function isValidPermission(\XF\Entity\Permission $permission): bool
	{
		return ($permission->permission_group_id == 'dbtechEcommerce' && $permission->permission_id == 'useCoupons');
	}
	
	/**
	 * @param $contentId
	 * @param array $calculated
	 * @param array $childPerms
	 *
	 * @return array
	 */
	protected function getFinalPerms($contentId, array $calculated, array &$childPerms): array
	{
		if (!isset($calculated['dbtechEcommerce']))
		{
			$calculated['dbtechEcommerce'] = [];
		}
		
		$final = $this->builder->finalizePermissionValues($calculated['dbtechEcommerce']);
		
		if (empty($final['useCoupons']))
		{
			$childPerms['dbtechEcommerce']['useCoupons'] = 'deny';
		}
		
		return $final;
	}
	
	/**
	 * @param $contentId
	 * @param array $calculated
	 * @param array $childPerms
	 *
	 * @return array
	 */
	protected function getFinalAnalysisPerms($contentId, array $calculated, array &$childPerms): array
	{
		$final = $this->builder->finalizePermissionValues($calculated);
		
		if (empty($final['dbtechEcommerce']['useCoupons']))
		{
			$childPerms['dbtechEcommerce']['useCoupons'] = 'deny';
		}
		
		return $final;
	}
}