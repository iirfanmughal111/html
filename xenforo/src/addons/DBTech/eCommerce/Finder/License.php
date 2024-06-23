<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class License
 * @package DBTech\eCommerce\Finder
 */
class License extends Finder
{
	/**
	 * @param bool $allowOwnPending
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function applyGlobalVisibilityChecks($allowOwnPending = false): License
	{
		$visitor = \XF::visitor();
		$conditions = [];
		$viewableStates = ['visible', 'awaiting_payment'];
		
		if ($visitor->hasPermission('dbtechEcommerce', 'viewDeleted'))
		{
			$viewableStates[] = 'deleted';
			
			$this->with('DeletionLog');
		}
		
		if ($visitor->hasPermission('dbtechEcommerce', 'viewModerated'))
		{
			$viewableStates[] = 'moderated';
		}
		elseif ($visitor->user_id && $allowOwnPending)
		{
			$conditions[] = [
				'license_state' => 'moderated',
				'user_id' => $visitor->user_id
			];
		}
		
		$conditions[] = ['license_state', $viewableStates];
		
		$this->whereOr($conditions);
		
		return $this;
	}
	
	/**
	 * @param $productId
	 * @return $this
	 */
	public function fromProduct($productId): License
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
	): License {
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
				['license_key', 'LIKE', $this->escapeLike($match, $prefixMatch ? '?%' : '%?%')]
			];

			$this->whereOr($conditions);
		}

		return $this;
	}
	
	/**
	 * @return $this
	 */
	public function orderForList(): License
	{
		$this->order(['purchase_date', 'DESC']);
		
		$this->order('Product.parent_product_id');
		
		$this->orderTitle();
		
		return $this;
	}

	/**
	 * @param string $direction
	 * @return $this
	 */
	public function orderTitle(string $direction = 'ASC'): License
	{
//		$expression = $this->columnUtf8('Product.MasterTitle.phrase_text');
		$expression = $this->columnUtf8('Product.title');
		$this->order($expression, $direction);

		return $this;
	}
}