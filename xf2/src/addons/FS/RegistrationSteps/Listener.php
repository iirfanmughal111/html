<?php


namespace FS\RegistrationSteps;

use XF\Mvc\Reply\View;
use XF\Pub\Controller\AbstractController;
use XF\Pub\Controller\Login;
use XF\Pub\Controller\Register;
use XF\Pub\Controller\Account;


class Listener
{


    public static function controllerPostDispatch(\XF\Mvc\Controller $controller, $action, \XF\Mvc\ParameterBag $params, \XF\Mvc\Reply\AbstractReply &$reply)
    {
        
     
                
        if ($controller instanceof AbstractController)
        {
            if (!$reply instanceof View)
            {
                return;
            }

            if ($controller instanceof Login || $controller instanceof Register || $controller instanceof Account)
            {
                return;
            }
            
            
              $url=\xf::app()->request()->getFullRequestUri();

        $user = \xf::visitor();

     
        if ($user->user_id && !$user->is_verify && !strpos($url, "admin.php")) {


                $reply->setTemplateName('fs_verify_account_compulsory');
        }

            return;
        }
    }

    



}