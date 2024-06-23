<?php

namespace Siropu\Chat\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Login extends XFCP_Login
{
     public function actionLogin()
	{
          $login = parent::actionLogin();

          if ($this->isLoggedIn() && \XF::options()->siropuChatLogoutUserOnSiteLogout && \XF::visitor()->hasJoinedRoomsSiropuChat())
          {
               $visitor = \XF::visitor();
               $visitor->siropu_chat_rooms = [];
               $visitor->siropu_chat_last_activity = -1;
               $visitor->siropu_chat_room_id = 0;
               $visitor->save(false);
          }

          return $login;
     }
}
