<?php

namespace Siropu\Chat\Command;

class Nick
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if ($controller->isLoggedIn())
          {
               return $controller->message(\XF::phrase('siropu_chat_no_permission_to_use_command'));
          }

          $validator = \XF::app()->validator('Username');
          $nickname = $validator->coerceValue($controller->stripBbCode($input));
          $validator->setOption('regex_match', '/^[\w ]+$/u');

          if (!$validator->isValid($nickname, $error))
          {
               return $controller->message($validator->getPrintableErrorValue($error));
          }

          $guestService = \XF::service('Siropu\Chat:Guest\Manager');

          if (!$guestService->checkNicknameAvailability($nickname, $error))
          {
               return $controller->message($error);
          }

          if ($guestService->getNickname())
          {
               $guestService->removeGuest();
          }

          \XF::app()->session()->set('siropuChatGuestNickname', $nickname);

          $guestEntity = $guestService->saveGuest($nickname, true);

          $notifier = \XF::app()->service('Siropu\Chat:Room\Notifier', $guestEntity, $controller->roomId);
          $notifier->notify('join');

          $reply = $controller->message(\XF::phrase('siropu_chat_your_nickname_has_been_set_to_x',
               ['nickname' => $nickname]));
          $reply->setJsonParams(['nickname' => $nickname]);

          return $reply;
     }
}
