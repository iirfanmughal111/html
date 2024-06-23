<?php

namespace DBTech\eCommerce\InlineMod\Product;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

/**
 * Class Move
 *
 * @package DBTech\eCommerce\InlineMod\Product
 */
class Move extends AbstractAction
{
	/**
	 * @var
	 */
	protected $targetCategory;
	/**
	 * @var
	 */
	protected $targetCategoryId;
	
	/**
	 * @return \XF\Phrase
	 */
	public function getTitle(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_move_products...');
	}
	
	/**
	 * @param AbstractCollection $entities
	 * @param array $options
	 * @param $error
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 */
	protected function canApplyInternal(AbstractCollection $entities, array $options, &$error): bool
	{
		$result = parent::canApplyInternal($entities, $options, $error);
		
		if ($result && $options['target_category_id'])
		{
			$category = $this->getTargetCategory($options['target_category_id']);
			if (!$category)
			{
				return false;
			}
			
			if ($options['check_category_viewable'] && !$category->canView($error))
			{
				return false;
			}
			
			if ($options['check_all_same_category'])
			{
				$allSame = true;
				foreach ($entities AS $entity)
				{
					/** @var \DBTech\eCommerce\Entity\Product $entity */
					if ($entity->product_category_id != $options['target_category_id'])
					{
						$allSame = false;
						break;
					}
				}
				
				if ($allSame)
				{
					$error = \XF::phrase('dbtech_ecommerce_all_selected_products_already_in_destination_category_select_another');
					return false;
				}
			}
		}
		
		return $result;
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
		return $entity->canMove($error);
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 *
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function applyToEntity(Entity $entity, array $options)
	{
		$category = $this->getTargetCategory($options['target_category_id']);
		if (!$category)
		{
			throw new \InvalidArgumentException('No target specified');
		}

		/** @var \DBTech\eCommerce\Service\Product\Move $mover */
		$mover = $this->app()->service('DBTech\eCommerce:Product\Move', $entity);

		if ($options['alert'])
		{
			$mover->setSendAlert(true, $options['alert_reason']);
		}

		if ($options['notify_watchers'])
		{
			$mover->setNotifyWatchers();
		}
		
		if ($options['prefix_id'] !== null)
		{
			$mover->setPrefix($options['prefix_id']);
		}

		$mover->move($category);

		$this->returnUrl = $this->app()->router()->buildLink('dbtech-ecommerce/categories', $category);
	}
	
	/**
	 * @return array
	 */
	public function getBaseOptions(): array
	{
		return [
			'target_category_id' => 0,
			'check_category_viewable' => true,
			'check_all_same_category' => true,
			'prefix_id' => null,
			'notify_watchers' => false,
			'alert' => false,
			'alert_reason' => ''
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
		/** @var \DBTech\eCommerce\Entity\ProductPrefix[]|\XF\Mvc\Entity\ArrayCollection $prefixes */
		$prefixes = $this->app()->finder('DBTech\eCommerce:ProductPrefix')
			->order('materialized_order')
			->fetch();
		
		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = $this->app()->repository('DBTech\eCommerce:Category');
		$categories = $categoryRepo->getViewableCategories();

		$viewParams = [
			'products' => $entities,
			'prefixes' => $prefixes->groupBy('prefix_group_id'),
			'total' => count($entities),
			'categoryTree' => $categoryRepo->createCategoryTree($categories),
			'first' => $entities->first()
		];
		return $controller->view('DBTech\eCommerce:Public:InlineMod\Product\Move', 'inline_mod_dbtech_ecommerce_product_move', $viewParams);
	}
	
	/**
	 * @param AbstractCollection $entities
	 * @param Request $request
	 *
	 * @return array
	 */
	public function getFormOptions(AbstractCollection $entities, Request $request): array
	{
		$options = [
			'target_category_id' => $request->filter('target_category_id', 'uint'),
			'prefix_id' => $request->filter('prefix_id', 'uint'),
			'notify_watchers' => $request->filter('notify_watchers', 'bool'),
			'alert' => $request->filter('author_alert', 'bool'),
			'alert_reason' => $request->filter('author_alert_reason', 'str')
		];
		if (!$request->filter('apply_prefix', 'bool'))
		{
			$options['prefix_id'] = null;
		}

		return $options;
	}
	
	/**
	 * @param int $categoryId
	 *
	 * @return null|\DBTech\eCommerce\Entity\Category
	 * @throws \InvalidArgumentException
	 */
	protected function getTargetCategory(int $categoryId): ?\DBTech\eCommerce\Entity\Category
	{
		if ($this->targetCategoryId && $this->targetCategoryId == $categoryId)
		{
			return $this->targetCategory;
		}
		if (!$categoryId)
		{
			return null;
		}

		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->app()->em()->find('DBTech\eCommerce:Category', $categoryId);
		if (!$category)
		{
			throw new \InvalidArgumentException("Invalid target category ($categoryId)");
		}

		$this->targetCategoryId = $categoryId;
		$this->targetCategory = $category;

		return $this->targetCategory;
	}
}