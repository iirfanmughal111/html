<?php

namespace DBTech\eCommerce\InlineMod;

use XF\InlineMod\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\InlineMod
 */
class Product extends AbstractHandler
{
	/**
	 * @return array|\XF\InlineMod\AbstractAction[]
	 * @throws \Exception
	 * @throws \LogicException
	 */
	public function getPossibleActions(): array
	{
		$actions = [];

		$actions['delete'] = $this->getActionHandler('DBTech\eCommerce:Product\Delete');

		$actions['undelete'] = $this->getSimpleActionHandler(
			\XF::phrase('dbtech_ecommerce_undelete_products'),
			'canUndelete',
			function (Entity $entity)
			{
				/** @var \DBTech\eCommerce\Service\Product\Delete $deleter */
				$deleter = $this->app->service('DBTech\eCommerce:Product\Delete', $entity);
				$deleter->unDelete();
			}
		);

		$actions['approve'] = $this->getSimpleActionHandler(
			\XF::phrase('dbtech_ecommerce_approve_products'),
			'canApproveUnapprove',
			function (Entity $entity)
			{
				/** @var \DBTech\eCommerce\Entity\Product $entity */
				if ($entity->product_state == 'moderated')
				{
					/** @var \DBTech\eCommerce\Service\Product\Approve $approver */
					$approver = \XF::service('DBTech\eCommerce:Product\Approve', $entity);
					$approver->setNotifyRunTime(1); // may be a lot happening
					$approver->approve();
				}
			}
		);

		$actions['unapprove'] = $this->getSimpleActionHandler(
			\XF::phrase('dbtech_ecommerce_unapprove_products'),
			'canApproveUnapprove',
			function (Entity $entity)
			{
				/** @var \DBTech\eCommerce\Entity\Product $entity */
				if ($entity->product_state == 'visible')
				{
					$entity->product_state = 'moderated';
					$entity->save();
				}
			}
		);

		$actions['reassign'] = $this->getActionHandler('DBTech\eCommerce:Product\Reassign');
		$actions['move'] = $this->getActionHandler('DBTech\eCommerce:Product\Move');
		$actions['apply_prefix'] = $this->getActionHandler('DBTech\eCommerce:Product\ApplyPrefix');

		return $actions;
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'permissionSet'
		];
	}
}