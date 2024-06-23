<?php

namespace XenAddons\Showcase\InlineMod\Rating;

use XF\Http\Request;
use XF\InlineMod\AbstractAction;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

abstract class AbstractMoveCopy extends AbstractAction
{
	protected $targetItem;

	public function getTitle()
	{
		throw new \LogicException("The title phrase must be overridden.");
	}

	protected function canApplyInternal(AbstractCollection $entities, array $options, &$error)
	{
		$result = parent::canApplyInternal($entities, $options, $error);

		if ($result && $options['item_type'])
		{
			switch ($options['item_type'])
			{
				case 'existing':
					$itemRepo = $this->app()->repository('XenAddons\Showcase:Item');
					$item = $itemRepo->getItemFromUrl($options['existing_url'], null, $itemFetchError);
					if ($item)
					{
						$category = $item->Category;
					}
					else
					{
						$error = $itemFetchError;
						return false;
					}
					break;
			}
		}

		return $result;
	}

	protected function canApplyToEntity(Entity $entity, array $options, &$error = null)
	{
		throw new \LogicException("canApplyToEntity must be overridden.");
	}

	public function applyInternal(AbstractCollection $entities, array $options)
	{
		throw new \LogicException("applyInternal must be overridden.");
	}

	protected function applyToEntity(Entity $entity, array $options)
	{
		throw new \LogicException("applyToEntity should not be called on review moving or copying");
	}

	public function getBaseOptions()
	{
		return [
			'item_type' => '',
			'existing_url' => null,
			'alert' => false,
			'alert_reason' => ''
		];
	}

	public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
	{
		throw new \LogicException("renderForm must be overridden.");
	}

	public function getFormOptions(AbstractCollection $entities, Request $request)
	{
		$options = [
			'item_type' => $request->filter('item_type', 'str'), // existing or new
			'existing_url' => $request->filter('existing_url', 'str'),
			'alert' => $request->filter('author_alert', 'bool'),
			'alert_reason' => $request->filter('author_alert_reason', 'str')
		];

		return $options;
	}

	protected function getTargetItemFromOptions(array $options)
	{
		$item = $this->app()->repository('XenAddons\Showcase:Item')->getItemFromUrl($options['existing_url']);

		if (!$item)
		{
			throw new \InvalidArgumentException("No target item available");
		}

		return $item;
	}
}