<?php

namespace XenAddons\Showcase\Admin\Controller;

use XF\Admin\Controller\AbstractField;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class ItemField extends AbstractField
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('showcase');
	}

	protected function getClassIdentifier()
	{
		return 'XenAddons\Showcase:ItemField';
	}

	protected function getLinkPrefix()
	{
		return 'xa-sc/item-fields';
	}

	protected function getTemplatePrefix()
	{
		return 'xa_sc_item_field';
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
			$fieldAssociations = $field->getRelationOrDefault('CategoryFields', false);

			$reply->setParams([
				'categoryTree' => $categoryTree,
				'categoryIds' => $fieldAssociations->pluckNamed('category_id')
			]);
		}

		return $reply;
	}

	protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractField $field)
	{
		$additionalOptions = $this->filter([
			'hide_title' => 'bool',
			'display_on_list' => 'bool',
			'display_on_tab' => 'bool',
			'display_on_tab_field_id' => 'int'
		]);
		
		$form->setup(function() use ($field, $additionalOptions)
		{
			$field->bulkSet($additionalOptions);
		});
		
		$categoryIds = $this->filter('category_ids', 'array-uint');

		/** @var \XenAddons\Showcase\Entity\ItemField $field */
		$form->complete(function() use ($field, $categoryIds)
		{
			/** @var \XenAddons\Showcase\Repository\CategoryField $repo */
			$repo = $this->repository('XenAddons\Showcase:CategoryField');
			$repo->updateFieldAssociations($field, $categoryIds);
		});

		return $form;
	}
}