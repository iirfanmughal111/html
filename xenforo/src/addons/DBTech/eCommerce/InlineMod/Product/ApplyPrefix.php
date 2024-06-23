<?php

namespace DBTech\eCommerce\InlineMod\Product;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

/**
 * Class ApplyPrefix
 *
 * @package DBTech\eCommerce\InlineMod\Product
 */
class ApplyPrefix extends AbstractAction
{
	/**
	 * @return \XF\Phrase
	 */
	public function getTitle(): \XF\Phrase
	{
		return \XF::phrase('apply_prefix...');
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 * @param null $error
	 *
	 * @return bool
	 */
	protected function canApplyToEntity(Entity $entity, array $options, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\Entity\Product $entity */
		return $entity->canEdit($error);
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 *
	 * @throws \LogicException
	 */
	protected function applyToEntity(Entity $entity, array $options)
	{
		/** @var \DBTech\eCommerce\Entity\Product $entity */
		if (!$entity->Category->isPrefixValid($options['prefix_id']))
		{
			return;
		}

		/** @var \DBTech\eCommerce\Service\Product\Edit $editor */
		$editor = $this->app()->service('DBTech\eCommerce:Product\Edit', $entity);
		$editor->setPerformValidations(false);
		$editor->setPrefix($options['prefix_id']);
		if ($editor->validate($errors))
		{
			$editor->save();
		}
	}
	
	/**
	 * @return array
	 */
	public function getBaseOptions(): array
	{
		return [
			'prefix_id' => null
		];
	}
	
	/**
	 * @param AbstractCollection $entities
	 * @param \XF\Mvc\Controller $controller
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 */
	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller): \XF\Mvc\Reply\AbstractReply
	{
		$categories = $entities->pluckNamed('Category', 'product_category_id');
		$prefixIds = [];

		foreach ($categories AS $category)
		{
			$prefixIds = array_merge($prefixIds, array_keys($category->prefix_cache));
		}

		$prefixes = $this->app()->finder('DBTech\eCommerce:ProductPrefix')
			->where('prefix_id', array_unique($prefixIds))
			->order('materialized_order')
			->fetch();

		if (!$prefixes->count())
		{
			return $controller->error(\XF::phrase('dbtech_ecommerce_no_prefixes_available_for_selected_categories'));
		}

		$selectedPrefix = 0;
		$prefixCounts = [0 => 0];
		foreach ($entities AS $product)
		{
			$prefixId = $product->prefix_id;

			if (!isset($prefixCounts[$prefixId]))
			{
				$prefixCounts[$prefixId] = 1;
			}
			else
			{
				$prefixCounts[$prefixId]++;
			}

			if ($prefixCounts[$prefixId] > $prefixCounts[$selectedPrefix])
			{
				$selectedPrefix = $prefixId;
			}
		}

		$viewParams = [
			'products' => $entities,
			'prefixes' => $prefixes->groupBy('prefix_group_id'),
			'categoryCount' => count($categories->keys()),
			'selectedPrefix' => $selectedPrefix,
			'total' => count($entities)
		];
		return $controller->view('DBTech\eCommerce:Public:InlineMod\Product\ApplyPrefix', 'inline_mod_dbtech_ecommerce_product_apply_prefix', $viewParams);
	}
	
	/**
	 * @param AbstractCollection $entities
	 * @param Request $request
	 *
	 * @return array
	 */
	public function getFormOptions(AbstractCollection $entities, Request $request): array
	{
		return [
			'prefix_id' => $request->filter('prefix_id', 'uint')
		];
	}
}