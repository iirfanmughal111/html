<?php

namespace DBTech\eCommerce\AdminSearch;

use XF\AdminSearch\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\AdminSearch
 */
class Download extends AbstractHandler
{
	/**
	 * @return int
	 */
	public function getDisplayOrder(): int
	{
		return 54;
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
		$finder = $this->app->finder('DBTech\eCommerce:Download');

		$conditions = [
			['Product.title', 'like', $finder->escapeLike($text, '%?%')],
			['version_string', 'like', $finder->escapeLike($text, '%?%')],
			['change_log', 'like', $finder->escapeLike($text, '%?%')],
			['release_notes', 'like', $finder->escapeLike($text, '%?%')]
		];
		if ($previousMatchIds)
		{
			$conditions[] = ['download_id', $previousMatchIds];
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
			'link' => $router->buildLink('dbtech-ecommerce/downloads/edit', $record),
			'title' => $record->title
		];
	}

	/**
	 * @return bool
	 */
	public function isSearchable(): bool
	{
		return \XF::visitor()->hasAdminPermission('dbtechEcomDownload');
	}
}