<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractPrefix;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\ParameterBag;
use XF\Mvc\FormAction;

/**
 * Class ProductPrefix
 *
 * @package DBTech\eCommerce\Admin\Controller
 */
class ProductPrefix extends AbstractPrefix
{
	/**
	 * @param $action
	 * @param ParameterBag $params
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
		return 'DBTech\eCommerce:ProductPrefix';
	}
	
	/**
	 * @return string
	 */
	protected function getLinkPrefix(): string
	{
		return 'dbtech-ecommerce/prefixes';
	}
	
	/**
	 * @return string
	 */
	protected function getTemplatePrefix(): string
	{
		return 'dbtech_ecommerce_product_prefix';
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ProductPrefix $prefix
	 *
	 * @return array
	 */
	protected function getCategoryParams(\DBTech\eCommerce\Entity\ProductPrefix $prefix): array
	{
		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = \XF::repository('DBTech\eCommerce:Category');
		$categoryTree = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());

		return [
			'categoryTree' => $categoryTree,
		];
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ProductPrefix|\XF\Entity\AbstractPrefix $prefix
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	protected function prefixAddEditResponse(\XF\Entity\AbstractPrefix $prefix): \XF\Mvc\Reply\AbstractReply
	{
		$reply = parent::prefixAddEditResponse($prefix);

		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			$reply->setParams($this->getCategoryParams($prefix));
		}

		return $reply;
	}
	
	/**
	 * @param FormAction $form
	 * @param ArrayCollection $prefixes
	 *
	 * @return void|FormAction
	 */
	protected function quickSetAdditionalData(FormAction $form, ArrayCollection $prefixes): FormAction
	{
		$input = $this->filter([
			'apply_category_ids' => 'bool',
			'category_ids' => 'array-uint'
		]);

		if ($input['apply_category_ids'])
		{
			$form->complete(function () use ($prefixes, $input)
			{
				$mapRepo = $this->getCategoryPrefixRepo();

				foreach ($prefixes AS $prefix)
				{
					$mapRepo->updatePrefixAssociations($prefix, $input['category_ids']);
				}
			});
		}

		return $form;
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionQuickSet(): \XF\Mvc\Reply\AbstractReply
	{
		$reply = parent::actionQuickSet();

		if ($reply instanceof \XF\Mvc\Reply\View)
		{
			if ($reply->getTemplateName() == $this->getTemplatePrefix() . '_quickset_editor')
			{
				$reply->setParams($this->getCategoryParams($reply->getParam('prefix')));
			}
		}

		return $reply;
	}
	
	/**
	 * @param FormAction $form
	 * @param \XF\Entity\AbstractPrefix $prefix
	 *
	 * @return void|FormAction
	 */
	protected function saveAdditionalData(FormAction $form, \XF\Entity\AbstractPrefix $prefix): FormAction
	{
		$categoryIds = $this->filter('category_ids', 'array-uint');

		$form->complete(function () use ($prefix, $categoryIds)
		{
			$this->getCategoryPrefixRepo()->updatePrefixAssociations($prefix, $categoryIds);
		});

		return $form;
	}

	/**
	 * @return \DBTech\eCommerce\Repository\CategoryPrefix
	 * @noinspection PhpUnnecessaryLocalVariableInspection
	 */
	protected function getCategoryPrefixRepo(): \DBTech\eCommerce\Repository\CategoryPrefix
	{
		/** @var \DBTech\eCommerce\Repository\CategoryPrefix $categoryPrefixRepo */
		/** @noinspection OneTimeUseVariablesInspection */
		$categoryPrefixRepo = $this->repository('DBTech\eCommerce:CategoryPrefix');
		return $categoryPrefixRepo;
	}
}