<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var Item
	 */
	protected $item;

	protected $attachmentHash;

	protected $logIp = true;

	protected $mentionedUsers = [];

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		$this->setItem($item);
	}
	
	public function setItem(Item $item)
	{
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function logIp($logIp)
	{
		$this->logIp = $logIp;
	}

	public function getMentionedUsers($limitPermissions = true)
	{
		if ($limitPermissions)
		{
			/** @var \XF\Entity\User $user */
			$user = $this->item->User ?: $this->repository('XF:User')->getGuestUser();
			return $user->getAllowedUserMentions($this->mentionedUsers);
		}
		else
		{
			return $this->mentionedUsers;
		}
	}

	public function getMentionedUserIds($limitPermissions = true)
	{
		return array_keys($this->getMentionedUsers($limitPermissions));
	}
	
	public function setMessage($message, $format = true, $checkValidity = true)
	{
		$preparer = $this->getMessagePreparer($format);
		$this->item->message = $preparer->prepare($message, $checkValidity);
		$this->item->embed_metadata = $preparer->getEmbedMetadata();

		$this->mentionedUsers = $preparer->getMentionedUsers();

		return $preparer->pushEntityErrorIfInvalid($this->item);
	}
	
	public function setMessageS2($message, $format = true, $checkValidity = true)
	{
		$preparer = $this->getMessagePreparer($format);
		$this->item->message_s2 = $preparer->prepare($message, $checkValidity);
	
		return $preparer->pushEntityErrorIfInvalid($this->item);
	}
	
	public function setMessageS3($message, $format = true, $checkValidity = true)
	{
		$preparer = $this->getMessagePreparer($format);
		$this->item->message_s3 = $preparer->prepare($message, $checkValidity);
	
		return $preparer->pushEntityErrorIfInvalid($this->item);
	}
	
	public function setMessageS4($message, $format = true, $checkValidity = true)
	{
		$preparer = $this->getMessagePreparer($format);
		$this->item->message_s4 = $preparer->prepare($message, $checkValidity);
	
		return $preparer->pushEntityErrorIfInvalid($this->item);
	}
	
	public function setMessageS5($message, $format = true, $checkValidity = true)
	{
		$preparer = $this->getMessagePreparer($format);
		$this->item->message_s5 = $preparer->prepare($message, $checkValidity);
	
		return $preparer->pushEntityErrorIfInvalid($this->item);
	}
	
	public function setMessageS6($message, $format = true, $checkValidity = true)
	{
		$preparer = $this->getMessagePreparer($format);
		$this->item->message_s6 = $preparer->prepare($message, $checkValidity);
	
		return $preparer->pushEntityErrorIfInvalid($this->item);
	}

	/**
	 * @param bool $format
	 *
	 * @return \XF\Service\Message\Preparer
	 */
	protected function getMessagePreparer($format = true)
	{
		$options = $this->app->options();

		if ($options->messageMaxLength && $options->xaScItemMaxLength)
		{
			$ratio = ceil($options->xaScItemMaxLength / $options->messageMaxLength);
			$maxImages = $options->messageMaxImages * $ratio;
			$maxMedia = $options->messageMaxMedia * $ratio;
		}
		else
		{
			$maxImages = 100;
			$maxMedia = 30;
		}

		/** @var \XF\Service\Message\Preparer $preparer */
		$preparer = $this->service('XF:Message\Preparer', 'sc_item', $this->item);
		$preparer->setConstraint('maxLength', $options->xaScItemMaxLength);
		$preparer->setConstraint('maxImages', $maxImages);
		$preparer->setConstraint('maxMedia', $maxMedia);

		if (!$format)
		{
			$preparer->disableAllFilters();
		}
		
		$preparer->setConstraint('allowEmpty', true);

		return $preparer;
	}

	public function setAttachmentHash($hash)
	{
		$this->attachmentHash = $hash;
	}

	public function checkForSpam()
	{
		$item = $this->item;

		/** @var \XF\Entity\User $user */
		$user = $item->User ?: $this->repository('XF:User')->getGuestUser($item->username);

		$message = $item->title . "\n" . $item->message;

		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'permalink' => $this->app->router('public')->buildLink('canonical:showcase', $item),
			'content_type' => 'sc_item'
		]);

		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
				$item->item_state = 'moderated';
				break;

			case 'denied':
				$checker->logSpamTrigger('sc_item', null);
				$item->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}

	public function validateFiles(&$error = null)
	{
		$item = $this->item;
		$category = $item->Category;
		if (!$category)
		{
			throw new \LogicException("Could not find category for item");
		}
		
		if ($this->attachmentHash && $category->require_item_image)
		{
			$totalTempHashImageAttachments = $this->finder('XF:Attachment')
				->with('Data', true)
				->where('temp_hash', $this->attachmentHash)
				->where('Data.width', '>', 0)
				->total();

			$totalItemImageAttachments = $this->finder('XF:Attachment')
				->with('Data', true)
				->where('content_type', 'sc_item')
				->where('content_id', $item->item_id)
				->where('Data.width', '>', 0)
				->total();
			
			$totalImageAttachments = $totalTempHashImageAttachments + $totalItemImageAttachments;
			
			if (!$totalImageAttachments)
			{
				$error = \XF::phrase('xa_sc_you_must_upload_at_least_one_image_attachment');
				return false;
			}
		}
	
		return true;
	}
	
	public function afterInsert()
	{
		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}
		
		$this->updateCoverImageIfNeeded();

		if ($this->logIp)
		{
			$ip = ($this->logIp === true ? $this->app->request()->getIp() : $this->logIp);
			$this->writeIpLog($ip);
		}

		$item = $this->item;
		$checker = $this->app->spam()->contentChecker();

		$checker->logContentSpamCheck('sc_item', $item->item_id);
		$checker->logSpamTrigger('sc_item', $item->item_id);

	}

	public function afterUpdate()
	{
		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}

		$this->updateCoverImageIfNeeded();
		
		$item = $this->item;
		$checker = $this->app->spam()->contentChecker();

		$checker->logSpamTrigger('sc_item', $item->item_id);
	}
	
	protected function associateAttachments($hash)
	{
		$item = $this->item;
	
		/** @var \XF\Service\Attachment\Preparer $inserter */
		$inserter = $this->service('XF:Attachment\Preparer');
		$associated = $inserter->associateAttachmentsWithContent($hash, 'sc_item', $item->item_id);
	
		if ($associated)
		{
			$item->fastUpdate('attach_count', $item->attach_count + $associated);
		}
	}	
	
	protected function updateCoverImageIfNeeded()
	{
		$item = $this->item;
		$attachments = $this->item->Attachments;
	
		$imageAttachments = [];
		$fileAttachments = [];
	
		foreach ($attachments AS $key => $attachment)
		{
			if ($attachment['thumbnail_url'])
			{
				$imageAttachments[$key] = $attachment;
			}
			else
			{
				$fileAttachments[$key] = $attachment;
			}
		}
	
		if (!$this->item->cover_image_id) 
		{
			// Things to do if no cover image id is set
			
			if ($imageAttachments)
			{
				foreach ($imageAttachments AS $imageAttachment)
				{
					$coverImageId = $imageAttachment['attachment_id'];
					break;
				}
	
				if ($coverImageId)
				{
					$item->fastUpdate('cover_image_id', $coverImageId);
				}
			}
		}
		elseif ($this->item->cover_image_id) 
		{
			// Things to check/do if a cover image id is set
			
			if (!$imageAttachments) 
			{
				// if there are no longer any image attachments, then there can't be a cover image, so set the cover image id to 0
				
				$item->fastUpdate('cover_image_id',0);
			}
			elseif (array_key_exists($this->item->cover_image_id, $imageAttachments))
			{
				// do nothing as the cover image exists. 
			}
			else
			{
				// if it gets to this point, lets set the first attachment as the cover image id since the old cover image has been removed!
				
				foreach ($imageAttachments AS $imageAttachment)
				{
					$coverImageId = $imageAttachment['attachment_id'];
					break;
				}
				
				if ($coverImageId)
				{
					$item->fastUpdate('cover_image_id', $coverImageId);
				}				
			}
		}
	}	

	protected function writeIpLog($ip)
	{
		$item = $this->item;

		/** @var \XF\Repository\IP $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		$ipEnt = $ipRepo->logIp($item->user_id, $ip, 'sc_item', $item->item_id);
		if ($ipEnt)
		{
			$item->fastUpdate('ip_id', $ipEnt->ip_id);
		}
	}
}