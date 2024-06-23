<?php

namespace Truonglv\Groups\XF\Pub\Controller;

use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;

class InlineMod extends XFCP_InlineMod
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @return void
     */
    protected function preDispatchType($action, ParameterBag $params)
    {
        parent::preDispatchType($action, $params);

        $type = $this->filter('type', 'str');
        if ($type !== '' && strpos($type, App::CONTENT_TYPE_MEMBER) === 0) {
            $this->request()->set('type', App::CONTENT_TYPE_MEMBER);
        }
    }
}
