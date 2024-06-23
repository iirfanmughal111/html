<?php

namespace XenAddons\Showcase\InlineMod\Item;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

class Merge extends AbstractAction
{
	public function getTitle()
	{
		return \XF::phrase('xa_sc_merge_items...');
	}
	
	protected function canApplyInternal(AbstractCollection $entities, array $options, &$error)
	{
		$result = parent::canApplyInternal($entities, $options, $error);
		
		if ($result)
		{
			if ($options['target_item_id'])
			{
				if (!isset($entities[$options['target_item_id']]))
				{
					return false;
				}
			}

			if ($entities->count() < 2)
			{
				return false;
			}
		}
		
		return $result;
	}

	protected function canApplyToEntity(Entity $entity, array $options, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\Item $entity */
		return $entity->canMerge($error);
	}

	public function applyInternal(AbstractCollection $entities, array $options)
	{
		if (!$options['target_item_id'])
		{
			throw new \InvalidArgumentException("No target item selected");
		}

		$source = $entities->toArray();
		$target = $source[$options['target_item_id']];
		unset($source[$options['target_item_id']]);

		/** @var \XenAddons\Showcase\Service\Item\Merger $merger */
		$merger = $this->app()->service('XenAddons\Showcase:Item\Merger', $target);

		if ($options['alert'])
		{
			$merger->setSendAlert(true, $options['alert_reason']);
		}

		$merger->merge($source);

		$this->returnUrl = $this->app()->router()->buildLink('showcase', $target);
	}

	protected function applyToEntity(Entity $entity, array $options)
	{
		throw new \LogicException("applyToEntity should not be called on item merging");
	}

	public function getBaseOptions()
	{
		return [
			'target_item_id' => 0,
			'alert' => false,
			'alert_reason' => ''
		];
	}

	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
	{
		$viewParams = [
			'items' => $entities,
			'total' => count($entities),
			'first' => $entities->first()
		];
		return $controller->view('XenAddons\Showcase:Public:InlineMod\Item\Merge', 'xa_sc_inline_mod_item_merge', $viewParams);
	}

	public function getFormOptions(AbstractCollection $entities, Request $request)
	{
		$options = [
			'target_item_id' => $request->filter('target_item_id', 'uint'),
			'alert' => $request->filter('starter_alert', 'bool'),
			'alert_reason' => $request->filter('starter_alert_reason', 'str')
		];

		return $options;
	}
}