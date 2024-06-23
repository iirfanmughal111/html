<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Download
 * @package DBTech\eCommerce\Finder
 */
class Download extends Finder
{
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param array $limits
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function inProduct(\DBTech\eCommerce\Entity\Product $product, array $limits = []): Download
	{
		$limits = array_replace([
			'visibility' => true,
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
	public function applyVisibilityChecksInProduct(\DBTech\eCommerce\Entity\Product $product): Download
	{
		$conditions = [];
		$viewableStates = ['visible'];

		if ($product->canViewDeletedContent())
		{
			$viewableStates[] = 'deleted';

			$this->with('DeletionLog');
		}

		if ($product->canViewModeratedContent())
		{
			$viewableStates[] = 'moderated';
		}

		$conditions[] = ['Product.product_state', $viewableStates];

		$this->whereOr($conditions);
		$this->where(['download_state', $viewableStates]);

		return $this;
	}
	
	/**
	 * @param null $userId
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function watchedOnly($userId = null): Download
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		if (!$userId)
		{
			// no user, just ignore
			return $this;
		}
		
		$this->whereOr(
			['Product.Watch|' . $userId . '.user_id', '!=', null],
			['Product.Category.Watch|' . $userId . '.user_id', '!=', null]
		);
		
		return $this;
	}

	/**
	 * @param $productId
	 * @return $this
	 */
	public function fromProduct($productId): Download
	{
		if ($productId == '_any')
		{
			return $this;
		}
		$this->where('product_id', $productId);
		return $this;
	}
	
	/**
	 * @param string $match
	 * @param bool $caseSensitive
	 * @param bool $prefixMatch
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function searchText(
		string $match,
		bool $caseSensitive = false,
		bool $prefixMatch = false
	): Download {
		if ($match)
		{
//			$expression = 'Product.MasterTitle.phrase_text';
			$expression = 'Product.title';
			if ($caseSensitive)
			{
				$expression = $this->expression('BINARY %s', $expression);
			}

			$conditions = [
				[$expression, 'LIKE', $this->escapeLike($match, $prefixMatch ? '?%' : '%?%')],
				['version_string', 'LIKE', $this->escapeLike($match[0] == 'v' ? substr($match, 1) : $match, $prefixMatch ? '?%' : '%?%')]
			];

			$this->whereOr($conditions);
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function orderForList(): Download
	{
		$this->order(['release_date', 'DESC']);

		$this->order('Product.parent_product_id');

		$this->orderTitle();

		return $this;
	}

	/**
	 * @param string $direction
	 * @return $this
	 */
	public function orderTitle(string $direction = 'ASC'): Download
	{
//		$expression = $this->columnUtf8('MasterTitle.phrase_text');
		$expression = $this->columnUtf8('Product.title');
		$this->order($expression, $direction);

		return $this;
	}
}