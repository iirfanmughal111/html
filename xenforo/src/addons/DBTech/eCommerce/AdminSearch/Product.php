<?php

namespace DBTech\eCommerce\AdminSearch;

use XF\AdminSearch\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\AdminSearch
 */
class Product extends AbstractHandler
{
	/**
	 * @return int
	 */
	public function getDisplayOrder(): int
	{
		return 51;
	}

	/**
	 * @param string $text
	 * @param int $limit
	 * @param array $previousMatchIds
	 *
	 * @return \XF\Mvc\Entity\AbstractCollection|\XF\Mvc\Entity\ArrayCollection
	 */
	public function search($text, $limit, array $previousMatchIds = [])
	{
		$finder = $this->app->finder('DBTech\eCommerce:Product');

		$conditions = [
			['title', 'like', $finder->escapeLike($text, '%?%')],
			['description_full', 'like', $finder->escapeLike($text, '%?%')],
			['product_specification', 'like', $finder->escapeLike($text, '%?%')]
		];
		if ($previousMatchIds)
		{
			$conditions[] = ['product_id', $previousMatchIds];
		}

		$finder
			->whereOr($conditions)
			->orderForList()
			->limit($limit);

		return $finder->fetch();
	}

	/**
	 * @param \XF\Mvc\Entity\Entity $record
	 *
	 * @return array
	 */
	public function getTemplateData(Entity $record): array
	{
		/** @var \XF\Mvc\Router $router */
		$router = $this->app->container('router.admin');

		return [
			'link' => $router->buildLink('dbtech-ecommerce/products/edit', $record),
			'title' => $record->title
		];
	}

	/**
	 * @return bool
	 */
	public function isSearchable(): bool
	{
		return \XF::visitor()->hasAdminPermission('dbtechEcomProduct');
	}
}