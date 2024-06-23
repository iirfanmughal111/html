<?php

namespace DBTech\eCommerce\Service\ProductRating;

use DBTech\eCommerce\Entity\ProductRating;

/**
 * Class AuthorReply
 *
 * @package DBTech\eCommerce\Service\ProductRating
 */
class AuthorReply extends \XF\Service\AbstractService
{
	/** @var ProductRating */
	protected $rating;

	/** @var bool  */
	protected $sendAlert = true;
	
	
	/**
	 * AuthorReply constructor.
	 *
	 * @param \XF\App $app
	 * @param ProductRating $rating
	 */
	public function __construct(\XF\App $app, ProductRating $rating)
	{
		parent::__construct($app);
		$this->rating = $rating;
	}
	
	/**
	 * @return ProductRating
	 */
	public function getRating(): ProductRating
	{
		return $this->rating;
	}
	
	/**
	 * @param string $message
	 * @param null $error
	 *
	 * @return bool
	 * @throws \XF\PrintableException
	 */
	public function reply(string $message, &$error = null): bool
	{
		if (!$message)
		{
			$error = \XF::phrase('please_enter_valid_message');
			return false;
		}

		$hasExistingResponse = ($this->rating->author_response ? true : false);

		$this->rating->author_response = $message;
		$this->rating->save();

		if (!$hasExistingResponse && $this->sendAlert)
		{
			/** @var \DBTech\eCommerce\Repository\ProductRating $ratingRepo */
			$ratingRepo = $this->repository('DBTech\eCommerce:ProductRating');
			$ratingRepo->sendAuthorReplyAlert($this->rating);
		}

		return true;
	}
}