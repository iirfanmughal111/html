<?php

namespace XenAddons\Showcase\Service\Review;

use XenAddons\Showcase\Entity\ItemRating;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var ItemRating
	 */
	protected $itemRating;

	protected $attachmentHash;

	protected $logIp = true;

	protected $mentionedUsers = [];

	public function __construct(\XF\App $app, ItemRating $itemRating)
	{
		parent::__construct($app);
		$this->setItemRating($itemRating);
	}
	
	public function setItemRating(ItemRating $itemRating)
	{
		$this->itemRating = $itemRating;
	}

	public function getItemRating()
	{
		return $this->itemRating;
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
			$user = $this->itemRating->User ?: $this->repository('XF:User')->getGuestUser();
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
		$this->itemRating->message = $preparer->prepare($message, $checkValidity);
		$this->itemRating->embed_metadata = $preparer->getEmbedMetadata();

		$this->mentionedUsers = $preparer->getMentionedUsers();

		return $preparer->pushEntityErrorIfInvalid($this->itemRating);
	}

	/**
	 * @param bool $format
	 *
	 * @return \XF\Service\Message\Preparer
	 */
	protected function getMessagePreparer($format = true)
	{
		$options = $this->app->options();

		if ($options->messageMaxLength && $options->xaScReviewMaxLength)
		{
			$ratio = ceil($options->xaScReviewMaxLength / $options->messageMaxLength);
			$maxImages = $options->messageMaxImages * $ratio;
			$maxMedia = $options->messageMaxMedia * $ratio;
		}
		else
		{
			$maxImages = 100;
			$maxMedia = 30;
		}

		/** @var \XF\Service\Message\Preparer $preparer */
		$preparer = $this->service('XF:Message\Preparer', 'sc_rating', $this->itemRating);
		$preparer->setConstraint('maxLength', $options->xaScReviewMaxLength);
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
		$itemRating = $this->itemRating;

		/** @var \XF\Entity\User $user */
		$user = $itemRating->User ?: $this->repository('XF:User')->getGuestUser($itemRating->username);

		$message = $itemRating->title . "\n" . $itemRating->message;

		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'permalink' => $this->app->router('public')->buildLink('canonical:showcase', $itemRating),
			'content_type' => 'sc_item'
		]);

		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
				$itemRating->rating_state = 'moderated';
				break;

			case 'denied':
				$checker->logSpamTrigger('sc_item', null);
				$itemRating->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}

	public function afterInsert()
	{
		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}

		if ($this->logIp)
		{
			$ip = ($this->logIp === true ? $this->app->request()->getIp() : $this->logIp);
			$this->writeIpLog($ip);
		}

		$itemRating = $this->itemRating;
		$checker = $this->app->spam()->contentChecker();

		$checker->logContentSpamCheck('sc_rating', $itemRating->rating_id);
		$checker->logSpamTrigger('sc_rating', $itemRating->rating_id);

	}

	public function afterUpdate()
	{
		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}
		
		$itemRating = $this->itemRating;
		$checker = $this->app->spam()->contentChecker();

		$checker->logSpamTrigger('sc_rating', $itemRating->rating_id);
	}
	
	protected function associateAttachments($hash)
	{
		$itemRating = $this->itemRating;
	
		/** @var \XF\Service\Attachment\Preparer $inserter */
		$inserter = $this->service('XF:Attachment\Preparer');
		$associated = $inserter->associateAttachmentsWithContent($hash, 'sc_rating', $itemRating->rating_id);
	
		if ($associated)
		{
			$itemRating->fastUpdate('attach_count', $itemRating->attach_count + $associated);
		}
	}	

	protected function writeIpLog($ip)
	{
		$itemRating = $this->itemRating;

		/** @var \XF\Repository\IP $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		$ipEnt = $ipRepo->logIp($itemRating->user_id, $ip, 'sc_rating', $itemRating->rating_id);
		if ($ipEnt)
		{
			$itemRating->fastUpdate('ip_id', $ipEnt->ip_id);
		}
	}
}