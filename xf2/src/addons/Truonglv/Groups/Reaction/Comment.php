<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Reaction;

use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Reaction\AbstractHandler;

class Comment extends AbstractHandler
{
    /**
     * @param Entity $entity
     * @return bool
     */
    public function reactionsCounted(Entity $entity)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Comment)) {
            return false;
        }

        return $entity->isVisible();
    }

    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['full'];
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_reaction_item_comment';
    }

    /**
     * @param mixed $id
     * @return \XF\Mvc\Entity\ArrayCollection|Entity|null
     */
    public function getContent($id)
    {
        $entities = parent::getContent($id);

        if ($entities !== null) {
            App::commentRepo()->addContentIntoComments($entities);
        }

        return $entities;
    }
}
