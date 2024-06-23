<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Admin\Controller;

use Truonglv\Groups\App;

class Forum extends \XF\Admin\Controller\Forum
{
    /**
     * @return string
     */
    protected function getNodeTypeId()
    {
        return App::NODE_TYPE_ID;
    }
}
