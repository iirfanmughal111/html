<?php

namespace FS\PGPEncryption;

use XF\Util\Arr;

class Listener {

    public static function appPubRenderPage(\XF\Pub\App $app, array &$params, \XF\Mvc\Reply\AbstractReply $reply, \XF\Mvc\Renderer\AbstractRenderer $renderer) {


        $user = \xf::visitor()->user_id;

        if ($user) {

            $user = \xf::visitor();

            if (!$user->public_key) {

                $user->fastUpdate('pgp_option', 0);
            }
            
            
            if (!$user->passphrase_1 || !$user->passphrase_2  || !$user->public_key) {

                $params['passphraseOverlay'] = 1;
                
            }elseif(!$user->passphrase_3){
                
                $params['passphraseOverlay_3'] = 1;
                
            }
        }
    }

    public static function preDispatchController(\XF\Mvc\Controller $controller, $action, \XF\Mvc\ParameterBag $params) {



        $url=\xf::app()->request()->getFullRequestUri();

        $user = \xf::visitor();


        if ($user->user_id && $action != 'LoginPass' && $action!='PgpVerify' && !strpos($url, "admin.php") && $action != 'LoginPassLast' && $action != 'PgpVerifyLast') {

           


            if (!$user->passphrase_1 || !$user->passphrase_2 || !$user->passphrase_3 || !$user->public_key) {

                throw new \XF\PrintableException(\XF::phrase('fs_please_set_first_passphrase'));
            }
        }
    }

}
