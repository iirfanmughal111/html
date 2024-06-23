<?php

namespace DBTech\eCommerce\Service\ProductRating;

use DBTech\eCommerce\Entity\ProductRating;

/**
 * Class Editor
 *
 * @package DBTech\eCommerce\Service\ProductRating
 */
class Editor extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;
	
	/** @var \DBTech\eCommerce\Entity\ProductRating */
	protected $rating;
	
	/** @var bool */
	protected $reviewRequired = false;
	
	/** @var int */
	protected $reviewMinLength = 0;
	
	/** @var bool */
	protected $alert = false;

	/** @var string */
	protected $alertReason = '';
	
	/**
	 * Rate constructor.
	 *
	 * @param \XF\App $app
	 * @param ProductRating $rating
	 */
	public function __construct(\XF\App $app, ProductRating $rating)
	{
		parent::__construct($app);
		
		$this->rating = $rating;
		
		$this->reviewRequired = $this->app->options()->dbtechEcommerceReviewRequired;
		$this->reviewMinLength = $this->app->options()->dbtechEcommerceMinimumReviewLength;
	}
	
	/**
	 * @return \DBTech\eCommerce\Entity\ProductRating|\XF\Mvc\Entity\Entity
	 */
	public function getRating()
	{
		return $this->rating;
	}

	/**
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setMessage(string $message = ''): Editor
	{
		$this->rating->message = $message;

		return $this;
	}

	/**
	 * @param int $rating
	 *
	 * @return $this
	 */
	public function setRating(int $rating): Editor
	{
		$this->rating->rating = $rating;

		return $this;
	}

	/**
	 * @param bool $isAnonymous
	 *
	 * @return $this
	 */
	public function setIsAnonymous(bool $isAnonymous = true): Editor
	{
		$this->rating->is_anonymous = $isAnonymous;

		return $this;
	}

	/**
	 * @param bool|null $reviewRequired
	 * @param int|null $minLength
	 *
	 * @return $this
	 */
	public function setReviewRequirements(?bool $reviewRequired = null, ?int $minLength = null): Editor
	{
		if ($reviewRequired !== null)
		{
			$this->reviewRequired = $reviewRequired;
		}
		if ($minLength !== null)
		{
			$minLength = max(0, $minLength);
			$this->reviewMinLength = $minLength;
		}

		return $this;
	}

	/**
	 * @param array $customFields
	 */
	public function setCustomFields(array $customFields): void
	{
		$rating = $this->rating;
		$product = $rating->Product;

		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $rating->custom_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, 'user')
			->filterOnly($product->Category->review_field_cache);

		$customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

		if ($customFieldsShown)
		{
			$fieldSet->bulkSet($customFields, $customFieldsShown, 'user');
		}
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Editor
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}
	
	/**
	 *
	 */
	public function checkForSpam()
	{
		$rating = $this->rating;
		
		if (
			$this->rating->message === ''
			|| $this->rating->getErrors()
			|| !\XF::visitor()->isSpamCheckRequired()
		) {
			return;
		}
		
		/** @var \XF\Entity\User $user */
		$user = $rating->User;
		
		$message = $rating->message;
		
		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'permalink' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $rating->Product),
			'content_type' => 'dbtech_ecommerce_rating'
		]);
		
		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
			case 'denied':
				$checker->logSpamTrigger('dbtech_ecommerce_rating', null);
				$rating->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}
	
	/**
	 * @return array
	 */
	protected function _validate(): array
	{
		$rating = $this->rating;
		
		$rating->preSave();
		$errors = $rating->getErrors();
		
		if ($this->reviewRequired && !$rating->is_review)
		{
			$errors['message'] = \XF::phrase('dbtech_ecommerce_please_provide_review_with_your_rating');
		}
		
		if ($rating->is_review && \mb_strlen($rating->message) < $this->reviewMinLength)
		{
			$errors['message'] = \XF::phrase(
				'dbtech_ecommerce_your_review_must_be_at_least_x_characters',
				['min' => $this->reviewMinLength]
			);
		}
		
		if (!$rating->rating)
		{
			$errors['rating'] = \XF::phrase('dbtech_ecommerce_please_select_star_rating');
		}
		
		return $errors;
	}
	
	/**
	 * @return \DBTech\eCommerce\Entity\ProductRating|\XF\Mvc\Entity\Entity
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save()
	{
		$rating = $this->rating;
		$visitor = \XF::visitor();
		
		$rating->save(true, false);
		
		if ($rating->rating_state == 'visible' && $this->alert && $rating->user_id != $visitor->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\ProductRating $productRatingRepo */
			$productRatingRepo = $this->repository('DBTech\eCommerce:ProductRating');
			$productRatingRepo->sendModeratorActionAlert($rating, 'edit', $this->alertReason);
		}
		
		return $rating;
	}
}