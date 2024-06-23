<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Alert;

use Truonglv\Groups\App;
use XF\Alert\AbstractHandler;

class Comment extends AbstractHandler
{
    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['full'];
    }

    /**
     * @param mixed $action
     * @return string
     */
    public function getTemplateName($action)
    {
        return 'public:tlg_alert_item_comment_' . $action;
    }

    /**
     * @param mixed $id
     * @return \XF\Mvc\Entity\ArrayCollection|\XF\Mvc\Entity\Entity|null
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
