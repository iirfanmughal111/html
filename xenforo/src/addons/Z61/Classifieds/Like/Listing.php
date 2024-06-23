<?php

namespace Z61\Classifieds\Like;

use XF\Like\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Listing extends AbstractHandler
{
    /**
     * @param \Z61\Classifieds\Entity\Listing $entity
     * @return bool
     */
    public function likesCounted(Entity $entity)
    {
        if (!$entity || !$entity->Category)
        {
            return false;
        }

        return ($entity->listing_state == 'visible');
    }

    public function getContentUserId(Entity $entity)
    {
        return $entity->user_id;
    }

    public function getEntityWith()
    {
        $visitor = \XF::visitor();

        return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id];
    }
}