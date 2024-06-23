<?php

namespace DBTech\eCommerce\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\ApprovalQueue
 */
class Product extends AbstractHandler
{
	/**
	 * @param Entity $content
	 * @param null $error
	 *
	 * @return bool
	 */
	protected function canActionContent(Entity $content, &$error = null): bool
	{
		/** @var $content \DBTech\eCommerce\Entity\Product */
		return $content->canApproveUnapprove($error);
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		$visitor = \XF::visitor();
		return [
			'permissionSet',
			'User',
			'LatestVersion'
		];
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function actionApprove(\DBTech\eCommerce\Entity\Product $product)
	{
		/** @var \DBTech\eCommerce\Service\Product\Approve $approver */
		$approver = \XF::service('DBTech\eCommerce:Product\Approve', $product);
		$approver->setNotifyRunTime(1); // may be a lot happening
		$approver->approve();
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 */
	public function actionDelete(\DBTech\eCommerce\Entity\Product $product)
	{
		$this->quickUpdate($product, 'product_state', 'deleted');
	}
}