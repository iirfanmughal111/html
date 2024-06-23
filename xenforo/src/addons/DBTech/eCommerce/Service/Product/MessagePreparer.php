<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class Preparer
 *
 * @package DBTech\eCommerce\Service\Product
 */
class MessagePreparer extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;
	
	/** @var \DBTech\eCommerce\Service\Product\Create|\DBTech\eCommerce\Service\Product\Edit */
	protected $service;
	
	/** @var string */
	protected $key;
	
	/** @var array */
	protected $mentionedUsers = [];
	
	
	/**
	 * MessagePreparer constructor.
	 *
	 * @param \XF\App $app
	 * @param Product $product
	 * @param string $key
	 * @param \XF\Service\AbstractService $service
	 */
	public function __construct(\XF\App $app, Product $product, string $key, \XF\Service\AbstractService $service)
	{
		parent::__construct($app);
		$this->product = $product;
		$this->key = $key;
		$this->service = $service;
	}
	
	/**
	 * @return Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
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
			$user = $this->product->User ?: $this->repository('XF:User')->getGuestUser();
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
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	public function setMessage(string $message, bool $format = true, bool $checkValidity = true): bool
	{
		$preparer = $this->getMessagePreparer($format);
		$this->product->set($this->key, $preparer->prepare($message, $checkValidity));
//		$this->product->embed_metadata = $preparer->getEmbedMetadata();

		$this->mentionedUsers = $preparer->getMentionedUsers();

		return $preparer->pushEntityErrorIfInvalid($this->product);
	}

	/**
	 * @param bool $format
	 *
	 * @return \XF\Service\Message\Preparer
	 */
	protected function getMessagePreparer(bool $format = true): \XF\Service\Message\Preparer
	{
		$options = $this->app->options();

		// If we have a message length, then set the image/media limit based on that.
		// Otherwise, place very high limits on each that are unlikely to ever legitimately be hit.
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
		$preparer = $this->service('XF:Message\Preparer', 'dbtech_ecommerce_product', $this->product);
		$preparer->setConstraint('maxLength', $options->messageMaxLength);
		$preparer->setConstraint('maxImages', $maxImages);
		$preparer->setConstraint('maxMedia', $maxMedia);

		if (!$format)
		{
			$preparer->disableAllFilters();
		}

		return $preparer;
	}
	
	/**
	 *
	 */
	public function checkForSpam()
	{
		$product = $this->product;

		/** @var \XF\Entity\User $user */
		$user = $product->User ?: $this->repository('XF:User')->getGuestUser($product->username);

		$message = $product->title . "\n" .
			$this->service->getTagline() . "\n" .
			$this->service->getDescription() . "\n" .
			$product->get($this->key);

		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'permalink' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $product),
			'content_type' => 'dbtech_ecommerce_product'
		]);

		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
				$product->product_state = 'moderated';
				break;

			case 'denied':
				$checker->logSpamTrigger('dbtech_ecommerce_product', null);
				$product->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}
	
	/**
	 *
	 */
	public function beforeInsert()
	{
	}
	
	/**
	 *
	 */
	public function beforeUpdate()
	{
	}
	
	/**
	 *
	 */
	public function afterInsert()
	{
	}
	
	/**
	 *
	 */
	public function afterUpdate()
	{
	}
}