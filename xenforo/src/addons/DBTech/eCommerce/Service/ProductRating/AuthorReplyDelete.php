<?php

namespace DBTech\eCommerce\Service\ProductRating;

use DBTech\eCommerce\Entity\ProductRating;

/**
 * Class AuthorReplyDelete
 *
 * @package DBTech\eCommerce\Service\ProductRating
 */
class AuthorReplyDelete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\ProductRating */
	protected $rating;
	
	
	/**
	 * AuthorReplyDelete constructor.
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
	 * @return \DBTech\eCommerce\Entity\ProductRating
	 */
	public function getRating(): ProductRating
	{
		return $this->rating;
	}
	
	/**
	 * @return bool
	 * @throws \XF\PrintableException
	 */
	public function delete(): bool
	{
		if ($this->rating->author_response === '')
		{
			return false;
		}

		$this->rating->author_response = '';
		$this->rating->save();

		return true;
	}
}