<?php

namespace Truonglv\Groups\XF\Admin\Controller;

use XF\Mvc\Reply\View;
use XF\Mvc\ParameterBag;

class Option extends XFCP_Option
{
    public function actionGroup(ParameterBag $params)
    {
        $response = parent::actionGroup($params);
        if ($response instanceof View) {
            $group = $response->getParam('group');
            if ($group->group_id === 'tl_groups') {
                $this->setSectionContext('tl_groups');
            }
        }

        return $response;
    }
}
