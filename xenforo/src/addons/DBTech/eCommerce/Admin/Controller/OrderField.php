<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractField;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class OrderField
 * @package DBTech\eCommerce\Admin\Controller
 */
class OrderField extends AbstractField
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomOrder');
	}

	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:OrderField';
	}

	/**
	 * @return string
	 */
	protected function getLinkPrefix(): string
	{
		return 'dbtech-ecommerce/products/order-fields';
	}

	/**
	 * @return string
	 */
	protected function getTemplatePrefix(): string
	{
		return 'dbtech_ecommerce_order_field';
	}
	
	/**
	 * @param \XF\Entity\AbstractField $field
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 */
	protected function fieldAddEditResponse(\XF\Entity\AbstractField $field): \XF\Mvc\Reply\AbstractReply
	{
		$reply = parent::fieldAddEditResponse($field);
		
		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			/** @var \DBTech\eCommerce\Repository\Product $productRepo */
			$productRepo = \XF::repository('DBTech\eCommerce:Product');
			$productTree = $productRepo->createProductTree();
			
			/** @var \XF\Mvc\Entity\ArrayCollection $productFieldAssociations */
			$productFieldAssociations = $field->getRelationOrDefault('OrderFields', false);
			
			$reply->setParams([
				'productTree' => $productTree,
				'productIds' => $productFieldAssociations->pluckNamed('product_id')
			]);
		}
		
		return $reply;
	}
	
	/**
	 * @param FormAction $form
	 * @param \XF\Entity\AbstractField $field
	 *
	 * @return FormAction
	 */
	protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractField $field): FormAction
	{
		$productIds = $this->filter('product_ids', 'array-uint');
		
		/** @var \DBTech\eCommerce\Entity\OrderField $field */
		$form->complete(function () use ($field, $productIds)
		{
			/** @var \DBTech\eCommerce\Repository\OrderFieldMap $repo */
			$repo = $this->repository('DBTech\eCommerce:OrderFieldMap');
			$repo->updateFieldAssociations($field, $productIds);
		});
		
		return $form;
	}
}