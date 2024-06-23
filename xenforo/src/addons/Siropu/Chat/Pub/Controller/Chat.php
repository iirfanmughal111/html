<?php

namespace Siropu\Chat\Pub\Controller;

use XF\Mvc\ParameterBag;

class Chat extends AbstractController
{
     public $channel;
     public $roomId;
     public $lastId;
     public $convId;
     public $convUnread;

     protected function preDispatchController($action, ParameterBag $params)
	{
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!method_exists($visitor, 'canViewSiropuChat'))
		{
			return;
		}

          if (!($options->siropuChatEnabled && $visitor->canViewSiropuChat()))
          {
               throw $this->exception($this->noPermission(\XF::phrase('siropu_chat_do_not_have_permission')));
          }

          if ($visitor->user_state != 'valid')
          {
               throw $this->exception($this->noPermission(\XF::phrase('siropu_chat_do_not_have_permission_to_use')));
          }

          if ($visitor->isBannedSiropuChat())
          {
               throw $this->exception($this->noPermission());
          }

          if ($action == 'Index')
          {
               $this->getUserRepo()->autoLoginJoinedRooms(true);
          }

          $this->channel    = $this->filter('channel', 'str');
          $this->roomId     = $this->filter('room_id', 'uint');
          $this->lastId     = $this->filter('last_id', 'array-uint');
          $this->convId     = $this->filter('conv_id', 'uint');
          $this->convUnread = $this->filter('conv_unread', 'array');

          if ($this->isConvChannel() && !empty($this->convUnread[$this->convId]) && $visitor->hasConversationSiropuChat($this->convId))
          {
               $this->getConversationMessageRepo()->markAsRead($this->convId, $this->convUnread[$this->convId]);
          }
	}
     public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('siropu_chat_viewing_chat_page');
	}
     public function actionFullpage()
     {
          return $this->rerouteController(__CLASS__, 'index', ['fullPage' => true]);
     }
     public function actionIndex(ParameterBag $params)
     {
          $viewParams = [
               'isFullPage' => $params->fullPage
          ];

          return $this->plugin('Siropu\Chat:Chat')->loadChat($viewParams);
     }
     public function actionSubmit()
     {
          $this->assertPostOnly();

          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!$visitor->canUseSiropuChat())
          {
               return $this->message(\XF::phrase('siropu_chat_do_not_have_permission_to_use'));
          }

          if (!in_array($this->channel, ['room', 'conversation']))
          {
               return $this->message(\XF::phrase('siropu_chat_something_went_wrong'));
          }

          $messageHtml  = $this->plugin('Siropu\Chat:Chat')->getHtmlMessage();
		$messageText  = $this->plugin('XF:Editor')->convertToBbCode($messageHtml);
          $messageClean = $this->stripBbCode($messageText);

          $messagePreparerService = $this->service('Siropu\Chat:Message\Preparer');
          $messagePreparerService->prepare($messageText);

          if ($messagePreparerService->isValid())
          {
               $messageText = $messagePreparerService->getMessage();
          }
          else
          {
               $reply = $this->message($messagePreparerService->getErrors());
               $reply->setJsonParams(['input' => $messageHtml]);

               return $reply;
          }

          $mentionedUsers = [];

          if ($this->isRoomChannel())
          {
               $room = $this->em()->find('Siropu\Chat:Room', $this->roomId);

               if (empty($room->room_id))
               {
                    return $this->noPermission(\XF::phrase('siropu_chat_invalid_room'));
               }

               $floodCheck = $room->room_flood ?: $options->siropuChatFloodCheckLength;

               if ($floodCheck
                    && !$visitor->canBypassSiropuChatFloodCheck()
                    && $visitor->siropu_chat_last_activity >= \XF::$time - $floodCheck)
               {
                    return $this->message(\XF::phrase('siropu_chat_please_wait_at_least_x_seconds_before_posting', ['x' => $floodCheck]));
               }

               if ($room->isLocked($error))
               {
                    return $this->message($error);
               }

               if ($room->isReadOnly())
               {
                    return $this->message(\XF::phrase('siropu_chat_room_is_read_only'));
               }

               $message = $this->em()->create('Siropu\Chat:Message');
               $message->message_room_id = $this->roomId;
               $message->setOption('room_child_ids', $room->room_child_ids);

               if ($visitor->isSanctionedSiropuChat())
               {
                    if ($visitor->isMutedSiropuChat())
                    {
                         $message->ignoreMe();
                    }
                    else if ($sanction = $visitor->siropuChatGetRoomSanction($this->roomId))
                    {
                         if ($sanction->sanction_type == 'mute')
                         {
                              $message->ignoreMe();
                         }
                         else
                         {
                              return $this->message($sanction->getNotice());
                         }
                    }
               }

               if ($mentions = $messagePreparerService->getUserMentions())
               {
                    $mentionedUsers = $this->finder('XF:User')
     				->where('user_id', array_keys($mentions))
     				->isValidUser()
     				->fetch()
                         ->filter(function(\XF\Entity\User $user) use ($visitor)
                         {
                              return ($user->canUseSiropuChat() && $user->user_id != $visitor->user_id);
                         });

     			foreach ($mentionedUsers as $user)
     			{
     				$message->mentionUser($user);
     			}
               }
          }
          else
          {
               $message = $this->em()->create('Siropu\Chat:ConversationMessage');
               $message->message_conversation_id = $this->convId;
          }

          $message->message_text = $messageText;

          $nickname = $this->app()->session()->get('siropuChatGuestNickname');

          if ($nickname)
          {
               $message->message_username = $nickname;
          }

          $viewParams = [];
          $isPost     = true;

          if (preg_match('/^\/([\/\pL]+)(.*?)$/is', $messageText, $matches))
          {
               $commandName = $matches[1];
               $input       = !empty($matches[2]) ? trim(str_ireplace('/' . $commandName, '', $messageText)) : '';

               if (strpos($commandName, '/') === 0)
               {
                    $commandName = substr($commandName, 1, strlen($commandName));
                    $commandData = $this->getUserCommandRepo()->findUserCommand($visitor->user_id, $commandName);

                    if ($commandData)
                    {
                         switch ($input)
                         {
                              case '':
                                   $message->message_text = $commandData->command_value;
                                   break;
                              case 'delete':
                                   $commandData->delete();
                                   return $this->message(\XF::phrase('siropu_chat_custom_command_deleted', ['command' => $commandName]));
                                   break;
                              default:
                                   $commandData->command_value = $input;
                                   $commandData->save();
                                   return $this->message(\XF::phrase('siropu_chat_custom_command_updated', ['command' => $commandName]));
                                   break;
                         }
                    }
                    else if ($input)
                    {
                         $command = $this->em()->create('Siropu\Chat:UserCommand');
                         $command->command_name = $commandName;
                         $command->command_value = $input;
                         $command->save();

                         return $this->message(\XF::phrase('siropu_chat_custom_command_created', ['command' => $commandName]));
                    }
                    else
                    {
                         return $this->message(\XF::phrase('siropu_chat_unknown_custom_command', ['command' => $commandName]));
                    }
               }
               else if ($command = $this->em()->find('Siropu\Chat:Command', $commandName))
               {
                    if (!$command->canUse($this->roomId, $error))
                    {
                         return $this->message($error);
                    }
                    else
                    {
                         $reply = call_user_func_array(
                              [$command->command_callback_class, $command->command_callback_method],
                              [$this, $command, $message, $input]
                         );

                         if ($reply instanceof \XF\Mvc\Reply\Error)
                         {
                              $error = $reply->getErrors()[0];
                              return $this->message($error);
                         }

                         if ($reply instanceof \XF\Mvc\Reply\Message || $reply instanceof \XF\Mvc\Reply\View)
                         {
                              return $reply;
                         }

                         if (is_array($reply))
                         {
                              $viewParams = $reply;
                         }

                         if (in_array($command->command_name_default,
                              ['prune', 'kick', 'mute', 'ban', 'unkick', 'unmute', 'unban', 'idle']))
                         {
                              $isPost = false;
                         }
                    }
               }
               else
               {
                    return $this->message(\XF::phrase('siropu_chat_unknown_command', ['command' => $commandName]));
               }
          }

          if ($this->isRoomChannel())
          {
               if (!$visitor->hasJoinedRoomsSiropuChat())
               {
                    return $this->message(\XF::phrase('siropu_chat_no_rooms_joined'));
               }

               if (!$visitor->hasJoinedRoomSiropuChat($this->roomId))
               {
                    return $this->noPermission(\XF::phrase('siropu_chat_room_not_joined'));
               }
          }
          else
          {
               if (!$visitor->hasConversationsSiropuChat())
               {
                    return $this->message(\XF::phrase('siropu_chat_no_conversations'));
               }

               if (!$visitor->hasConversationSiropuChat($this->convId))
               {
                    return $this->noPermission(\XF::phrase('siropu_chat_not_part_of_the_conversation'));
               }
          }

          $guestRoomId = $options->siropuChatGuestRoom;

          if (!$this->isLoggedIn() && $guestRoomId && $this->roomId == $guestRoomId)
          {
               if (!$nickname)
               {
                    $command = $this->getCommandRepo()->findCommandByDefaultName('nick');

                    $reply = $this->message(\XF::phrase('siropu_chat_please_set_a_nickname', ['command' => $command->command_name]));
                    $reply->setJsonParams(['input' => "/{$command->command_name}"]);

                    return $reply;
               }

               $guestService = $this->service('Siropu\Chat:Guest\Manager');
               $guestService->saveGuest($nickname);

               $message->message_username = $nickname;
          }

          $this->setUserMessageColor($message);

          $message->save();

          if ($mentionedUsers && $options->siropuChatUserMentionAlert && $message->isChat())
          {
               $alertRepo = $this->repository('XF:UserAlert');

               foreach ($mentionedUsers as $user)
               {
                    if ($visitor->canAlertMentionSiropuChatUser($user))
                    {
                         $alertRepo->alert(
                              $user,
                              $visitor->user_id,
                              $visitor->username,
                              'siropu_chat_room_message',
                              $message->message_id,
                              'mention'
                         );
                    }
               }
          }

          if ($this->isRoomChannel() && $this->isLoggedIn() && !$message->isError() && empty($viewParams['leave']) && $isPost)
          {
               if ($message->isChat())
               {
                    $visitor->siropuChatIncrementMessageCount();
               }

               if (!$visitor->isActiveSiropuChat($visitor->siropu_chat_rooms[$this->roomId]))
               {
                    $viewParams['inactiveRoom'] = $this->roomId;
               }

               $visitor->siropuChatSetLastActivity();
               $visitor->siropuChatUpdateRooms($this->roomId);
               $visitor->siropuChatSetActiveRoom($this->roomId);
               $visitor->save();
          }

          $viewParams['action']    = 'submit';
          $viewParams['insert_id'] = $message->message_id;

          if ($message->isError())
          {
               $viewParams['input'] = $messageHtml;
          }

          if ($this->isConvChannel())
          {
               return $this->plugin('Siropu\Chat:Update')->getConvUpdates($viewParams);
          }

          $reply = $this->getUpdates($viewParams);

          if (empty($matches)
               && $this->isRoomChannel()
               && $options->siropuChatActiveBotResponseCount
               && !$this->getChatSettings()['hide_bot']
               && !preg_match('/\[(QUOTE|SPOILER|CODE|ICODE).*?\]/i', $messageText))
          {
               $botResponses = $this->getBotResponseRepo()
                    ->findBotResponsesForList()
                    ->isEnabled()
                    ->fetch()
                    ->filter(function(\Siropu\Chat\Entity\BotResponse $response) use ($messageClean)
                    {
                         return ($response->canUse($this->roomId) && $response->isMatch($messageClean));
                    });

               if ($botResponses->count())
               {
                    $matchExactFound = $matchFound = null;

                    foreach ($botResponses as $response)
                    {
                         if ($response->isMatchExact($messageClean) || $response->isMatchFull($messageClean))
                         {
                              $matchExactFound = $response;
                              break;
                         }
                         else
                         {
                              $matchFound = $response;
                              break;
                         }
                    }

                    $this->postBotResponse($matchExactFound ?: $matchFound);
               }
          }

          return $reply;
     }
     protected function getUpdates(array $data = [])
     {
          if ($this->filter('conv_only', 'bool'))
          {
               return $this->plugin('Siropu\Chat:Update')->getConvUpdates();
          }
          else
          {
               return $this->plugin('Siropu\Chat:Update')->getUpdates(array_merge($data, ['lastId' => $this->lastId]));
          }
     }
     public function actionUpdate()
     {
          return $this->getUpdates(['action' => 'update']);
     }
     public function actionSaveSettings()
     {
          $settings = $this->filter([
               'sound'          => 'array-int',
               'notification'   => 'array-int',
               'inverse'        => 'uint',
               'editor_on_top'  => 'uint',
               'image_as_link'  => 'uint',
               'maximized'      => 'uint',
               'mention_alert'  => 'uint',
               'hide_bot'       => 'uint',
               'hide_status'    => 'uint',
               'hide_chatters'  => 'uint',
               'show_ignored'   => 'uint',
               'disable'        => 'uint',
               'display_mode'   => 'str',
               'message_color'  => 'str'
          ]);

          $options = [
               'normal'  => 0,
               'private' => 0,
               'whisper' => 0,
               'mention' => 0,
               'bot'     => 0
          ];

          $settings['sound']        += $options;
          $settings['notification'] += $options;

          $visitor = \XF::visitor();

          if (!($this->isLoggedIn() && $visitor->canChangeSettingsSiropuChat()))
          {
               return $this->noPermission();
          }

          if (!$visitor->canChangeDisplayModeSiropuChat())
          {
               unset($settings['display_mode']);
          }

          $messageColor = $settings['message_color'];

          if (!($visitor->canSetMessageColorSiropuChat() && preg_match('/^rgba?\([0-9., ]+\)$/', $messageColor)))
          {
               unset($settings['message_color']);
          }

          if (!empty($settings['message_color']))
          {
               $colorParams = preg_split('/,/', preg_replace('/[a-z()]+/i', '', $messageColor));

               if (count($colorParams) > 3)
               {
                    array_pop($colorParams);
               }

               $settings['message_color'] = 'rgb(' . implode(',', $colorParams) . ')';
          }

          $visitor->siropu_chat_settings = $settings;
          $visitor->save();

          if ($settings['disable'])
          {
               $displayMode = $this->getChatSettings()['display_mode'] ?: \XF::options()->siropuChatDisplayMode;

               $viewParams  = [
                    'cssClass' => $this->getChatData()->getCssClass($displayMode == 'all_pages')
               ];

               return $this->view('Siropu\Chat:Settings', 'siropu_chat_disabled', $viewParams);
          }

          return $this->view('Siropu\Chat:Settings');
     }
     public function actionRules()
     {
          return $this->view('Siropu\Chat:Rules', 'siropu_chat_rules');
     }
     public function actionHelp()
     {
          return $this->plugin('Siropu\Chat:Chat')->help();
     }
     public function actionUpdateStatus()
     {
          $visitor = \XF::visitor();

          if (!($this->isLoggedIn() && $visitor->canSetSiropuChatStatus()))
          {
               return $this->noPermission();
          }

          if ($this->isPost())
          {
               $visitor->siropuChatSetStatus($this->filter('status', 'str'), $reply);
               $visitor->save();

               return $this->message($reply);
          }

          return $this->view('Siropu\Chat:Status', 'siropu_chat_status');
     }
     public function actionEditRules()
     {
          $visitor = \XF::visitor();

          if (!$visitor->canEditSiropuChatRules())
          {
               return $this->noPermission();
          }

          if ($this->isPost())
          {
               $rules = $this->plugin('XF:Editor')->convertToBbCode($this->filter('rules_html', 'str,no-clean'));
               $this->getOptionRepo()->updateOption('siropuChatRules', $rules);

               return $this->message(\XF::phrase('siropu_chat_rules_saved'));
          }

          return $this->view('Siropu\Chat:Rules\Edit', 'siropu_chat_rules_edit');
     }
     public function actionPostAnnouncement()
     {
          $visitor = \XF::visitor();

          if (!$visitor->canPostSiropuChatAnnouncements())
          {
               return $this->noPermission();
          }

          if ($this->isPost())
          {
               $target      = $this->filter('target', 'str');
               $roomIds     = $this->filter('room_id', 'array-uint');
               $messageHtml = $this->filter('message_html', 'str,no-clean');
               $botName     = $this->filter('bot_name', 'str');

               if (empty($messageHtml))
               {
                    return $this->error(\XF::phrase('please_enter_valid_message'));
               }

               if ($target == 'chat')
               {
                    $roomIds = $this->finder('Siropu\Chat:Room')
                         ->fetch()
                         ->pluckNamed('room_id');
               }
               else if (empty($roomIds))
               {
                    return $this->error(\XF::phrase('siropu_chat_please_select_at_least_one_room'));
               }

               foreach ($roomIds as $roomId)
               {
                    $message = $this->em()->create('Siropu\Chat:Message');
                    $message->message_type = 'announcement';
				$message->message_room_id = $roomId;
				$message->message_text = $this->plugin('XF:Editor')->convertToBbCode($messageHtml);
                    $message->message_bot_name = $botName;
				$message->save();
               }

               return $this->message(\XF::phrase('siropu_chat_announcement_has_been_posted'));
          }

          $rooms = $this->getRoomRepo()
               ->findRoomsForSelect()
               ->fetch()
               ->pluckNamed('room_name', 'room_id');

          $viewParams = [
               'rooms' => $rooms
          ];

          return $this->view('Siropu\Chat:Announcement', 'siropu_chat_post_announcement', $viewParams);
     }
     public function actionResetJoinedRooms()
     {
          $visitor = \XF::visitor();

          if (!$visitor->canResetSiropuChatUserData())
          {
               return $this->noPermission();
          }

          if ($this->isPost())
          {
               \XF::db()->update(
                    'xf_user',
                    ['siropu_chat_rooms' => '{}', 'siropu_chat_last_activity' => -1, 'siropu_chat_room_id' => 1],
                    'siropu_chat_last_activity <> -1'
               );

               return $this->message(\XF::phrase('siropu_chat_joined_rooms_have_been_reset'));
          }

          return $this->view('Siropu\Chat:ResetJoinedRooms', 'siropu_chat_reset_joined_rooms');
     }
     public function actionEditNotice()
     {
          $visitor = \XF::visitor();

          if (!$visitor->canEditSiropuChatNotice())
          {
               return $this->noPermission();
          }

          $viewParams = [];

          if ($this->isPost())
          {
               $notice = [];

               if (!$this->filter('remove_all', 'bool'))
               {
                    foreach ($this->filter('notice', 'array,no-clean') as $key => $val)
                    {
                         if ($val)
                         {
                              $notice[] = $this->plugin('XF:Editor')->convertToBbCode($val);
                         }
                    }
               }

               $this->getOptionRepo()->updateOption('siropuChatNotice', $notice);

               $viewParams['notice'] = $notice;
               return $this->view('Siropu\Chat:Notice\Edit', '', $viewParams);
          }

          return $this->view('Siropu\Chat:NoticeEdit', 'siropu_chat_notice_edit', $viewParams);
     }
     public function actionEditAds()
     {
          $visitor = \XF::visitor();

          if (!$visitor->canEditSiropuChatAds())
          {
               return $this->noPermission();
          }

          if ($this->isPost())
          {
               $adsAboveEditor = [];
               $adsBelowEditor = [];

               if (!$this->filter('remove_all', 'bool'))
               {
                    foreach ($this->filter('ads_above_editor', 'array,no-clean') as $key => $val)
                    {
                         if ($val)
                         {
                              $adsAboveEditor[] = $val;
                         }
                    }

                    foreach ($this->filter('ads_below_editor', 'array,no-clean') as $key => $val)
                    {
                         if ($val)
                         {
                              $adsBelowEditor[] = $val;
                         }
                    }
               }

               $this->getOptionRepo()->updateOption('siropuChatAdsAboveEditor', $adsAboveEditor);
               $this->getOptionRepo()->updateOption('siropuChatAdsBelowEditor', $adsBelowEditor);

               return $this->message(\XF::phrase('siropu_chat_ads_saved'));
          }

          return $this->view('Siropu\Chat:Ads\Edit', 'siropu_chat_ads_edit');
     }
     protected function postBotResponse(\Siropu\Chat\Entity\BotResponse $response)
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          $responses   = array_filter(explode("\n", $response->response_message));
          shuffle($responses);

          $messageText = $responses[0];
          $message     = $this->service('Siropu\Chat:Message\Creator', null, $this->roomId);

          if ($response->response_settings['mention'])
          {
               $message->mentionUser($visitor);
               $messageText = $visitor->siropuChatGetUserWrapper($options->userMentionKeepAt) . ', ' . $messageText;
          }

          $message->setBotName($response->response_bot_name);
          $message->setText($messageText);
          $message->save();

          if ($response->response_settings['interval'])
          {
               $response->updateLastResponse($this->roomId);
               $response->save();
          }
     }
     public function actionLogout()
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!$options->siropuChatRooms)
          {
               return $this->noPermission();
          }

          if (empty($visitor->siropu_chat_rooms))
          {
               return $this->message(\XF::phrase('siropu_chat_no_rooms_joined'));
          }

          if ($this->isPost())
          {
               foreach ($visitor->siropuChatGetJoinedRoomsEntities() as $room)
               {
                    if ($room->canLeave())
                    {
                         $visitor->siropuChatLeaveRoom($room->room_id, true);
                    }
               }

               $visitor->save();

               return $this->view();
          }

          return $this->view('Siropu\Chat:Logout', 'siropu_chat_logout');
     }
     public function actionEnable()
     {
          if ($this->isLoggedIn())
          {
               $visitor = \XF::visitor();
               $visitor->siropu_chat_settings = array_merge($this->getChatSettings(), ['disable' => 0]);
               $visitor->save();
          }

          return $this->view();
     }
     public function actionGuest()
     {
          $visitor = \XF::visitor();

          if (!$visitor->canSanctionSiropuChat())
          {
               return $this->noPermission();
          }

          $guestService = $this->service('Siropu\Chat:Guest\Manager');
          $guest = $guestService->getGuest($this->filter('nickname', 'str'));

          if (empty($guest))
          {
               return $this->error(\XF::phrase('siropu_chat_requested_guest_not_found'));
          }

          if ($this->isPost())
          {
               $action = $this->filter('action', 'str');
               $reason = $this->filter('reason', 'str');

               if ($action == 'ban_ip')
               {
                    $this->getBanningRepo()->banIp($guest['ip'], $reason);
               }
               else
               {
                    $this->getBanningRepo()->discourageIp($guest['ip'], $this->filter('reason', 'str'));
               }

               $guestService->removeGuest($guest['nickname']);

               return $this->redirect($this->buildLink('chat'));
          }

          $viewParams = [
               'guest' => $guest
          ];

          return $this->view('Siropu\Chat:Guest', 'siropu_chat_guest', $viewParams);
     }
     public function actionEmbed()
     {
          $visitor = \XF::visitor();

          if (!$visitor->is_admin)
          {
               return $this->noPermission();
          }

          return $this->view('Siropu\Chat:Embed', 'siropu_chat_embed');
     }
     public function stripBbCode($input)
     {
          return $this->app()->stringFormatter()->stripBbCode($input);
     }
     public function isRoomChannel()
     {
          return $this->channel == 'room';
     }
     public function isConvChannel()
     {
          return $this->channel == 'conversation';
     }
     protected function assertBotResponseExists($id, $with = null)
	{
		return $this->assertRecordExists('Siropu\Chat:BotResponse', $id, $with, 'siropu_chat_requested_bot_response_not_found');
	}
     protected function getOptionRepo()
	{
		return $this->repository('XF:Option');
	}
     protected function getAttachmentRepo()
	{
		return $this->repository('XF:Attachment');
	}
     protected function getBanningRepo()
	{
		return $this->repository('XF:Banning');
	}
}
