<?php

namespace XenAddons\Showcase\Permission;

use XF\Mvc\Entity\Entity;
use XF\Permission\TreeContentPermissions;

class CategoryPermissions extends TreeContentPermissions
{
	public function getContentType()
	{
		return 'sc_category';
	}

	public function getAnalysisTypeTitle()
	{
		return \XF::phrase('xa_sc_item_category_permissions');
	}

	public function getContentTitle(Entity $entity)
	{
		return $entity->title;
	}

	public function isValidPermission(\XF\Entity\Permission $permission)
	{
		return ($permission->permission_group_id == 'xa_showcase');  
	}

	public function getContentTree()
	{
		/** @var \XenAddons\Showcase\Repository\Category $categoryRepo */
		$categoryRepo = $this->builder->em()->getRepository('XenAddons\Showcase:Category');
		return $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
	}

	protected function getFinalPerms($contentId, array $calculated, array &$childPerms)
	{
		if (!isset($calculated['xa_showcase']))
		{
			$calculated['xa_showcase'] = [];
		}

		$final = $this->builder->finalizePermissionValues($calculated['xa_showcase']);

		if (empty($final['view']))
		{
			$childPerms['xa_showcase']['view'] = 'deny';
		}

		return $final;
	}

	protected function getFinalAnalysisPerms($contentId, array $calculated, array &$childPerms)
	{
		$final = $this->builder->finalizePermissionValues($calculated);

		if (empty($final['xa_showcase']['view']))
		{
			$childPerms['xa_showcase']['view'] = 'deny';
		}

		return $final;
	}
}