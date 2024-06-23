<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class ProductRating
 *
 * @package DBTech\eCommerce\Finder
 */
class ProductRating extends Finder
{
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param array $limits
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function inProduct(\DBTech\eCommerce\Entity\Product $product, array $limits = []): ProductRating
	{
		$limits = array_replace([
			'visibility' => true
		], $limits);

		$this->where('product_id', $product->product_id);

		if ($limits['visibility'])
		{
			$this->applyVisibilityChecksInProduct($product);
		}

		return $this;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function applyVisibilityChecksInProduct(\DBTech\eCommerce\Entity\Product $product): ProductRating
	{
		$conditions = [];
		$viewableStates = ['visible'];

		if ($product->canViewDeletedContent())
		{
			$viewableStates[] = 'deleted';

			$this->with('DeletionLog');
		}

		$conditions[] = ['rating_state', $viewableStates];

		$this->whereOr($conditions);

		return $this;
	}
}