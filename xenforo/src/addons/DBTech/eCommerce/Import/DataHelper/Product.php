<?php

namespace DBTech\eCommerce\Import\DataHelper;

use XF\Import\DataHelper\AbstractHelper;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Import\DataHelper
 */
class Product extends AbstractHelper
{
	/**
	 * @param $productId
	 * @param array $forumIds
	 */
	public function importTicketForumIds($productId, array $forumIds)
	{
		foreach ($forumIds as $forumId)
		{
			$forum = $this->db()->fetchRow('SELECT * FROM xf_forum WHERE node_id = ?', $forumId);
			$forum['type_config']['product_id'] = $productId;

			$this->db()
				->update('xf_forum', [
					'type_config'    => \GuzzleHttp\json_encode($forum['type_config'])
				], 'node_id = ?', $forumId)
			;
		}
	}
	/**
	 * @param $productId
	 * @param $date
	 */
	public function addDefaultCost($productId, $date)
	{
		$this->db()->insert(
			'xf_dbtech_ecommerce_product_cost',
			[
				'product_id' => $productId,
				'creation_date' => $date,
				'cost_amount' => 0,
				'length_amount' => 0,
				'length_unit' => ''
			]
		);
	}
	
	/**
	 * @param $productId
	 * @param $userId
	 * @param bool $email
	 */
	public function importProductWatch($productId, $userId, $email = false)
	{
		$this->importProductWatchBulk($productId, [$userId => $email]);
	}
	
	/**
	 * @param $productId
	 * @param array $userConfigs
	 */
	public function importProductWatchBulk($productId, array $userConfigs)
	{
		$insert = [];

		foreach ($userConfigs AS $userId => $config)
		{
			if (is_scalar($config))
			{
				$config = ['email_subscribe' => (bool)$config];
			}

			$insert[] = [
				'user_id' => $userId,
				'product_id' => $productId,
				'email_subscribe' => empty($config['email_subscribe']) ? 0 : 1
			];
		}

		if ($insert)
		{
			$this->db()->insertBulk(
				'xf_dbtech_ecommerce_product_watch',
				$insert,
				false,
				'email_subscribe = VALUES(email_subscribe)'
			);
		}
	}
	
	/**
	 * @param $sourceFile
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function setIconFromFile($sourceFile, \DBTech\eCommerce\Entity\Product $product)
	{
		/** @var \DBTech\eCommerce\Service\Product\Icon $iconService */
		$iconService = $this->dataManager->app()->service('DBTech\eCommerce:Product\Icon', $product);
		$iconService->logIp(false);
		
		if ($iconService->setImage($sourceFile))
		{
			$iconService->updateIcon();
		}
	}
}