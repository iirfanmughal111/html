<?php

namespace Siropu\Chat\Pub\Controller;

use XF\Mvc\ParameterBag;

class Message extends AbstractController
{
     public function actionView(ParameterBag $params)
     {
          $message = $this->assertViewableMessage($params->message_id, ['User', 'Room']);
          $message->message_mentions = [];

          $viewParams = [
               'message' => $message
          ];

          return $this->view('Siropu\Chat:Message\View', 'siropu_chat_room_message_view', $viewParams);
     }
     public function actionLink(ParameterBag $params)
     {
          $message = $this->assertMessageExists($params->message_id, ['User', 'Room']);

          if (!$message->canView())
          {
               return $this->noPermission();
          }

          $viewParams = [
               'message' => $message
          ];

          return $this->view('Siropu\Chat:Message\Link', 'siropu_chat_room_message_link', $viewParams);
     }
     public function actionEdit(ParameterBag $params)
     {
          $message = $this->assertMessageExists($params->message_id);

          if (!$message->canEdit($error))
          {
               return $this->noPermission($error);
          }

          if ($this->isPost())
          {
               $messageHtml = $this->plugin('Siropu\Chat:Chat')->getHtmlMessage();
               $messageText = $this->plugin('XF:Editor')->convertToBbCode($messageHtml);

               $messagePreparerService = $this->service('Siropu\Chat:Message\Preparer');
               $messagePreparerService->prepare($messageText);

               if ($messagePreparerService->isValid())
               {
                    $messageText = $messagePreparerService->getMessage();
               }
               else
               {
                    return $this->error($messagePreparerService->getErrors());
               }

               $oldMessage = $message->message_text;

               if ($message->canChangeAuthor())
               {
                    $message->message_username = $this->filter('username', 'str');

                    if ($message->isChanged('message_username'))
                    {
                         $user = $this->em()->findOne('XF:User', ['username' => $message->message_username]);

                         if ($user)
                         {
                              $message->message_user_id = $user->user_id;
                         }
                    }
               }

               $message->message_text = $messageText;
               $this->setUserMessageColor($message);

               $message->message_edit_count++;
               $message->save();

               $editHistoryRepo = $this->repository('XF:EditHistory');
			$editHistoryRepo->insertEditHistory(
                    'siropu_chat_room_message',
                    $message,
                    \XF::visitor(),
                    $oldMessage,
                    $this->app()->request()->getIp()
               );

               $reply = $this->view('Siropu\Chat:Message\Edit', 'siropu_chat_room_message_row', ['message' => $message]);
               $reply->setJsonParams([
                    'message'    => \XF::phrase('siropu_chat_message_edited'),
                    'message_id' => $message->message_id,
                    'channel'    => 'room'
               ]);

               return $reply;
          }

          $viewParams = [
               'message'         => $message,
               'disabledButtons' => $this->getChatData()->getDisabledButtons()
          ];

          return $this->view('', 'siropu_chat_room_message_edit', $viewParams);
     }
     public function actionDelete(ParameterBag $params)
     {
          $message = $this->assertMessageExists($params->message_id);

          if (!$message->canDelete($error))
          {
               return $this->noPermission($error);
          }

          if ($this->isPost())
          {
               $message->delete();

               if ($message->isCounted() && $this->isLoggedIn() && $message->User)
               {
                    $user = $this->em()->find('XF:User', $message->message_user_id);
                    $user->siropuChatDecrementMessageCount();
                    $user->save();
               }

               $reply = $this->view();
               $reply->setJsonParams([
                    'message'    => \XF::phrase('siropu_chat_message_deleted'),
                    'message_id' => $message->message_id,
                    'channel'    => 'room'
               ]);

               return $reply;
          }

          $viewParams = [
               'message' => $message
          ];

          return $this->view('Siropu\Chat:Message\Delete', 'siropu_chat_room_message_delete', $viewParams);
     }
     public function actionQuote(ParameterBag $params)
     {
          $message = $this->assertViewableMessage($params->message_id);

          if (!$message->canQuote())
          {
               return $this->noPermission();
          }

          return $this->plugin('XF:Quote')->actionQuote($message, 'siropu_chat_room_message', 'message_text');
     }
     public function actionLike(ParameterBag $params)
     {
          $message = $this->assertViewableMessage($params->message_id);

          if (!$message->canLike())
          {
               return $this->noPermission();
          }

          if (!$message->isLiked())
          {
               $message->like();
               $message->incrementLikeCount();
               $message->save();
          }

          return $this->view('Siropu\Chat:Message\Like', 'siropu_chat_room_message_row', ['message' => $message]);
     }
     public function actionUnlike(ParameterBag $params)
     {
          $message = $this->assertViewableMessage($params->message_id);

          if (!$message->canUnlike())
          {
               return $this->noPermission();
          }

          if ($message->isLiked())
          {
               $message->unlike();
               $message->decrementLikeCount();
               $message->save();
          }

          return $this->view('Siropu\Chat:Message\Unlike', 'siropu_chat_room_message_row', ['message' => $message]);
     }
     public function actionLikes(ParameterBag $params)
     {
          $message = $this->assertViewableMessage($params->message_id);

          $userIds = $message->getLikesUserIds();

          $users = $this->finder('XF:User')
               ->where('user_id', $message->getLikesUserIds())
               ->fetch();

          if (in_array(0, $userIds))
          {
               $guest = $this->em()->create('XF:User');
               $guest->setTrusted('user_id', 0);
               $guest->setTrusted('username', \XF::phrase('guest')->render());
               $guest->setTrusted('custom_title', '--');

     		$users = $users->toArray();
     		$users = $users + [$guest];
     		$users = $this->em()->getBasicCollection($users);
          }

          $viewParams = [
               'message' => $message,
               'users'   => $users
          ];

          return $this->view('Siropu\Chat:Message\Likes', 'siropu_chat_room_message_likes', $viewParams);
     }
     public function actionReport(ParameterBag $params)
     {
          $message = $this->assertViewableMessage($params->message_id);

          if (!$message->canReport())
          {
               return $this->noPermission();
          }

          $reportPlugin = $this->plugin('XF:Report');

		return $reportPlugin->actionReport(
			'siropu_chat_room_message', $message,
			$this->buildLink('chat/message/report', $message),
			$this->buildLink('chat/message', $message)
		);
     }
     public function actionHistory(ParameterBag $params)
	{
		return $this->rerouteController('XF:EditHistory', 'index', [
			'content_type' => 'siropu_chat_room_message',
			'content_id'   => $params->message_id
		]);
	}
     public function actionReact(ParameterBag $params)
	{
          $options = \XF::options();

          if (!$options->siropuChatReactions)
          {
               return $this->noPermission();
          }

		$message = $this->assertViewableMessage($params->message_id);

		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($message, 'chat/message');
	}
	public function actionReactions(ParameterBag $params)
	{
		$message = $this->assertViewableMessage($params->message_id);

		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$message,
			'chat/message/reactions',
			null
		);
	}
     protected function assertViewableMessage($id, $with = ['Room'])
     {
          $message = $this->assertRecordExists('Siropu\Chat:Message', $id, $with, 'siropu_chat_requested_message_not_found');
          $visitor = \XF::visitor();

          if (!($message->canView() && ($message->Room->canJoin() || $visitor->hasJoinedRoomSiropuChat($message->message_room_id))))
          {
               throw $this->exception($this->noPermission());
          }

          return $message;
     }
}
