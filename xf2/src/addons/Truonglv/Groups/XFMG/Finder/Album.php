<?php

namespace Truonglv\Groups\XFMG\Finder;

use Truonglv\Groups\Entity\Group;

class Album extends XFCP_Album
{
    /**
     * @var Group|null
     */
    protected $tlgGroup;

    public function setTLGGroup(Group $group): void
    {
        $this->tlgGroup = $group;
    }

    /**
     * @param mixed $categoryIds
     * @param mixed $includePersonalAlbums
     * @return $this|Album
     */
    public function inCategoriesIncludePersonalAlbums($categoryIds, $includePersonalAlbums = true)
    {
        if (is_array($categoryIds) && isset($categoryIds['tlg_group_id'])) {
            // we load all albums which created in group
            $this->where('category_id', 0);

            return $this;
        }

        return parent::inCategoriesIncludePersonalAlbums($categoryIds, $includePersonalAlbums);
    }
}
