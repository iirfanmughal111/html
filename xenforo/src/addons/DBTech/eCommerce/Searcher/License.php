<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * @method \DBTech\eCommerce\Finder\License getFinder()
 */
class License extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['User', 'Product'];

	/** @var array */
	protected $formats = [
		'username' => 'like',
		'purchase_date' => 'date',
		'expiry_date' => 'date',
	];

	/** @var array */
	protected $order = [['purchase_date', 'desc'], ['license_id', 'desc']];

	/**
	 * @return string
	 */
	protected function getEntityType(): string
	{
		return 'DBTech\eCommerce:License';
	}

	/**
	 * @return array
	 */
	protected function getDefaultOrderOptions(): array
	{
		$orders = [
			'purchase_date' => \XF::phrase('dbtech_ecommerce_purchase_date'),
			'User.username' => \XF::phrase('user_name'),
		];

		\XF::fire('dbtech_ecommerce_license_searcher_orders', [$this, &$orders]);

		return $orders;
	}

	/**
	 * @param $key
	 * @param $value
	 * @param $column
	 * @param $format
	 * @param $relation
	 *
	 * @return null
	 */
	protected function validateSpecialCriteriaValue($key, &$value, $column, $format, $relation)
	{
		if ($key == 'expiry_date')
		{
			if (empty($value['start']) && !empty($value['end']))
			{
				$value['start'] = 1;
			}
		}

		return null;
	}

	/**
	 * @param Finder $finder
	 * @param $key
	 * @param $value
	 * @param $column
	 * @param $format
	 * @param $relation
	 * @return bool
	 */
	protected function applySpecialCriteriaValue(Finder $finder, $key, $value, $column, $format, $relation): bool
	{
		if ($key == 'product_id' && $value == '_any')
		{
			return true;
		}
		
		if ($key == 'license_fields')
		{
			$exactMatchFields = !empty($value['exact']) ? $value['exact'] : [];
			$customFields = array_merge($value, $exactMatchFields);
			unset($customFields['exact']);

			$conditions = [];
			foreach ($customFields AS $fieldId => $value)
			{
				if ($value === '' || (is_array($value) && !$value))
				{
					continue;
				}

				$finder->with('LicenseFields|' . $fieldId);
				$isExact = !empty($exactMatchFields[$fieldId]);

				foreach ((array)$value AS $possible)
				{
					$columnName = 'LicenseFields|' . $fieldId . '.field_value';
					if ($isExact)
					{
						$conditions[] = [$columnName, '=', $possible];
					}
					else
					{
						$conditions[] = [$columnName, 'LIKE', $finder->escapeLike($possible, '%?%')];
					}
				}
			}
			if ($conditions)
			{
				$finder->whereOr($conditions);
			}

			return true;
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

	public function getFormDefaults(): array
	{
		return [
			'license_state' => ['visible', 'awaiting_payment', 'moderated', 'deleted'],
		];
	}
}