<?php

namespace DBTech\eCommerce\Api\ControllerPlugin;

use XF\Api\ControllerPlugin\AbstractPlugin;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Api\ControllerPlugin
 */
class Product extends AbstractPlugin
{
	/**
	 * @param \DBTech\eCommerce\Finder\Product $productFinder
	 * @param \DBTech\eCommerce\Entity\Category|null $category
	 *
	 * @return array
	 */
	public function applyProductListFilters(\DBTech\eCommerce\Finder\Product $productFinder, \DBTech\eCommerce\Entity\Category $category = null): array
	{
		$filters = [];

		$prefixId = $this->filter('prefix_id', 'uint');
		if ($prefixId)
		{
			$productFinder->where('prefix_id', $prefixId);
			$filters['prefix_id'] = $prefixId;
		}

		$type = $this->filter('type', 'str');
		if ($type)
		{
			switch ($type)
			{
				case 'free':
					$productFinder->where('is_paid', false);
					$filters['type'] = 'free';
					break;

				case 'paid':
					$productFinder->where('is_paid', true);
					$filters['type'] = 'paid';
					break;
			}
		}

		$creatorId = $this->filter('creator_id', 'uint');
		if ($creatorId)
		{
			$productFinder->where('user_id', $creatorId);
			$filters['creator_id'] = $creatorId;
		}

		return $filters;
	}
	
	/**
	 * @param \DBTech\eCommerce\Finder\Product $productFinder
	 * @param \DBTech\eCommerce\Entity\Category|null $category
	 *
	 * @return array|null
	 */
	public function applyProductListSort(\DBTech\eCommerce\Finder\Product $productFinder, \DBTech\eCommerce\Entity\Category $category = null): ?array
	{
		$order = $this->filter('order', 'str');
		if (!$order)
		{
			return null;
		}

		$direction = $this->filter('direction', 'str');
		if ($direction !== 'asc')
		{
			$direction = 'desc';
		}

		switch ($order)
		{
			case 'last_update':
			case 'creation_date':
			case 'rating_weighted':
			case 'title':
				$productFinder->order('is_featured', 'desc');
				$productFinder->order($order, $direction);
				return [$order, $direction];
		}

		return null;
	}
}