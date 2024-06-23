<?php

namespace DBTech\eCommerce\Permission;

use XF\Mvc\Entity\Entity;
use XF\Permission\TreeContentPermissions;

/**
 * Class CategoryPermissions
 *
 * @package DBTech\eCommerce\Permission
 */
class CategoryPermissions extends TreeContentPermissions
{
	/**
	 * @return string
	 */
	protected function getContentType(): string
	{
		return 'dbtech_ecommerce_category';
	}
	
	/**
	 * @return \XF\Phrase
	 */
	public function getAnalysisTypeTitle(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_category_permissions');
	}
	
	/**
	 * @param Entity $entity
	 *
	 * @return mixed|null
	 */
	public function getContentTitle(Entity $entity)
	{
		return $entity->title;
	}
	
	/**
	 * @param \XF\Entity\Permission $permission
	 *
	 * @return bool
	 */
	public function isValidPermission(\XF\Entity\Permission $permission): bool
	{
		return ($permission->permission_group_id == 'dbtechEcommerce');
	}
	
	/**
	 * @return \XF\Tree
	 */
	public function getContentTree(): \XF\Tree
	{
		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = $this->builder->em()->getRepository('DBTech\eCommerce:Category');
		return $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
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
}