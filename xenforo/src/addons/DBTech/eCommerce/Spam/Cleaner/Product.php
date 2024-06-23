<?php

namespace DBTech\eCommerce\Spam\Cleaner;

use XF\Spam\Cleaner\AbstractHandler;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Spam\Cleaner
 */
class Product extends AbstractHandler
{
	/**
	 * @param array $options
	 *
	 * @return bool
	 */
	public function canCleanUp(array $options = []): bool
	{
		return !empty($options['action_threads']);
	}
	
	/**
	 * @param array $log
	 * @param null $error
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function cleanUp(array &$log, &$error = null): bool
	{
		$app = \XF::app();

		$products = $app->finder('DBTech\eCommerce:Product')
			->where('user_id', $this->user->user_id)
			->fetch();

		if ($products->count())
		{
			$submitter = $app->container('spam.contentSubmitter');
			$submitter->submitSpam('dbtech_ecommerce_product', $products->keys());

			$deleteType = $app->options()->spamMessageAction == 'delete' ? 'hard' : 'soft';

			$log['dbtech_ecommerce_product'] = [
				'deleteType' => $deleteType,
				'productIds' => []
			];

			foreach ($products AS $productId => $product)
			{
				$log['dbtech_ecommerce_product']['productIds'][] = $productId;

				/** @var \DBTech\eCommerce\Entity\Product $product */
				$product->setOption('log_moderator', false);
				if ($deleteType == 'soft')
				{
					$product->softDelete();
				}
				else
				{
					$product->delete();
				}
			}
		}

		return true;
	}
	
	/**
	 * @param array $log
	 * @param null $error
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function restore(array $log, &$error = null): bool
	{
		if ($log['deleteType'] == 'soft')
		{
			$products = \XF::app()->finder('DBTech\eCommerce:Product')
				->where('product_id', $log['productIds'])
				->fetch();

			foreach ($products AS $product)
			{
				/** @var \DBTech\eCommerce\Entity\Product $product */
				$product->setOption('log_moderator', false);
				$product->product_state = 'visible';
				$product->save();
			}
		}

		return true;
	}
}