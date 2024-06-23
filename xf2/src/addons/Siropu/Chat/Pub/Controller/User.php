<?php

namespace Siropu\Chat\Pub\Controller;

use XF\Mvc\ParameterBag;

class User extends AbstractController
{
     public function actionResetData(ParameterBag $params)
     {
          $visitor = \XF::visitor();

          if (!$visitor->canResetSiropuChatUserData())
          {
               return $this->noPermission();
          }

          $user = $this->assertUserExists($params->user_id);

          if ($this->isPost())
          {
               $data       = $this->filter('data', 'array-uint');
               $jsonParams = [];

               if (!empty($data['settings']))
               {
                    $user->siropu_chat_settings = [];
               }

               if (!empty($data['status']))
               {
                    $user->siropu_chat_status = '';
               }

               if (!empty($data['message_count']))
               {
                    $user->siropu_chat_message_count = 0;
               }

               if (!empty($data['rooms']))
               {
                    foreach ($user->siropuChatGetRoomIds() as $roomId)
                    {
                         $user->siropuChatLeaveRoom($roomId);
                    }

                    $user->siropu_chat_last_activity = -1;
               }

               if (!empty($data['conversations']))
               {
                    $user->siropu_chat_conversations = [];
                    $user->siropu_chat_conv_id = 0;

                    foreach ($user->siropuChatGetConversations() as $convId => $userId)
                    {
                         $conversation = $this->em()->find('Siropu\Chat:Conversation', $convId);
                         $this->service('Siropu\Chat:Conversation\Manager', null, $conversation, null)->leaveConversation(false);
                    }

                    $jsonParams['noConversations'] = \XF::app()->templater()->renderTemplate('public:siropu_chat_conversation_list_empty');
               }

               $user->saveIfChanged($saved);

               if ($saved)
               {
                    if ($user->user_id == $visitor->user_id)
                    {
                         $jsonParams['isSelf'] = true;
                    }

                    $reply = $this->message(\XF::phrase('siropu_chat_user_data_has_been_reset'));
                    $reply->setJsonParams($jsonParams);

                    return $reply;
               }
          }

          $viewParams = [
               'user' => $user
          ];

          return $this->view('Siropu\Chat:User\ResetData', 'siropu_chat_reset_user_data', $viewParams);
     }
     public function actionMenuOptions(ParameterBag $params)
     {
          $user = $this->assertUserExists($params->user_id);
          $room = $this->assertRoomExists($this->filter('room_id', 'uint'));

          $viewParams = [
               'user' => $user,
               'room' => $room
          ];

          return $this->view('Siropu\Chat:User\RoomMenuOptions', 'siropu_chat_room_user_menu_options', $viewParams);
     }
}
