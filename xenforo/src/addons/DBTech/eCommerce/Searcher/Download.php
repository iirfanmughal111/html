<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * @method \DBTech\eCommerce\Finder\Download getFinder()
 */
class Download extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['Product'];

	/** @var array */
	protected $formats = [
		'Product.title' => 'like',
		'release_date' => 'date',
	];

	/** @var array */
	protected $order = [['release_date', 'DESC']];

	/**
	 * @return string
	 */
	protected function getEntityType(): string
	{
		return 'DBTech\eCommerce:Download';
	}

	/**
	 * @return array
	 */
	protected function getDefaultOrderOptions(): array
	{
		$orders = [
			'release_date' => \XF::phrase('dbtech_ecommerce_release_date'),
			'Product.title' => \XF::phrase('dbtech_ecommerce_product'),
		];

		\XF::fire('dbtech_ecommerce_download_searcher_orders', [$this, &$orders]);

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
		if ($key == 'product_id')
		{
			if ($value == '_any')
			{
				return true;
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
			'download_state' => ['visible', 'moderated', 'deleted'],
		];
	}
}