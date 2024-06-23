<?php

namespace Siropu\Chat\Entity;

use XF\Entity\QuotableInterface;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Entity\ReactionTrait;

class ConversationMessage extends Entity implements QuotableInterface, \XF\BbCode\RenderableContentInterface
{
     use ReactionTrait;

     public static function getStructure(Structure $structure)
	{
          $structure->table       = 'xf_siropu_chat_conversation_message';
          $structure->shortName   = 'Chat:ConversationMessage';
          $structure->primaryKey  = 'message_id';
          $structure->contentType = 'siropu_chat_conv_message';

          $structure->columns = [
               'message_id'              => ['type' => self::UINT, 'autoIncrement' => true],
               'message_conversation_id' => ['type' => self::UINT, 'required' => true],
               'message_user_id'         => ['type' => self::UINT, 'default' => \XF::visitor()->user_id],
               'message_username'        => ['type' => self::STR, 'maxLength' => 50, 'default' => \XF::visitor()->username],
               'message_text'            => ['type' => self::STR, 'required' => true, 'censor' => true],
               'message_type'            => ['type' => self::STR, 'default' => 'chat', 'allowedValues' =>
                    ['chat', 'me', 'bot', 'error']],
               'message_read'            => ['type' => self::UINT, 'default' => 0],
               'message_liked'           => ['type' => self::UINT, 'default' => 0],
               'message_date'            => ['type' => self::UINT, 'default' => \XF::$time]
          ];

          $structure->getters   = [
               'css_class' => true,
               'message'   => true
          ];

          $structure->relations = [
               'Conversation' => [
                    'entity'     => 'Siropu\Chat:Conversation',
                    'type'       => self::TO_ONE,
                    'conditions' => [['conversation_id', '=', '$message_conversation_id']]
               ],
               'User' => [
                    'entity'     => 'XF:User',
                    'type'       => self::TO_ONE,
                    'conditions' => [['user_id', '=', '$message_user_id']]
               ]
          ];

          $structure->defaultWith = ['User'];

          static::addReactableStructureElements($structure);

          return $structure;
     }
     public function getCssClass()
     {
          $cssCLass = 'siropuChatMessageRow';

          switch ($this->message_type)
          {
               case 'me':
                    $cssCLass .= ' siropuChatMe';
                    break;
               case 'bot':
                    $cssCLass .= ' siropuChatBot';
                    break;
               case 'error':
                    $cssCLass .= ' siropuChatError';
                    break;
          }

          if ($this->isUnread())
          {
               $cssCLass .= ' siropuChatUnread';
          }

          return $cssCLass;
     }
     public function getMessage()
     {
          $visitor = \XF::visitor();

          if ($visitor->getSiropuChatSetting('image_as_link'))
          {
               return preg_replace('/\[IMG\](.*?)\[\/IMG\]/i', '[URL]$1[/URL]', $this->message_text);
          }

          return $this->message_text;
     }
     public function canLink()
     {
          return $this->isChat();
     }
     public function canView()
     {
          $visitor = \XF::visitor();

          if ($this->isError() && !$this->isSelf())
          {
               return false;
          }

          return true;
     }
     public function canLike()
     {
          $visitor = \XF::visitor();

          return !$this->isSelf() && $visitor->hasPermission('siropuChat', 'likeMessages');
     }
     public function canReact(&$error = null)
     {
          return $this->canLike();
     }
     public function isLiked()
     {
          return $this->message_liked == 1;
     }
     public function isUnread()
     {
          $visitor = \XF::visitor();

          return $this->message_read == 0 && !$this->isSelf();
     }
     public function isSelf()
     {
          $visitor = \XF::visitor();

          return $this->message_user_id == $visitor->user_id;
     }
     public function isBot()
     {
          return $this->message_type == 'bot';
     }
     public function isError()
     {
          return $this->message_type == 'error';
     }
     public function isChat()
     {
          return $this->message_type == 'chat';
     }
     public function isRecipient() {}
     public function isMentioned() {}
     public function canQuote()
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if ($this->isBot() || $this->isError())
          {
               return false;
          }

          if (!$visitor->canUseSiropuChat())
          {
               return false;
          }

          return !empty($options->siropuChatEnabledBBCodes['quote']);
     }
     public function canReport()
     {
          $visitor = \XF::visitor();

          return !$this->isSelf() && $visitor->canReportSiropuChatMessages();
     }
     public function canEditDelete()
     {
          $visitor = \XF::visitor();

          return $this->message_read == 0 && $this->message_user_id == $visitor->user_id && !$this->isBot();
     }
     public function setError($error)
     {
          $this->message_text = $error;
          $this->message_type = 'error';
     }
     public function like()
     {
          $this->message_liked = 1;
     }
     public function unlike()
     {
          $this->message_liked = 0;
     }
     public function getQuoteWrapper($inner)
	{
          return '[QUOTE="' . $this->message_username . '"]' . $inner . '[/QUOTE]';
     }
     public function getBbCodeRenderOptions($context, $type)
     {
          return [
               'entity' => $this,
               'user'   => $this->User
          ];
     }
     protected function _postDeleteRatings(array $ratingIds)
	{
          /** @var \XF\Repository\Reaction $reactionRepo */
		$reactionRepo = $this->repository('XF:Reaction');
		$reactionRepo->fastDeleteReactions('siropu_chat_conv_message', $ratingIds);
     }
     protected function _postSave()
     {
          $visitor       = \XF::visitor();
          $options       = \XF::options();
          $conversations = $visitor->siropu_chat_conversations;

          if ($options->siropuChatConversationMessageAlert && !empty($conversations[$this->message_conversation_id]))
          {
               $recipient = $this->em()->find('XF:User', $conversations[$this->message_conversation_id]);

               if ($recipient && $recipient->isInConversationWithSiropuChatUser($visitor))
               {
                    $unreadCount = $this->finder('XF:UserAlert')
                         ->where('alerted_user_id', $recipient->user_id)
                         ->where('content_type', 'siropu_chat_conv')
                         ->where('view_date', 0)
                         ->total();

                    if ($unreadCount == 0)
                    {
                         $alertRepo = \XF::repository('XF:UserAlert');
          			$alertRepo->alert(
          				$recipient,
          				$this->message_user_id,
          				$this->message_username,
          				'siropu_chat_conv',
          				$this->message_conversation_id,
          				'new_message'
          			);
                    }
               }
          }

          if ($this->isUpdate())
          {
               if ($this->isChanged('message_liked'))
               {
                    $action = 'like';
               }
               else if ($this->isChanged('reactions'))
               {
                    $action = 'react';
               }
               else
               {
                    $action = 'edit';
               }

               $this->app()->service('Siropu\Chat:Conversation\ActionLogger', $action)->logMessageAction($this);
          }
     }
     protected function _postDelete()
	{
          /** @var \XF\Repository\Reaction $reactionRepo */
          $reactionRepo = $this->repository('XF:Reaction');
          $reactionRepo->fastDeleteReactions('siropu_chat_conv_message', $this->message_id);
     }
}
