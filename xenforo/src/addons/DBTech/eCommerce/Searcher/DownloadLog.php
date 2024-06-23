<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * Class DownloadLog
 * @package DBTech\eCommerce\Searcher
 */
class DownloadLog extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['User', 'Ip', 'License'];

	/** @var array */
	protected $formats = [
		'username' => 'like',
		'log_date' => 'date',
	];

	/** @var array */
	protected $order = [['log_date', 'desc']];

	/**
	 * @return string
	 */
	protected function getEntityType(): string
	{
		return 'DBTech\eCommerce:DownloadLog';
	}

	/**
	 * @return array
	 */
	protected function getDefaultOrderOptions(): array
	{
		$orders = [
			'log_date' => \XF::phrase('date'),
			'User.username' => \XF::phrase('user_name'),
		];

		\XF::fire('dbtech_ecommerce_download_log_searcher_orders', [$this, &$orders]);

		return $orders;
	}

	/*
	protected function validateSpecialCriteriaValue($key, &$value, $column, $format, $relation)
	{
		if ($key == 'no_secondary_group_ids' && !$value)
		{
			return false;
		}

		return null;
	}
	*/

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
		if ($key == 'product_id')
		{
			if ($value == '_any')
			{
				return true;
			}
		}
		
		if ($key == 'download_log_fields')
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
				$finder->where($conditions);
			}

			return true;
		}

		if ($key == 'ip')
		{
			$parsed = \XF\Util\Ip::parseIpRangeString($value);

			if (!$parsed)
			{
				return true;
			}
			
			if ($parsed['isRange'])
			{
				$finder->where('Ip.ip', '>=', $parsed['startRange']);
				$finder->where('Ip.ip', '<=', $parsed['endRange']);
			}
			else
			{
				$finder->where('Ip.ip', $parsed['startRange']);
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

	/*
	public function getFormDefaults()
	{
		return [
			'user_state' => [
				'valid', 'email_confirm', 'email_confirm_edit', 'email_bounce', 'moderated', 'rejected', 'disabled'
			],
			'is_banned' => [0, 1],
			'is_staff' => [0, 1],
			'no_secondary_group_ids' => 0,
			'message_count' => ['end' => -1],
			'trophy_points' => ['end' => -1],
			'Option' => [
				'is_discouraged' => [0, 1],
			]
		];
	}
	*/
	
	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->em->getRepository('DBTech\eCommerce:Product');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Category|\XF\Mvc\Entity\Repository
	 */
	protected function getCategoryRepo()
	{
		return $this->em->getRepository('DBTech\eCommerce:Category');
	}
}