<?php

namespace Siropu\Chat\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Logout extends XFCP_Logout
{
     public function actionIndex()
	{
          $options = \XF::options();
          $visitor = \XF::visitor();

          if ($options->siropuChatLogoutUserOnSiteLogout && !$visitor->isInShoutboxModeSiropuChat())
          {
               try
               {
                    $visitor->siropuChatLogout();
                    $visitor->save(false);
               }
               catch (\Exception $e) {}
          }

          return parent::actionIndex();
     }
}
