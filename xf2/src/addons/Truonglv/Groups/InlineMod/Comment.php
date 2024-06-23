<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\InlineMod;

use XF;
use XF\Mvc\Entity\Entity;
use XF\InlineMod\AbstractHandler;

class Comment extends AbstractHandler
{
    /**
     * @return \XF\InlineMod\AbstractAction[]
     */
    public function getPossibleActions()
    {
        $actions = [];

        $actions['delete'] = $this->getSimpleActionHandler(
            XF::phrase('tlg_delete_comments'),
            'canDelete',
            function (Entity $entity) {
                $entity->delete();
            }
        );

        return $actions;
    }

    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['Event', 'Event.Group', 'Event.Group'];
    }
}
