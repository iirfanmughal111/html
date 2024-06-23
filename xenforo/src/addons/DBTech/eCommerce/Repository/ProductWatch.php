<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ProductWatch
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductWatch extends Repository
{
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \XF\Entity\User $user
	 * @param bool $onCreation
	 *
	 * @return null|\XF\Mvc\Entity\Entity
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function autoWatchProduct(\DBTech\eCommerce\Entity\Product $product, \XF\Entity\User $user, $onCreation = false)
	{
		$userField = $onCreation ? 'creation_watch_state' : 'interaction_watch_state';

		if (!$product->product_id || !$user->user_id || !$user->Option->getValue($userField))
		{
			return null;
		}

		$watch = $this->em->find('DBTech\eCommerce:ProductWatch', [
			'product_id' => $product->product_id,
			'user_id' => $user->user_id
		]);
		if ($watch)
		{
			return null;
		}

		/** @var \DBTech\eCommerce\Entity\ProductWatch $watch */
		$watch = $this->em->create('DBTech\eCommerce:ProductWatch');
		$watch->product_id = $product->product_id;
		$watch->user_id = $user->user_id;
		$watch->email_subscribe = ($user->Option->getValue($userField) == 'watch_email');

		try
		{
			$watch->save();
		}
		/** @noinspection PhpRedundantCatchClauseInspection */
		catch (\XF\Db\DuplicateKeyException $e)
		{
			return null;
		}

		return $watch;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \XF\Entity\User $user
	 * @param string $action
	 * @param array $config
	 *
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function setWatchState(
		\DBTech\eCommerce\Entity\Product $product,
		\XF\Entity\User $user,
		string $action,
		array $config = []
	) {
		if (!$product->product_id || !$user->user_id)
		{
			throw new \InvalidArgumentException('Invalid product or user');
		}

		$watch = $this->em->find('DBTech\eCommerce:ProductWatch', [
			'product_id' => $product->product_id,
			'user_id' => $user->user_id
		]);

		switch ($action)
		{
			case 'watch':
				if (!$watch)
				{
					$watch = $this->em->create('DBTech\eCommerce:ProductWatch');
					$watch->product_id = $product->product_id;
					$watch->user_id = $user->user_id;
				}
				unset($config['product_id'], $config['user_id']);

				$watch->bulkSet($config);
				$watch->save();
				break;

			case 'update':
				if ($watch)
				{
					unset($config['product_id'], $config['user_id']);

					$watch->bulkSet($config);
					$watch->save();
				}
				break;

			case 'delete':
				if ($watch)
				{
					$watch->delete();
				}
				break;

			default:
				throw new \InvalidArgumentException("Unknown action '$action' (expected: delete/watch)");
		}
	}
	
	/**
	 * @param \XF\Entity\User $user
	 * @param string $action
	 * @param array $updates
	 *
	 * @return int
	 * @throws \InvalidArgumentException
	 */
	public function setWatchStateForAll(\XF\Entity\User $user, string $action, array $updates = []): int
	{
		if (!$user->user_id)
		{
			throw new \InvalidArgumentException('Invalid user');
		}

		$db = $this->db();

		switch ($action)
		{
			case 'update':
				unset($updates['product_id'], $updates['user_id']);
				return $db->update('xf_dbtech_ecommerce_product_watch', $updates, 'user_id = ?', $user->user_id);

			case 'delete':
				return $db->delete('xf_dbtech_ecommerce_product_watch', 'user_id = ?', $user->user_id);

			default:
				throw new \InvalidArgumentException("Unknown action '$action'");
		}
	}
	
	/**
	 * @param string $state
	 *
	 * @return bool
	 */
	public function isValidWatchState(string $state): bool
	{
		switch ($state)
		{
			case 'watch':
			case 'update':
			case 'delete':

			default:
				return false;
		}
	}
}