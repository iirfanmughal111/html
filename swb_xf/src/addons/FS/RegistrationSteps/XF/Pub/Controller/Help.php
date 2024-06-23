<?php

namespace FS\RegistrationSteps\XF\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\View;

use function call_user_func_array;

class Help extends XFCP_Help
{
    public function actionIndex(ParameterBag $params)
	{
        $pageName = $params->get('page_name', '');

        if ($pageName == 'compliance'){
            return $this->view('FS\RegistrationSteps', 'fs_register_compliance');
        }
		return parent::actionIndex($params);
    }
   
}