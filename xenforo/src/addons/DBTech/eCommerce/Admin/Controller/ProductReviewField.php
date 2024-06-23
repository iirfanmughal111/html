<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractField;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class ProductReviewField
 *
 * @package DBTech\eCommerce\Admin\Controller
 */
class ProductReviewField extends AbstractField
{
	/**
	 * @param $action
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcommerceProduct');
	}

	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:ProductReviewField';
	}

	/**
	 * @return string
	 */
	protected function getLinkPrefix(): string
	{
		return 'dbtech-ecommerce/review-fields';
	}

	/**
	 * @return string
	 */
	protected function getTemplatePrefix(): string
	{
		return 'dbtech_ecommerce_product_review_field';
	}

	/**
	 * @param \XF\Entity\AbstractField $field
	 *
	 * @return \XF\Mvc\Reply\View
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
			$fieldAssociations = $field->getRelationOrDefault('CategoryReviewFields', false);

			$reply->setParams([
				'categoryTree' => $categoryTree,
				'categoryIds' => $fieldAssociations->pluckNamed('category_id')
			]);
		}

		return $reply;
	}

	/**
	 * @param \XF\Mvc\FormAction $form
	 * @param \XF\Entity\AbstractField $field
	 *
	 * @return \XF\Mvc\FormAction
	 */
	protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractField $field): FormAction
	{
		$categoryIds = $this->filter('category_ids', 'array-uint');

		/** @var \DBTech\eCommerce\Entity\ProductReviewField $field */
		$form->complete(function () use ($field, $categoryIds)
		{
			/** @var \DBTech\eCommerce\Repository\CategoryReviewField $repo */
			$repo = $this->repository('DBTech\eCommerce:CategoryReviewField');
			$repo->updateFieldAssociations($field, $categoryIds);
		});

		return $form;
	}
}