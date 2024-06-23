<?php

namespace XenAddons\Showcase\InlineMod\Item;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

class Move extends AbstractAction
{
	protected $targetCategory;
	protected $targetCategoryId;

	public function getTitle()
	{
		return \XF::phrase('xa_sc_move_items...');
	}
	
	protected function canApplyInternal(AbstractCollection $entities, array $options, &$error)
	{
		$result = parent::canApplyInternal($entities, $options, $error);
		
		if ($result)
		{
			if ($options['target_category_id'])
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
						/** @var \XenAddons\Showcase\Entity\Item $entity */
						if ($entity->category_id != $options['target_category_id'])
						{
							$allSame = false;
							break;
						}
					}
					
					if ($allSame)
					{
						$error = \XF::phrase('xa_sc_all_selected_items_already_in_destination_category_select_another');
						return false;
					}
				}
			}
		}
		
		return $result;
	}

	protected function canApplyToEntity(Entity $entity, array $options, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\Item $entity */
		return $entity->canMove($error);
	}

	protected function applyToEntity(Entity $entity, array $options)
	{
		$category = $this->getTargetCategory($options['target_category_id']);
		if (!$category)
		{
			throw new \InvalidArgumentException("No target specified");
		}

		/** @var \XenAddons\Showcase\Service\Item\Move $mover */
		$mover = $this->app()->service('XenAddons\Showcase:Item\Move', $entity);

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

		$this->returnUrl = $this->app()->router()->buildLink('showcase/categories', $category);
	}

	public function getBaseOptions()
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

	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
	{
		$prefixes = $this->app()->finder('XenAddons\Showcase:ItemPrefix')
			->order('materialized_order')
			->fetch();

		$categoryRepo = $this->app()->repository('XenAddons\Showcase:Category');
		$categories = $categoryRepo->getViewableCategories();

		$viewParams = [
			'items' => $entities,
			'prefixes' => $prefixes->groupBy('prefix_group_id'),
			'total' => count($entities),
			'categoryTree' => $categoryRepo->createCategoryTree($categories),
			'first' => $entities->first()
		];
		return $controller->view('XenAddons\Showcase:Public:InlineMod\Item\Move', 'xa_sc_inline_mod_item_move', $viewParams);
	}

	public function getFormOptions(AbstractCollection $entities, Request $request)
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
	 * @param integer $categoryId
	 * 
	 * @return null|\XenAddons\Showcase\Entity\Category
	 */
	protected function getTargetCategory($categoryId)
	{
		$categoryId = intval($categoryId);

		if ($this->targetCategoryId && $this->targetCategoryId == $categoryId)
		{
			return $this->targetCategory;
		}
		if (!$categoryId)
		{
			return null;
		}

		$category = $this->app()->em()->find('XenAddons\Showcase:Category', $categoryId);
		if (!$category)
		{
			throw new \InvalidArgumentException("Invalid target category ($categoryId)");
		}

		$this->targetCategoryId = $categoryId;
		$this->targetCategory = $category;

		return $this->targetCategory;
	}
}