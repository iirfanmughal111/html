<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Alert;

use XF\Alert\AbstractHandler;

class Member extends AbstractHandler
{
    /**
     * @param mixed $action
     * @return string
     */
    public function getTemplateName($action)
    {
        return 'public:tlg_alert_item_member_' . $action;
    }

    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['Group'];
    }
}
