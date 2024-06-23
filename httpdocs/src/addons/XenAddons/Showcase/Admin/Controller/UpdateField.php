<?php

namespace XenAddons\Showcase\Admin\Controller;

use XF\Admin\Controller\AbstractField;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class UpdateField extends AbstractField
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('showcase');
	}

	protected function getClassIdentifier()
	{
		return 'XenAddons\Showcase:UpdateField';
	}

	protected function getLinkPrefix()
	{
		return 'xa-sc/update-fields';
	}

	protected function getTemplatePrefix()
	{
		return 'xa_sc_update_field';
	}

	protected function fieldAddEditResponse(\XF\Entity\AbstractField $field)
	{
		$reply = parent::fieldAddEditResponse($field);

		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			/** @var \XenAddons\Showcase\Repository\Category $categoryRepo */
			$categoryRepo = $this->repository('XenAddons\Showcase:Category');

			$categories = $categoryRepo->findCategoryList()->fetch();
			$categoryTree = $categoryRepo->createCategoryTree($categories);

			/** @var \XF\Mvc\Entity\ArrayCollection $fieldAssociations */
			$fieldAssociations = $field->getRelationOrDefault('CategoryUpdateFields', false);

			$reply->setParams([
				'categoryTree' => $categoryTree,
				'categoryIds' => $fieldAssociations->pluckNamed('category_id')
			]);
		}

		return $reply;
	}

	protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractField $field)
	{
		$categoryIds = $this->filter('category_ids', 'array-uint');

		/** @var \XenAddons\Showcase\Entity\UpdateField $field */
		$form->complete(function() use ($field, $categoryIds)
		{
			/** @var \XenAddons\Showcase\Repository\CategoryUpdateField $repo */
			$repo = $this->repository('XenAddons\Showcase:CategoryUpdateField');
			$repo->updateFieldAssociations($field, $categoryIds);
		});

		return $form;
	}
}