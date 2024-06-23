<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractField;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class ProductField
 * @package DBTech\eCommerce\Admin\Controller
 */
class ProductField extends AbstractField
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomProduct');
	}

	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:ProductField';
	}

	/**
	 * @return string
	 */
	protected function getLinkPrefix(): string
	{
		return 'dbtech-ecommerce/products/fields';
	}

	/**
	 * @return string
	 */
	protected function getTemplatePrefix(): string
	{
		return 'dbtech_ecommerce_product_field';
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
			/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
			$categoryRepo = $this->repository('DBTech\eCommerce:Category');
			
			$categories = $categoryRepo->findCategoryList()->fetch();
			$categoryTree = $categoryRepo->createCategoryTree($categories);
			
			/** @var \XF\Mvc\Entity\ArrayCollection $fieldAssociations */
			$fieldAssociations = $field->getRelationOrDefault('CategoryFields', false);
			
			$reply->setParams([
				'categoryTree' => $categoryTree,
				'categoryIds' => $fieldAssociations->pluckNamed('category_id')
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
		$input = $this->filter([
			'filterable' => 'bool',
		]);
		
		$form->basicEntitySave($field, $input);
		
		$categoryIds = $this->filter('category_ids', 'array-uint');
		
		/** @var \DBTech\eCommerce\Entity\ProductField $field */
		$form->complete(function () use ($field, $categoryIds)
		{
			/** @var \DBTech\eCommerce\Repository\CategoryField $repo */
			$repo = $this->repository('DBTech\eCommerce:CategoryField');
			$repo->updateFieldAssociations($field, $categoryIds);
		});
		
		return $form;
	}
}