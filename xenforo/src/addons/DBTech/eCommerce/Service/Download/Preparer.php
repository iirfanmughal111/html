<?php

namespace DBTech\eCommerce\Service\Download;

use DBTech\eCommerce\Entity\Download;

/**
 * Class Preparer
 *
 * @package DBTech\eCommerce\Service\Download
 */
class Preparer extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Download */
	protected $download;
	
	/** @var string */
	protected $key;
	
	/** @var string */
	protected $attachmentHash;
	
	/** @var array */
	protected $mentionedUsers = [];
	
	/**
	 * Preparer constructor.
	 *
	 * @param \XF\App $app
	 * @param Download $download
	 * @param string $key
	 */
	public function __construct(\XF\App $app, Download $download, string $key)
	{
		parent::__construct($app);
		$this->download = $download;
		$this->key = $key;
	}
	
	/**
	 * @return Download
	 */
	public function getDownload(): Download
	{
		return $this->download;
	}
	
	/**
	 * @param bool $limitPermissions
	 *
	 * @return array
	 */
	public function getMentionedUsers(bool $limitPermissions = true): array
	{
		if ($limitPermissions)
		{
			/** @var \XF\Entity\User $user */
			$user = $this->download->Product->User ?: $this->repository('XF:User')->getGuestUser();
			return $user->getAllowedUserMentions($this->mentionedUsers);
		}
		
		return $this->mentionedUsers;
	}
	
	/**
	 * @param bool $limitPermissions
	 *
	 * @return array
	 */
	public function getMentionedUserIds(bool $limitPermissions = true): array
	{
		return array_keys($this->getMentionedUsers($limitPermissions));
	}
	
	/**
	 * @param string $message
	 * @param bool $format
	 * @param bool $checkValidity
	 * @param bool $allowEmpty
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	public function setMessage(
		string $message,
		bool $format = true,
		bool $checkValidity = true,
		bool $allowEmpty = false
	): bool {
		$preparer = $this->getMessagePreparer($format, $allowEmpty);
		$this->download->set($this->key, $preparer->prepare($message, $checkValidity));
		$this->download->embed_metadata = $preparer->getEmbedMetadata();

		$this->mentionedUsers = $preparer->getMentionedUsers();

		return $preparer->pushEntityErrorIfInvalid($this->download);
	}
	
	/**
	 * @param bool $format
	 * @param bool $allowEmpty
	 *
	 * @return \XF\Service\Message\Preparer
	 */
	protected function getMessagePreparer(bool $format = true, bool $allowEmpty = false): \XF\Service\Message\Preparer
	{
		$options = $this->app->options();

		if ($options->messageMaxLength)
		{
			$maxImages = $options->messageMaxImages;
			$maxMedia = $options->messageMaxMedia;
		}
		else
		{
			$maxImages = 100;
			$maxMedia = 30;
		}

		/** @var \XF\Service\Message\Preparer $preparer */
		$preparer = $this->service('XF:Message\Preparer', 'dbtech_ecommerce_download', $this->download);
		$preparer->setConstraint('maxLength', $options->messageMaxLength);
		$preparer->setConstraint('maxImages', $maxImages);
		$preparer->setConstraint('maxMedia', $maxMedia);
		$preparer->setConstraint('allowEmpty', $allowEmpty);

		if (!$format)
		{
			$preparer->disableAllFilters();
		}

		return $preparer;
	}

	/**
	 * @param string $hash
	 *
	 * @return $this
	 */
	public function setAttachmentHash(string $hash): Preparer
	{
		$this->attachmentHash = $hash;

		return $this;
	}

	/**
	 *
	 */
	public function checkForSpam()
	{
		$download = $this->download;
		$product = $download->Product;

		/** @var \XF\Entity\User $user */
		$user = $product->User ?: $this->repository('XF:User')->getGuestUser($product->username);

		$message = $download->title . "\n" . $download->get($this->key);

		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'permalink' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $product),
			'content_type' => 'dbtech_ecommerce_download'
		]);

		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
				$download->download_state = 'moderated';
				break;

			case 'denied':
				$checker->logSpamTrigger('dbtech_ecommerce_download', null);
				$download->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}
	
	public function beforeInsert()
	{
	}
	
	public function beforeUpdate()
	{
	}
	
	/**
	 * @throws \LogicException
	 */
	public function afterInsert()
	{
		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}
		
		$download = $this->download;
		$checker = $this->app->spam()->contentChecker();
		
		$checker->logContentSpamCheck('dbtech_ecommerce_download', $download->download_id);
		$checker->logSpamTrigger('dbtech_ecommerce_download', $download->download_id);
	}
	
	/**
	 * @throws \LogicException
	 */
	public function afterUpdate()
	{
		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}
		
		$download = $this->download;
		$checker = $this->app->spam()->contentChecker();
		
		$checker->logSpamTrigger('dbtech_ecommerce_download', $download->download_id);
	}
	
	/**
	 * @param string $hash
	 *
	 * @throws \LogicException
	 */
	protected function associateAttachments(string $hash)
	{
		$download = $this->download;

		/** @var \XF\Service\Attachment\Preparer $inserter */
		$inserter = $this->service('XF:Attachment\Preparer');
		$associated = $inserter->associateAttachmentsWithContent($hash, 'dbtech_ecommerce_download', $download->download_id);
		if ($associated)
		{
			$download->fastUpdate('attach_count', $download->attach_count + $associated);
		}
	}
}