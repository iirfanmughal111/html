<?php

namespace Z61\Classifieds\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Package extends Repository
{
    /**
     * @return Finder
     */
    public function findPackagesForList()
    {
        return $this->finder('Z61\Classifieds:Package')
            ->setDefaultOrder('display_order');
    }
}