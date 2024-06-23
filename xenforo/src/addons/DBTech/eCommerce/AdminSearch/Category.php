<?php

namespace DBTech\eCommerce\AdminSearch;

use XF\AdminSearch\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Category
 *
 * @package DBTech\eCommerce\AdminSearch
 */
class Category extends AbstractHandler
{
	/**
	 * @return int
	 */
	public function getDisplayOrder(): int
	{
		return 55;
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
		$finder = $this->app->finder('DBTech\eCommerce:Category');

		$conditions = [
			['title', 'like', $finder->escapeLike($text, '%?%')],
			['description', 'like', $finder->escapeLike($text, '%?%')]
		];
		if ($previousMatchIds)
		{
			$conditions[] = ['category_id', $previousMatchIds];
		}

		$finder
			->whereOr($conditions)
			->order('title')
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
			'link' => $router->buildLink('dbtech-ecommerce/categories/edit', $record),
			'title' => $record->title
		];
	}

	/**
	 * @return bool
	 */
	public function isSearchable(): bool
	{
		return \XF::visitor()->hasAdminPermission('dbtechEcomCategory');
	}
}