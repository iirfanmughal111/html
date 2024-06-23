<?php

namespace FS\UpdateUrl\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{
    protected function setupThreadCreate(\XF\Entity\Forum $forum)
    {
        $parent = parent::setupThreadCreate($forum);
        $parent->setUrl_string($this->filter('title', 'str'));
        return $parent;
    }
}