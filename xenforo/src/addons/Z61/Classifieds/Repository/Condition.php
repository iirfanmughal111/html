<?php

namespace Z61\Classifieds\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Condition extends Repository
{
    /**
     * @return Finder
     */
    public function findConditionsForList()
    {
        return $this->finder('Z61\Classifieds:Condition')
            ->setDefaultOrder('display_order');
    }
}