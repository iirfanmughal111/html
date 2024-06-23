<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * @method \DBTech\eCommerce\Finder\Address getFinder()
 */
class Address extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['User'];

	/** @var array */
	protected $formats = [
		'title' => 'like',
	];

	/** @var array */
	protected $order = [['address_id', 'DESC']];


	/**
	 * @return string
	 */
	protected function getEntityType(): string
	{
		return 'DBTech\eCommerce:Address';
	}

	/**
	 * @return array
	 */
	protected function getDefaultOrderOptions(): array
	{
		$orders = [
			'address_id' => \XF::phrase('dbtech_ecommerce_address_id'),
			'title' => \XF::phrase('title'),
		];

		\XF::fire('dbtech_ecommerce_address_searcher_orders', [$this, &$orders]);

		return $orders;
	}
	
	/**
	 * @param Finder $finder
	 * @param $key
	 * @param $value
	 * @param $column
	 * @param $format
	 * @param $relation
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	protected function applySpecialCriteriaValue(Finder $finder, $key, $value, $column, $format, $relation): bool
	{
		if ($key == 'has_orders')
		{
			$conditions = [];
			
			foreach ((array)$value AS $possible)
			{
				$conditions[] = ['order_count', $possible ? '>' : '=', 0];
			}
			
			if ($conditions)
			{
				$finder->whereOr($conditions);
			}
		}
		
		if ($key == 'has_sales_tax')
		{
			$conditions = [];
			
			foreach ((array)$value AS $possible)
			{
				$conditions[] = ['sales_tax_id', $possible ? '!=' : '=', ''];
			}
			
			if ($conditions)
			{
				$finder->whereOr($conditions);
			}
		}
		
		return false;
	}

	/**
	 * @return array
	 */
	public function getFormData(): array
	{
		return [];
	}
	
	/**
	 * @return array
	 */
	public function getFormDefaults(): array
	{
		return [
			'has_orders' => [0, 1],
			'has_sales_tax' => [0, 1],
			'is_guest' => [0, 1],
			'address_state' => ['visible', 'verified', 'moderated', 'deleted'],
		];
	}
}