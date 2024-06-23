<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;
use DBTech\eCommerce\Entity\ProductRating;

/**
 * Class Rate
 *
 * @package DBTech\eCommerce\Service\Product
 */
class Rate extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;

	/** @var \DBTech\eCommerce\Entity\ProductRating */
	protected $rating;

	/** @var bool */
	protected $reviewRequired = false;

	/** @var int */
	protected $reviewMinLength = 0;

	/** @var bool */
	protected $sendAlert = true;


	/**
	 * Rate constructor.
	 *
	 * @param \XF\App $app
	 * @param Product $product
	 *
	 * @throws \Exception
	 * @throws \Exception
	 */
	public function __construct(\XF\App $app, Product $product)
	{
		parent::__construct($app);

		$this->product = $product;
		$this->rating = $this->setupRating();

		$this->reviewRequired = $this->app->options()->dbtechEcommerceReviewRequired;
		$this->reviewMinLength = $this->app->options()->dbtechEcommerceMinimumReviewLength;
	}

	/**
	 * @return \XF\Mvc\Entity\Entity
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupRating()
	{
		$product = $this->product;

		/** @var \DBTech\eCommerce\Entity\ProductRating $rating */
		$rating = $this->em()->create('DBTech\eCommerce:ProductRating');
		$rating->product_id = $product->product_id;
		$rating->user_id = \XF::visitor()->user_id;
		$rating->version_string = ($product->hasDownloadFunctionality() && $product->LatestVersion) ? $product->LatestVersion->version_string : 'N/A';

		return $rating;
	}

	/**
	 * @return Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\ProductRating
	 */
	public function getRating(): ProductRating
	{
		return $this->rating;
	}

	/**
	 * @param int $rating
	 * @param string $message
	 *
	 * @return $this
	 */
	public function setRating(int $rating, string $message = ''): Rate
	{
		$this->rating->rating = $rating;
		$this->rating->message = $message;

		return $this;
	}

	/**
	 * @param bool $isAnonymous
	 *
	 * @return $this
	 */
	public function setIsAnonymous(bool $isAnonymous = true): Rate
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
	public function setReviewRequirements(?bool $reviewRequired = null, ?int $minLength = null): Rate
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
		$product = $this->product;
		$rating = $this->rating;

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
			'permalink'    => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $rating->Product),
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

		$existing = $this->product->Ratings[$rating->user_id];
		if ($existing)
		{
			$existing->delete();
		}

		$rating->save(true, false);

		if ($this->sendAlert)
		{
			/** @var \DBTech\eCommerce\Repository\ProductRating $productRatingRepo */
			$productRatingRepo = $this->repository('DBTech\eCommerce:ProductRating');
			$productRatingRepo->sendReviewAlertToProductAuthor($rating);
		}

		return $rating;
	}
}