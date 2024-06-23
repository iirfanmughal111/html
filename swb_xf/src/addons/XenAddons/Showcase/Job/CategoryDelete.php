<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractJob;

class CategoryDelete extends AbstractJob
{
	protected $defaultData = [
		'category_id' => null,
		'count' => 0,
		'total' => null
	];

	public function run($maxRunTime)
	{
		$s = microtime(true);

		if (!$this->data['category_id'])
		{
			throw new \InvalidArgumentException('Cannot delete items without a category_id.');
		}

		$finder = $this->app->finder('XenAddons\Showcase:Item')
			->where('category_id', $this->data['category_id']);

		if ($this->data['total'] === null)
		{
			$this->data['total'] = $finder->total();
			if (!$this->data['total'])
			{
				return $this->complete();
			}
		}

		$ids = $finder->pluckFrom('item_id')->fetch(1000);
		if (!$ids)
		{
			return $this->complete();
		}

		$continue = count($ids) < 1000 ? false : true;

		foreach ($ids AS $id)
		{
			$this->data['count']++;

			$item = $this->app->find('XenAddons\Showcase:Item', $id);
			if (!$item)
			{
				continue;
			}
			$item->delete(false);

			if ($maxRunTime && microtime(true) - $s > $maxRunTime)
			{
				$continue = true;
				break;
			}
		}

		if ($continue)
		{
			return $this->resume();
		}
		else
		{
			return $this->complete();
		}
	}

	public function getStatusMessage()
	{
		$actionPhrase = \XF::phrase('deleting');
		$typePhrase = \XF::phrase('xa_sc_items');
		return sprintf('%s... %s (%s/%s)', $actionPhrase, $typePhrase,
			\XF::language()->numberFormat($this->data['count']), \XF::language()->numberFormat($this->data['total'])
		);
	}

	public function canCancel()
	{
		return true;
	}

	public function canTriggerByChoice()
	{
		return false;
	}
}