<?php

namespace Truonglv\Groups\Data;

use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Group;
use XF\Mvc\Entity\AbstractCollection;

class Badge
{
    /**
     * @var null|AbstractCollection
     */
    protected $groups = null;

    public function setBadgeGroupIds(array $groupIds): void
    {
        if (count($groupIds) === 0) {
            $this->groups = null;

            return;
        }

        $this->groups = App::groupFinder()->whereIds($groupIds)->with('full')->fetch()->filterViewable();
    }

    /**
     * @param mixed $id
     * @return Group|null
     */
    public function getGroup($id): ?Group
    {
        if ($this->groups === null) {
            return null;
        }

        /** @var Group|null $group */
        $group = $this->groups[$id];

        return $group;
    }
}
