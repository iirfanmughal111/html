<?php

namespace Siropu\Chat\Service\Message;

class Creator extends \XF\Service\AbstractService
{
	protected $message;
	protected $entity;

	public function __construct(\XF\App $app, \XF\Mvc\Entity\Entity $entity = null, $roomId = 0, $type = 'bot')
	{
		parent::__construct($app);

		$this->message = $this->em()->create('Siropu\Chat:Message');
		$this->message->message_room_id = $roomId;
		$this->message->message_type = $type;

		$this->entity = $entity;
	}
	public function setType($type)
	{
		$this->message->message_type = $type;
	}
     public function setTypeId($typeId)
	{
		$this->message->message_type_id = $typeId;
	}
     public function setTypeCategoryId($typeCategoryId)
	{
		$this->message->message_type_category_id = $typeCategoryId;
	}
	public function setText($text)
	{
		$this->message->message_text = $text;
	}
	public function setBotName($name)
	{
		$this->message->message_bot_name = $name;
	}
	public function mentionUser($user)
	{
		$this->message->mentionUser($user);
	}
	public function save()
	{
		$this->message->message_user_id = isset($this->entity->user_id) ? $this->entity->user_id : 0;
		$this->message->message_username =  isset($this->entity->username) ? $this->entity->username : '';
		$this->message->save();
	}
}
