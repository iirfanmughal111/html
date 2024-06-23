<?php

namespace Z61\Classifieds\Permission;

use XF\Mvc\Entity\Entity;
use XF\Permission\TreeContentPermissions;

class CategoryPermissions extends TreeContentPermissions
{
    protected function getContentType()
    {
        return 'classifieds_category';
    }

    public function getAnalysisTypeTitle()
    {
        return \XF::phrase('z61_classifieds_category_permissions');
    }

    public function getContentTitle(Entity $entity)
    {
        return $entity->title;
    }

    public function isValidPermission(\XF\Entity\Permission $permission)
    {
        return ($permission->permission_group_id == 'classifieds');
    }

    public function getContentTree()
    {
        /** @var \Z61\Classifieds\Repository\Category $categoryRepo */
        $categoryRepo = $this->builder->em()->getRepository('Z61\Classifieds:Category');
        return $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
    }

    protected function getFinalPerms($contentId, array $calculated, array &$childPerms)
    {
        if (!isset($calculated['classifieds']))
        {
            $calculated['classifieds'] = [];
        }

        $final = $this->builder->finalizePermissionValues($calculated['classifieds']);

        if (empty($final['view']))
        {
            $childPerms['classifieds']['view'] = 'deny';
        }

        return $final;
    }

    protected function getFinalAnalysisPerms($contentId, array $calculated, array &$childPerms)
    {
        $final = $this->builder->finalizePermissionValues($calculated);

        if (empty($final['classifieds']['view']))
        {
            $childPerms['classifieds']['view'] = 'deny';
        }

        return $final;
    }
}