<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractJob;

class ItemAction extends AbstractJob
{
	protected $defaultData = [
		'start' => 0,
		'count' => 0,
		'total' => null,
		'criteria' => null,
		'itemIds' => null,
		'actions' => []
	];

	public function run($maxRunTime)
	{
		if (is_array($this->data['criteria']) && is_array($this->data['itemIds']))
		{
			throw new \LogicException("Cannot have both criteria and itemIds values; one must be null");
		}

		$startTime = microtime(true);
		$em = $this->app->em();

		$ids = $this->prepareItemIds();
		if (!$ids)
		{
			return $this->complete();
		}

		$db = $this->app->db();
		$db->beginTransaction();

		$limitTime = ($maxRunTime > 0);
		foreach ($ids AS $key => $id)
		{
			$this->data['count']++;
			$this->data['start'] = $id;
			unset($ids[$key]);

			/** @var \XenAddons\Showcase\Entity\Item $item */
			$item = $em->find('XenAddons\Showcase:Item', $id);
			if ($item)
			{
				if ($this->getActionValue('delete'))
				{
					$item->delete(false, false);
					continue; // no further action required
				}

				$this->applyInternalItemChange($item);
				$item->save(false, false);

				$this->applyExternalItemChange($item);
			}

			if ($limitTime && microtime(true) - $startTime > $maxRunTime)
			{
				break;
			}
		}

		if (is_array($this->data['itemIds']))
		{
			$this->data['itemIds'] = $ids;
		}

		$db->commit();

		return $this->resume();
	}

	protected function getActionValue($action)
	{
		$value = null;
		if (!empty($this->data['actions'][$action]))
		{
			$value = $this->data['actions'][$action];
		}
		return $value;
	}

	protected function prepareItemIds()
	{
		if (is_array($this->data['criteria']))
		{
			$searcher = $this->app->searcher('XenAddons\Showcase:Item', $this->data['criteria']);
			$results = $searcher->getFinder()
				->where('item_id', '>', $this->data['start'])
				->order('item_id')
				->limit(1000)
				->fetchColumns('item_id');
			$ids = array_column($results, 'item_id'); 
		}
		else if (is_array($this->data['itemIds']))
		{
			$ids = $this->data['itemIds'];
		}
		else
		{
			$ids = [];
		}
		sort($ids, SORT_NUMERIC);
		return $ids;
	}

	protected function applyInternalItemChange(\XenAddons\Showcase\Entity\Item $item)
	{
		if ($categoryId = $this->getActionValue('category_id'))
		{
			$item->category_id = $categoryId;
		}

		if ($this->getActionValue('apply_item_prefix'))
		{
			$item->prefix_id = intval($this->getActionValue('prefix_id'));
		}
		
		if ($this->getActionValue('lock_comments'))
		{
			$item->comments_open = false;
		}
		if ($this->getActionValue('unlock_comments'))
		{
			$item->comments_open = true;
		}
		
		if ($this->getActionValue('lock_ratings'))
		{
			$item->ratings_open = false;
		}
		if ($this->getActionValue('unlock_ratings'))
		{
			$item->ratings_open = true;
		}

		if ($this->getActionValue('approve'))
		{
			$item->item_state = 'visible';
		}
		if ($this->getActionValue('unapprove'))
		{
			$item->item_state = 'moderated';
		}

		if ($this->getActionValue('soft_delete'))
		{
			$item->item_state = 'deleted';
		}
	}

	protected function applyExternalItemChange(\XenAddons\Showcase\Entity\Item $item)
	{
	}

	public function getStatusMessage()
	{
		$actionPhrase = \XF::phrase('updating');
		$typePhrase = \XF::phrase('xa_sc_items');

		if ($this->data['total'] !== null)
		{
			return sprintf('%s... %s (%d/%d)', $actionPhrase, $typePhrase, $this->data['count'], $this->data['total']);
		}
		else
		{
			return sprintf('%s... %s (%d)', $actionPhrase, $typePhrase, $this->data['start']);
		}
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