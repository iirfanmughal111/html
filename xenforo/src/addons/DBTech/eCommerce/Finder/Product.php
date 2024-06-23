<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Product
 * @package DBTech\eCommerce\Finder
 */
class Product extends Finder
{
	/**
	 * @param bool $allowOwnPending
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function applyGlobalVisibilityChecks($allowOwnPending = false): Product
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		$conditions = [];
		$viewableStates = ['visible'];
		
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
				'product_state' => 'moderated',
				'user_id' => $visitor->user_id
			];
		}
		
		$conditions[] = ['product_state', $viewableStates];
		
		$this->whereOr($conditions);
		
		return $this;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 * @param bool $allowOwnPending
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function applyVisibilityChecksInCategory(\DBTech\eCommerce\Entity\Category $category, $allowOwnPending = false): Product
	{
		$conditions = [];
		$viewableStates = ['visible'];
		
		if ($category->canViewDeletedProducts())
		{
			$viewableStates[] = 'deleted';
			
			$this->with('DeletionLog');
		}
		
		$visitor = \XF::visitor();
		if ($category->canViewModeratedProducts())
		{
			$viewableStates[] = 'moderated';
		}
		elseif ($visitor->user_id && $allowOwnPending)
		{
			$conditions[] = [
				'product_state' => 'moderated',
				'user_id' => $visitor->user_id
			];
		}
		
		$conditions[] = ['product_state', $viewableStates];
		
		$this->whereOr($conditions);
		
		return $this;
	}
	
	/**
	 * @param null $userId
	 *
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function watchedOnly($userId = null): Product
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
			['Watch|' . $userId . '.user_id', '!=', null],
			['Category.Watch|' . $userId . '.user_id', '!=', null]
		);
		
		return $this;
	}
	
	/**
	 * @param string $match
	 * @param bool $caseSensitive
	 * @param bool $prefixMatch
	 * @param bool $exactMatch
	 *
	 * @return $this
	 */
	public function searchText(
		string $match,
		bool $caseSensitive = false,
		bool $prefixMatch = false,
		bool $exactMatch = false
	): Product {
		if ($match)
		{
//			$expression = 'MasterTitle.phrase_text';
			$expression = 'title';
			if ($caseSensitive)
			{
				$expression = $this->expression('BINARY %s', $expression);
			}

			if ($exactMatch)
			{
				$this->where($expression, $match);
			}
			else
			{
				$this->where($expression, 'LIKE', $this->escapeLike($match, $prefixMatch ? '?%' : '%?%'));
			}
		}

		return $this;
	}
	
	/**
	 * @return $this
	 */
	public function orderForList(): Product
	{
		$this->order('parent_product_id');
		
		$this->orderTitle();
		
		return $this;
	}
	
	/**
	 * @param string $direction
	 * @return $this
	 */
	public function orderTitle(string $direction = 'ASC'): Product
	{
//		$expression = $this->columnUtf8('MasterTitle.phrase_text');
		$expression = $this->columnUtf8('title');
		$this->order($expression, $direction);

		return $this;
	}
	
	/**
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function useDefaultOrder(): Product
	{
		$defaultOrder = $this->app()->options()->dbtechEcommerceListDefaultOrder ?: 'last_update';
		$defaultDir = $defaultOrder == 'title' ? 'asc' : 'desc';

		if ($defaultOrder == 'random')
		{
			$this->setDefaultOrder([['is_featured', 'DESC'], $this->expression('RAND()')]);
		}
		else
		{
			$this->setDefaultOrder([['is_featured', 'DESC'], [$defaultOrder, $defaultDir]]);
		}

		return $this;
	}
}