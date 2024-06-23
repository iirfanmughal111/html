<?php

namespace Siropu\Chat\Pub\Controller;

abstract class AbstractController extends \XF\Pub\Controller\AbstractController
{
	public function getChatData()
	{
		$class = $this->app()->extendClass('Siropu\Chat\Data');
		return new $class();
	}
	public function getChatSettings()
	{
		return \XF::visitor()->getSiropuChatSettings();
	}
	protected function assertUserExists($id = null, $with = null)
     {
		return $this->assertRecordExists('XF:User', $id, $with, 'requested_user_not_found');
     }
     protected function assertRoomExists($id, $with = null)
	{
		return $this->assertRecordExists('Siropu\Chat:Room', $id, $with, 'siropu_chat_requested_room_not_found');
	}
	protected function assertMessageExists($id, $with = null)
	{
		return $this->assertRecordExists('Siropu\Chat:Message', $id, $with, 'siropu_chat_requested_message_not_found');
	}
     protected function setUserMessageColor(\XF\Mvc\Entity\Entity $message)
     {
          $disabledBBCodes = $this->getChatData()->getDisabledBbCodes();

          if (($color = $this->getChatSettings()['message_color'])
               && !isset($disabledBBCodes['color'])
               && in_array($message->message_type, ['chat', 'whisper']))
          {
               $message->message_text = "[COLOR=$color]{$message->message_text}[/COLOR]";
          }
     }
	/**
	 * @return \Siropu\Chat\Repository\User
	 */
	public function getUserRepo()
     {
          return $this->repository('Siropu\Chat:User');
     }
	/**
	 * @return \Siropu\Chat\Repository\UserAlert
	 */
	public function getUserAlertRepo()
     {
          return $this->repository('Siropu\Chat:UserAlert');
     }
	/**
	 * @return \Siropu\Chat\Repository\UserCommand
	 */
	public function getUserCommandRepo()
     {
          return $this->repository('Siropu\Chat:UserCommand');
     }
	/**
	 * @return \Siropu\Chat\Repository\Room
	 */
     public function getRoomRepo()
     {
          return$this->repository('Siropu\Chat:Room');
     }
	/**
	 * @return \Siropu\Chat\Repository\Message
	 */
     public function getMessageRepo()
     {
          return $this->repository('Siropu\Chat:Message');
     }
	/**
	 * @return \Siropu\Chat\Repository\Sanction
	 */
	public function getSanctionRepo()
     {
          return $this->repository('Siropu\Chat:Sanction');
     }
	/**
	 * @return \Siropu\Chat\Repository\Conversation
	 */
     public function getConversationRepo()
     {
          return $this->repository('Siropu\Chat:Conversation');
     }
	/**
	 * @return \Siropu\Chat\Repository\ConversationMessage
	 */
     public function getConversationMessageRepo()
     {
          return $this->repository('Siropu\Chat:ConversationMessage');
     }
	/**
	 * @return \Siropu\Chat\Repository\Command
	 */
     public function getCommandRepo()
     {
          return $this->repository('Siropu\Chat:Command');
     }
	/**
	 * @return \Siropu\Chat\Repository\BotMessage
	 */
	public function getBotMessageRepo()
     {
          return $this->repository('Siropu\Chat:BotMessage');
     }
	/**
	 * @return \Siropu\Chat\Repository\BotResponse
	 */
     public function getBotResponseRepo()
     {
          return $this->repository('Siropu\Chat:BotResponse');
     }
}
