<?php

namespace XenAddons\Showcase\Service\Page;

use XenAddons\Showcase\Entity\ItemPage;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var ItemPage
	 */
	protected $itemPage;
	
	protected $attachmentHash;
	
	protected $logIp = true;

	public function __construct(\XF\App $app, ItemPage $itemPage)
	{
		parent::__construct($app);
		$this->setItemPage($itemPage);
	}
	
	public function setItemPage(ItemPage $itemPage)
	{
		$this->itemPage = $itemPage;
	}

	public function getItemPage()
	{
		return $this->itemPage;
	}
	
	public function logIp($logIp)
	{
		$this->logIp = $logIp;
	}
	
	public function setMessage($message, $format = true, $checkValidity = true)
	{
		$preparer = $this->getMessagePreparer($format);
		$this->itemPage->message = $preparer->prepare($message, $checkValidity);
		$this->itemPage->embed_metadata = $preparer->getEmbedMetadata();
	
		return $preparer->pushEntityErrorIfInvalid($this->itemPage);
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
		$preparer = $this->service('XF:Message\Preparer', 'sc_page', $this->itemPage);
		$preparer->setConstraint('maxLength', $options->xaScItemMaxLength);
		$preparer->setConstraint('maxImages', $maxImages);
		$preparer->setConstraint('maxMedia', $maxMedia);
	
		if (!$format)
		{
			$preparer->disableAllFilters();
		}
	
		return $preparer;
	}
	
	public function setAttachmentHash($hash)
	{
		$this->attachmentHash = $hash;
	}

	public function checkForSpam()
	{
		// TODO implement this at some point for pages! 
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
		
		$itemPage = $this->itemPage;
	}

	public function afterUpdate()
	{
		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}
		
		$this->updateCoverImageIfNeeded();
		
		$itemPage = $this->itemPage;
	}
	
	protected function associateAttachments($hash)
	{
		$itemPage = $this->itemPage;
	
		/** @var \XF\Service\Attachment\Preparer $inserter */
		$inserter = $this->service('XF:Attachment\Preparer');
		$associated = $inserter->associateAttachmentsWithContent($hash, 'sc_page', $itemPage->page_id);
	
		if ($associated)
		{
			$itemPage->fastUpdate('attach_count', $itemPage->attach_count + $associated);
		}
	}
	
	protected function updateCoverImageIfNeeded()
	{
		$itemPage = $this->itemPage;
		$attachments = $this->itemPage->Attachments;
	
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
	
		if (!$this->itemPage->cover_image_id)
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
					$itemPage->fastUpdate('cover_image_id', $coverImageId);
				}
			}
		}
		elseif ($this->itemPage->cover_image_id)
		{
			// Things to check/do if a cover image id is set
				
			if (!$imageAttachments)
			{
				// if there are no longer any image attachments, then there can't be a cover image, so set the cover image id to 0
	
				$itemPage->fastUpdate('cover_image_id',0);
			}
			elseif (array_key_exists($this->itemPage->cover_image_id, $imageAttachments))
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
					$itemPage->fastUpdate('cover_image_id', $coverImageId);
				}
			}
		}
	}	
	
	protected function writeIpLog($ip)
	{
		$itemPage = $this->itemPage;
		
		$item = $this->itemPage->Item;
	
		/** @var \XF\Repository\IP $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		$ipEnt = $ipRepo->logIp($item->user_id, $ip, 'sc_page', $itemPage->page_id);
		if ($ipEnt)
		{
			$itemPage->fastUpdate('ip_id', $ipEnt->ip_id);
		}
	}	
}