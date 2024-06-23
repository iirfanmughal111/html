<?php

namespace DBTech\eCommerce\Permission;

use XF\Permission\FlatContentPermissions;
use XF\Permission\AnalysisIntermediate;

/**
 * Class ProductPermissions
 *
 * @package DBTech\eCommerce\Permission
 */
class ProductPermissions extends FlatContentPermissions
{
	/**
	 * @return string
	 */
	protected function getContentType(): string
	{
		return 'dbtech_ecommerce_product';
	}

	/**
	 * @return \XF\Phrase
	 */
	public function getAnalysisTypeTitle(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_product_permissions');
	}
	
	/**
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	public function getContentList(): \XF\Mvc\Entity\AbstractCollection
	{
		/** @var \DBTech\eCommerce\Repository\Product $entryRepo */
		$entryRepo = $this->builder->em()->getRepository('DBTech\eCommerce:Product');
		return $entryRepo->findEntriesForPermissionList()->fetch();
	}

	/**
	 * @param \XF\Entity\Permission $permission
	 *
	 * @return bool
	 */
	public function isValidPermission(\XF\Entity\Permission $permission): bool
	{
		return ($permission->permission_group_id == 'dbtechEcommerce' && in_array($permission->permission_id, [
			'view',
			'viewProductAttach',
			'purchase',
			'download',
			'react',
			'rate',
		]));
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
		
		if (empty($final['view']))
		{
			$childPerms['dbtechEcommerce']['view'] = 'deny';
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
		
		if (empty($final['dbtechEcommerce']['view']))
		{
			$childPerms['dbtechEcommerce']['view'] = 'deny';
		}
		
		return $final;
	}
	
	/**
	 * @param $contentId
	 * @param array $userGroupIds
	 * @param int $userId
	 *
	 * @return array
	 */
	public function getApplicablePermissionSets($contentId, array $userGroupIds, $userId = 0): array
	{
		/** @var \XF\Repository\PermissionEntry $entryRepo */
		$entryRepo = $this->builder->em()->getRepository('XF:PermissionEntry');
		
		$entries = $entryRepo->getContentPermissionEntriesGrouped('dbtech_ecommerce_category');
		
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->builder->em()->find('DBTech\eCommerce:Product', $contentId);
		
		$userEntries = $entries['users'];
		$groupEntries = $entries['groups'];
		$systemEntries = $entries['system'];
		
		$sets = [];
		foreach ($userGroupIds AS $userGroupId)
		{
			if (isset($groupEntries[$product->product_category_id][$userGroupId]))
			{
				$sets["ecommerce-category-group-$userGroupId"] = $groupEntries[$product->product_category_id][$userGroupId];
			}
			if (isset($this->groupEntries[$contentId][$userGroupId]))
			{
				$sets["group-$userGroupId"] = $this->groupEntries[$contentId][$userGroupId];
			}
		}
		
		if ($userId && isset($userEntries[$product->product_category_id][$userId]))
		{
			$sets["ecommerce-category-user-$userId"] = $userEntries[$product->product_category_id][$userId];
		}
		if ($userId && isset($this->userEntries[$contentId][$userId]))
		{
			$sets["user-$userId"] = $this->userEntries[$contentId][$userId];
		}
		
		if (isset($systemEntries[$product->product_category_id]))
		{
			$sets['ecommerce-category-system'] = $systemEntries[$product->product_category_id];
		}
		if (isset($this->systemEntries[$contentId]))
		{
			$sets['system'] = $this->systemEntries[$contentId];
		}
		
		return $sets;
	}
	
	/**
	 * @param \XF\Entity\PermissionCombination $combination
	 * @param $contentId
	 * @param array $basePerms
	 * @param array $baseIntermediates
	 *
	 * @return array
	 */
	public function analyzeCombination(
		\XF\Entity\PermissionCombination $combination,
		$contentId,
		array $basePerms,
		array $baseIntermediates
	): array {
		$groupIds = $combination->user_group_list;
		$userId = $combination->user_id;
		
		$intermediates = $baseIntermediates;
		$permissions = $basePerms;
		$dependChanges = [];
		
		$titles = $this->getAnalysisContentPairs();
		
		$permissions = $this->adjustBasePermissionAllows($permissions);
		
		$sets = $this->getApplicablePermissionSets($contentId, $groupIds, $userId);
		$permissions = $this->builder->calculatePermissions($sets, $this->permissionsGrouped, $permissions);
		
		$calculated = $this->builder->applyPermissionDependencies(
			$permissions,
			$this->permissionsGrouped,
			$dependChanges
		);
		$finalPerms = $this->getFinalAnalysisPerms($contentId, $calculated, $permissions);
		
		$thisIntermediates = $this->builder->collectIntermediates(
			$combination,
			$permissions,
			$sets,
			$contentId,
			$titles[$contentId]
		);
		$thisIntermediates = array_merge($thisIntermediates, $this->collectCategoryIntermediates(
			$combination,
			$permissions,
			$sets,
			$contentId,
			$titles[$contentId]
		));
		$intermediates = $this->builder->pushIntermediates($intermediates, $thisIntermediates);
		
		return $this->builder->getFinalAnalysis($finalPerms, $intermediates, $dependChanges);
	}
	
	protected function collectCategoryIntermediates(
		\XF\Entity\PermissionCombination $combination,
		array $groupedPermissions,
		array $sets,
		$contentId = null,
		$contentTitle = null
	): array {
		$groupIds = $combination->user_group_list;
		$userId = $combination->user_id;
		
		$intermediates = [];
		
		foreach ($groupedPermissions AS $permissionGroupId => $permissions)
		{
			foreach ($permissions AS $permissionId => $null)
			{
				$localIntermediates = [];
				
				if (isset($sets['ecommerce-category-system'][$permissionGroupId][$permissionId]))
				{
					$localIntermediates[] = new AnalysisIntermediate(
						$sets['ecommerce-category-system'][$permissionGroupId][$permissionId],
						'ecommerce-category-system',
						null,
						$contentId,
						$contentTitle
					);
				}
				
				foreach ($groupIds AS $groupId)
				{
					if (isset($sets["ecommerce-category-group-$groupId"][$permissionGroupId][$permissionId]))
					{
						$intermediateValue = $sets["ecommerce-category-group-$groupId"][$permissionGroupId][$permissionId];
					}
					else
					{
						$permission = $this->permissionsGrouped[$permissionGroupId][$permissionId];
						$intermediateValue = $permission->permission_type == 'integer' ? 0 : 'unset';
					}
					
					$skipDefault = ($contentId && ($intermediateValue === 'unset' || $intermediateValue === 0));
					if (!$skipDefault)
					{
						$localIntermediates[] = new AnalysisIntermediate(
							$intermediateValue,
							'ecommerce-category-group',
							$groupId,
							$contentId,
							$contentTitle
						);
					}
				}
				
				if ($userId && isset($sets["ecommerce-category-user-$userId"][$permissionGroupId][$permissionId]))
				{
					$localIntermediates[] = new AnalysisIntermediate(
						$sets["ecommerce-category-user-$userId"][$permissionGroupId][$permissionId],
						'ecommerce-category-user',
						$userId,
						$contentId,
						$contentTitle
					);
				}
				
				$intermediates[$permissionGroupId][$permissionId] = $localIntermediates;
			}
		}
		
		return $intermediates;
	}
}