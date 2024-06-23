<?php

namespace Siropu\Chat;

use XF\Mvc\Entity\Entity;

class Listener
{
     protected static $excerptLength = 30;

     public static function userEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure) {}
     public static function appSetup(\XF\App $app)
     {
          $path = \XF::getAddOnDirectory() . '/Siropu/Chat/Vendor/MobileDetect';

          if (phpversion() >= 7.4)
          {
               $classMap = ['Detection\MobileDetect' => "$path/MobileDetect.php"];
          }
          else
          {
               $classMap = ['Mobile_Detect' => "$path/Mobile_Detect.php"];
          }

          \XF::$autoLoader->addClassMap($classMap);
     }
     public static function templaterSetup(\XF\Container $container, \XF\Template\Templater &$templater)
     {
          $templater->addFunction('siropu_chat_unread_conv_count', function(\XF\Template\Templater $templater, &$escape)
		{
               return \XF::repository('Siropu\Chat:ConversationMessage')
                    ->findMessages()
                    ->forConversation(\XF::visitor()->siropuChatGetConvIds())
                    ->unread()
                    ->total();
          });
     }
     public static function editorButtonData(array &$buttons, \XF\Data\Editor $editorData)
	{
          $buttons['xfCustom_chat'] = [
               'title' => \XF::phrase('siropu_chat_image_uploads'),
               'fa'    => 'fa-upload'
          ];
	}
     public static function templaterTemplatePreRenderEditor(\XF\Template\Templater $templater, &$type, &$template, array &$params)
	{
		if (\XF::visitor()->canUploadSiropuChatImages())
		{
			$params['customIcons']['chat'] = [
                    'title'  => \XF::phrase('siropu_chat_image_uploads'),
                    'type'   => 'fa',
                    'value'  => 'upload',
                    'option' => 'yes'
               ];
		}
	}
     public static function userEntityPostSave(\XF\Mvc\Entity\Entity $entity)
     {
          $options = \XF::options();

          if (($activityRoomId = $options->siropuChatForumActivityRoom)
               && $options->siropuChatForumActivityUsers
               && ($entity->isInsert()
                    && $entity->user_state == 'valid'
                         || ($entity->isUpdate()
                              && $entity->isChanged('user_state')
                              && in_array($entity->getPreviousValue('user_state'), ['email_confirm', 'moderated'])
                              && $entity->user_state == 'valid'
                              && !$entity->is_banned)))
          {
               $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $activityRoomId, 'user');
     		$message->setText(\XF::phrase('siropu_chat_new_user_notification', [
                    'user' => new \XF\PreEscaped($entity->siropuChatGetUserWrapper())
               ]));
               $message->setTypeId($entity->user_id);
     		$message->save();
          }
     }
     public static function userEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
     {
          self::deleteMessagesByTypeId('user', $entity->user_id);

          if (\XF::options()->siropuChatDeleteUserConversationIfDeleted)
          {
               $conversations = \XF::finder('Siropu\Chat:Conversation')
                    ->whereOr([['user_1', $entity->user_id], ['user_2', $entity->user_id]])
                    ->fetch();

               foreach ($conversations as $conversation)
               {
                    $conversation->delete();
               }
          }

          \XF::repository('Siropu\Chat:Sanction')->removeAllUserSanctions($entity->user_id);

          $attachments = \XF::repository('XF:Attachment')->findAttachmentsForList()
               ->where('content_type', 'siropu_chat')
               ->where('content_id', $entity->user_id)
               ->fetch();

          foreach ($attachments as $attachment)
          {
               $attachment->delete();
               $attachment->Data->delete();
          }
     }
     public static function userMergeCombine(\XF\Entity\User $target, \XF\Entity\User $source, \XF\Service\User\Merge $mergeService)
	{
		$target->siropu_chat_message_count += $source->siropu_chat_message_count;
	}
     public static function postEntityPostSave(\XF\Mvc\Entity\Entity $entity)
     {
          $options = \XF::options();
          $router  = \XF::app()->router('public');
          $request = \XF::app()->request();

          if (($activityRoomId = $options->siropuChatForumActivityRoom)
               && $entity->Thread
               && self::assertValidForum($entity->Thread->node_id)
               && $entity->Thread->isVisible())
          {
               $messageText = '';

               $threadTitle = self::cleanThreadTitle($entity->Thread->title);

               if ($options->siropuChatForumActivityThreads && $entity->isInsert() && $entity->isFirstPost())
               {
                    $messageText = \XF::phrase('siropu_chat_new_thread_notification', [
                         'user'   => new \XF\PreEscaped('[USER=' . $entity->user_id . ']' . $entity->username . '[/USER]'),
                         'thread' => new \XF\PreEscaped('[URL=' . $router->buildLink('full:threads', $entity->Thread) . ']' . $threadTitle . '[/URL]'),
                         'forum'  => new \XF\PreEscaped('[URL=' . $router->buildLink('full:forums', $entity->Thread->Forum) . ']' . $entity->Thread->Forum->title . '[/URL]')
                    ]);

                    $messageType = 'thread';
                    $typeId      = $entity->thread_id;
               }

               if ($options->siropuChatForumActivityPosts
                    && !$entity->isFirstPost()
                    && ($entity->isInsert() && $entity->isVisible() || $entity->getPreviousValue('message_state') == 'moderated'))
               {
                    $messageText = \XF::phrase('siropu_chat_new_post_notification', [
                         'user'    => new \XF\PreEscaped('[USER=' . $entity->user_id . ']' . $entity->username . '[/USER]'),
                         'url'     => $router->buildLink('full:posts', $entity),
                         'thread'  => new \XF\PreEscaped('[URL=' . $router->buildLink('full:threads/unread', $entity->Thread) . ']' . $threadTitle . '[/URL]'),
                         'excerpt' => \XF::app()->stringFormatter()->snippetString($entity->message, self::$excerptLength, [
                              'stripQuote' => true,
                              'fromStart'  => true
                         ])
                    ]);

                    $messageType = 'post';
                    $typeId      = $entity->post_id;
               }

               if ($messageText)
               {
                    $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $activityRoomId, $messageType);
                    $message->setText($messageText . self::getPostMessageExcerpt($entity));
                    $message->setBotName($options->siropuChatForumActivityBotName);
                    $message->setTypeId($typeId);
                    $message->setTypeCategoryId($entity->Thread->node_id);
                    $message->save();
               }
          }

          if ($entity->isChanged('message_state') && $entity->message_state == 'deleted')
          {
               self::deleteMessagesByTypeId('post', $entity->post_id);
          }

          if ($entity->isInsert() && !$entity->isFirstPost() && !$request->filter('room_id', 'uint'))
          {
               $roomThreadCache = \XF::repository('Siropu\Chat:Room')->getRoomThreadCache();

               if (isset($roomThreadCache[$entity->thread_id]))
               {
                    $messageText = $entity->message;

                    if ($options->siropuChatStripPostBbCode)
                    {
                         $messageText = \XF::app()->stringFormatter()->stripBbCode($messageText, ['stripQuote' => true]);
                    }

                    $messageText .= ' ' . \XF::phrase('siropu_chat_thread_reply', ['url' => $router->buildLink('full:posts', $entity)]);

                    foreach ($roomThreadCache[$entity->thread_id] as $roomId)
                    {
                         $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $roomId, 'thread_reply');
                         $message->setText($messageText);
                         $message->save();
                    }
               }
          }
     }
     public static function postEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
     {
          self::deleteMessagesByTypeId('post', $entity->post_id);
     }
     public static function threadEntityPostSave(\XF\Mvc\Entity\Entity $entity)
     {
          $visitor = \XF::visitor();
          $options = \XF::options();
          $router  = \XF::app()->router('public');

          if (($activityRoomId = $options->siropuChatForumActivityRoom)
               && self::assertValidForum($entity->node_id)
               && $entity->isVisible()
               && $entity->isUpdate()
               && $entity->getPreviousValue('discussion_state') == 'moderated')
          {
               $messageText = \XF::phrase('siropu_chat_new_thread_notification', [
                    'user'   => new \XF\PreEscaped('[USER=' . $entity->user_id . ']' . $entity->username . '[/USER]'),
                    'thread' => new \XF\PreEscaped('[URL=' . $router->buildLink('full:threads', $entity) . ']' . self::cleanThreadTitle($entity->title) . '[/URL]'),
                    'forum'  => new \XF\PreEscaped('[URL=' . $router->buildLink('full:forums', $entity->Forum) . ']' . $entity->Forum->title . '[/URL]')
               ]);

               $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $activityRoomId, 'thread');
               $message->setText($messageText . self::getPostMessageExcerpt($entity->FirstPost));
               $message->setBotName($options->siropuChatForumActivityBotName);
               $message->setTypeId($entity->thread_id);
               $message->setTypeCategoryId($entity->node_id);
               $message->save();
          }

          if ($entity->isChanged('discussion_state') && $entity->discussion_state == 'deleted')
          {
               self::deleteMessagesByTypeId('thread', $entity->thread_id);
          }

          $request = \XF::app()->request();

          if ($entity->isInsert() && $visitor->hasPermission('siropuChat', 'createThreadRoom'))
          {
               $input = $request->filter('siropu_chat', 'array');

               if (!empty($input['room_create']))
               {
                    $room = \XF::em()->create('Siropu\Chat:Room');
          		$room->bulkSet([
          			'room_name'         => $input['room_name'] ?: $entity->title,
          			'room_description'  => $input['room_description'] ?: \XF::phrase('n_a')->render(),
                         'room_thread_id'    => !empty($input['room_thread_post']) ? $entity->thread_id : 0,
                         'room_thread_reply' => !empty($input['room_thread_reply']),
                         'room_locked'       => $request->filter('room_lock', 'datetime')
          		]);
          		$room->save(false);
               }
          }
     }
     public static function threadEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
     {
          self::deleteMessagesByTypeId('thread', $entity->thread_id);
     }
     public static function XFMGMediaItemEntityPostSave(\XF\Mvc\Entity\Entity $entity)
     {
          $options = \XF::options();
          $router  = \XF::app()->router('public');

          if (($activityRoomId = $options->siropuChatXFMGActivityRoom) && $options->siropuChatXFMGActivityMediaItem)
          {
               $canPost     = true;
               $messageText = '';

               $phraseParams = [
                    'user'     => '[USER=' . $entity->user_id . ']' . $entity->username . '[/USER]',
                    'mediaURL' => $router->buildLink('full:media', $entity)
               ];

               if ($entity->Album)
               {
                    if (!in_array($entity->Album->view_privacy, ['public', 'members']))
                    {
                         $canPost = false;
                    }

                    $phraseParams['album'] = new \XF\PreEscaped('[URL=' . $router->buildLink('full:media/albums', $entity->Album) . ']' . $entity->Album->title . '[/URL]');

                    $messageText = \XF::phrase('siropu_chat_new_media_album_item_notification', $phraseParams);
               }
               else if ($entity->Category)
               {
                    $phraseParams['category'] = new \XF\PreEscaped('[URL=' . $router->buildLink('full:media/categories', $entity->Category) . ']' . $entity->Category->title . '[/URL]');

                    $messageText = \XF::phrase('siropu_chat_new_media_category_item_notification', $phraseParams);
               }

               if ($canPost
                    && $messageText
                    && ($entity->isInsert()
                              && $entity->media_state == 'visible'
                         || ($entity->isUpdate()
                              && $entity->media_state == 'visible'
                              && $entity->getPreviousValue('media_state') == 'moderated')))
               {
                    $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $activityRoomId, 'media_item');
          		$message->setText($messageText);
                    $message->setBotName($options->siropuChatXFMGActivityBotName);
                    $message->setTypeId($entity->media_id);
                    $message->setTypeCategoryId($entity->category_id);
          		$message->save();
               }
          }

          if ($entity->isChanged('media_state') && $entity->media_state == 'deleted')
          {
               self::deleteMessagesByTypeId('media_item', $entity->media_id);
          }
     }
     public static function XFMGMediaItemEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
     {
          self::deleteMessagesByTypeId('media_item', $entity->media_id);
     }
     public static function XFMGAlbumEntityPostSave(\XF\Mvc\Entity\Entity $entity)
     {
          $options = \XF::options();
          $router  = \XF::app()->router('public');

          if (($activityRoomId = $options->siropuChatXFMGActivityRoom)
               && $options->siropuChatXFMGActivityMediaAlbum
               && (($entity->isInsert()
                         && $entity->album_state == 'visible'
                         && in_array($entity->view_privacy, ['public', 'members']))
                    || ($entity->isUpdate()
                         && $entity->album_state == 'visible'
                         && $entity->getPreviousValue('album_state') == 'moderated')))
          {
               $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $activityRoomId, 'media_album');
               $message->setText(\XF::phrase('siropu_chat_new_media_album_notification', [
                    'user'  => new \XF\PreEscaped('[USER=' . $entity->user_id . ']' . $entity->username . '[/USER]'),
                    'album' => new \XF\PreEscaped('[URL=' . $router->buildLink('full:media/albums', $entity) . ']' . $entity->title . '[/URL]')
               ]));
               $message->setBotName($options->siropuChatXFMGActivityBotName);
               $message->setTypeId($entity->album_id);
               $message->save();
          }

          if ($entity->isChanged('album_state') && $entity->album_state == 'deleted')
          {
               self::deleteMessagesByTypeId('media_album', $entity->album_id);
          }
     }
     public static function XFMGAlbumEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
     {
          self::deleteMessagesByTypeId('media_album', $entity->album_id);
     }
     public static function XFMGCommentEntityPostSave(\XF\Mvc\Entity\Entity $entity)
     {
          $options = \XF::options();
          $router  = \XF::app()->router('public');

          if (($activityRoomId = $options->siropuChatXFMGActivityRoom) && $options->siropuChatXFMGActivityComment)
          {
               $canPost    = true;
               $categoryId = 0;

               $phraseParams = [
                    'user' => new \XF\PreEscaped('[USER=' . $entity->user_id . ']' . $entity->username . '[/USER]')
               ];

               if ($entity->content_type == 'xfmg_media')
               {
                    $phraseParams['media'] = new \XF\PreEscaped('[URL=' . $router->buildLink('full:media', $entity->Media) . ']' . $entity->Media->title . '[/URL]');

                    $messageText = \XF::phrase('siropu_chat_new_media_comment_notification', $phraseParams);
                    $categoryId  =  $entity->Media->category_id;
               }
               else
               {
                    if (!in_array($entity->Album->view_privacy, ['public', 'members']))
                    {
                         $canPost = false;
                    }

                    $phraseParams['album'] = new \XF\PreEscaped('[URL=' . $router->buildLink('full:media/albums', $entity->Album) . ']' . $entity->Album->title . '[/URL]');

                    $messageText = \XF::phrase('siropu_chat_new_media_album_comment_notification', $phraseParams);
               }

               if ($canPost
                    && (($entity->isInsert()
                              && $entity->comment_state == 'visible')
                         || ($entity->isUpdate()
                              && $entity->comment_state == 'visible'
                              && $entity->getPreviousValue('comment_state') == 'moderated')))
               {
                    $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $activityRoomId, 'media_comment');
          		$message->setText($messageText);
                    $message->setBotName($options->siropuChatXFMGActivityBotName);
                    $message->setTypeId($entity->comment_id);
                    $message->setTypeCategoryId($categoryId);
          		$message->save();
               }
          }

          if ($entity->isChanged('comment_state') && $entity->comment_state == 'deleted')
          {
               self::deleteMessagesByTypeId('media_comment', $entity->comment_id);
          }
     }
     public static function XFMGCommentEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
     {
          self::deleteMessagesByTypeId('media_comment', $entity->comment_id);
     }
     public static function RMResourceItemEntityPostSave(\XF\Mvc\Entity\Entity $entity)
     {
          $options = \XF::options();
          $router  = \XF::app()->router('public');

          if (($activityRoomId = $options->siropuChatXFRMActivityRoom)
               && (($entity->isInsert()
                         && $entity->resource_state == 'visible')
                    || ($entity->isUpdate()
                         && $entity->resource_state == 'visible'
                         && $entity->getPreviousValue('resource_state') == 'moderated')))
          {
               $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $activityRoomId, 'resource_item');
               $message->setText(\XF::phrase('siropu_chat_new_resource_notification', [
                    'user'     => new \XF\PreEscaped('[USER=' . $entity->user_id . ']' . $entity->username . '[/USER]'),
                    'resource' => new \XF\PreEscaped('[URL=' . $router->buildLink('full:resources', $entity)  . ']' . $entity->title . '[/URL]'),
                    'category' => new \XF\PreEscaped('[URL=' . $router->buildLink('full:resources/categories', $entity->Category)  . ']' . $entity->Category->title . '[/URL]')
               ]));
               $message->setBotName($options->siropuChatXFRMActivityBotName);
               $message->setTypeId($entity->resource_id);
               $message->setTypeCategoryId($entity->resource_category_id);
               $message->save();
          }

          if ($entity->isChanged('resource_state') && $entity->resource_state == 'deleted')
          {
               self::deleteMessagesByTypeId('resource_item', $entity->resource_id);
          }
     }
     public static function RMResourceItemEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
     {
          self::deleteMessagesByTypeId('resource_item', $entity->resource_id);
     }
     public static function RMResourceRatingEntityPostSave(\XF\Mvc\Entity\Entity $entity)
     {
          $options = \XF::options();
          $visitor = \XF::visitor();
          $router  = \XF::app()->router('public');

          if (($activityRoomId = $options->siropuChatXFRMActivityRoom)
               && $options->siropuChatXFRMActivityRating
               && $entity->isInsert()
               && $entity->is_review)
          {
               $message = \XF::app()->service('Siropu\Chat:Message\Creator', $entity, $activityRoomId, 'resource_rating');
               $message->setText(\XF::phrase('siropu_chat_new_resource_rating_notification', [
                    'user'      => new \XF\PreEscaped('[USER=' . $visitor->user_id . ']' . $visitor->username . '[/USER]'),
                    'reviewUrl' => $router->buildLink('full:resources/review', $entity),
                    'resource'  => new \XF\PreEscaped('[URL=' . $router->buildLink('full:resources', $entity->Resource)  . ']' . $entity->Resource->title . '[/URL]')
               ]));
               $message->setBotName($options->siropuChatXFRMActivityBotName);
               $message->setTypeId($entity->resource_rating_id);
               $message->setTypeCategoryId($entity->Resource->resource_category_id);
               $message->save();
          }

          if ($entity->isChanged('rating_state') && $entity->rating_state == 'deleted')
          {
               self::deleteMessagesByTypeId('resource_rating', $entity->resource_rating_id);
          }
     }
     public static function RMResourceRatingEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
     {
          self::deleteMessagesByTypeId('resource_rating', $entity->resource_rating_id);
     }
     public static function editorDialog(array &$data, \XF\Mvc\Controller $controller)
	{
          $attachmentRepo = \XF::repository('XF:Attachment');
		$attachmentData = $attachmentRepo->getEditorData('siropu_chat', \XF::visitor());

          $attachmentList = $attachmentRepo->findAttachmentsForList()
               ->where('content_type', 'siropu_chat')
               ->where('content_id', \XF::visitor()->user_id)
               ->fetch();

		$data['template'] = 'siropu_chat_editor_dialog_chat';
          $data['params']   = [
               'attachmentData' => $attachmentData,
               'attachmentList' => $attachmentList
          ];
	}
     public static function criteriaUser($rule, array $data, \XF\Entity\User $user, &$returnValue)
	{
		switch ($rule)
		{
			case 'siropu_chat_messages_posted':
				if (isset($user->siropu_chat_message_count) && $user->siropu_chat_message_count >= $data['messages_posted'])
				{
					$returnValue = true;
				}
				break;
               case 'siropu_chat_messages_maximum':
				if (isset($user->siropu_chat_message_count) && $user->siropu_chat_message_count <= $data['messages_maximum'])
				{
					$returnValue = true;
				}
				break;
		}
	}
     private static function assertValidForum($id)
     {
          $forums = \XF::options()->siropuChatForumActivityForums;

		if (empty($forums) || in_array($id, $forums))
		{
			return true;
		}
     }
     private static function getPostMessageExcerpt($post)
     {
          $options = \XF::options();

          if ($options->siropuChatForumActivityPostsExcept['enabled'])
          {
               if ($limit = $options->siropuChatForumActivityPostsExcept['limit'])
               {
                   $excerpt = \XF::app()->stringFormatter()->snippetString($post->message, $limit, [
                        'stripQuote' => true,
                        'fromStart'  => true
                   ]);
               }
               else
               {
                   $excerpt = $post->message;
               }

               return ' [QUOTE="' . $post->username . ', post: ' . $post->post_id . ', member: ' . $post->user_id . '"]' . $excerpt . '[/QUOTE]';
          }
     }
     private static function deleteMessagesByTypeId($type, $id)
     {
          $options     = \XF::options();
          $messageRepo = \XF::repository('Siropu\Chat:Message');
          $delete      = false;

          switch ($type)
          {
               case 'user':
               case 'thread':
               case 'post':
                    if ($options->siropuChatForumActivityRoom)
                    {
                         $delete = true;
                    }
                    break;
               case 'media_item':
               case 'media_album':
               case 'media_comment':
                    if ($options->siropuChatXFMGActivityRoom)
                    {
                         $delete = true;
                    }
                    break;
               case 'resource_item':
               case 'resource_rating':
                    if ($options->siropuChatXFRMActivityRoom)
                    {
                         $delete = true;
                    }
                    break;
          }

          if ($delete)
          {
               $messageRepo->deleteMessagesByTypeId($type, $id);
          }
     }
     private static function cleanThreadTitle($title)
     {
          $title = \XF::app()->stringFormatter()->stripBbCode($title, ['stripQuote' => true]);
          return trim(str_replace(['[CODE]', '[/CODE]'], '', $title));
     }
}
