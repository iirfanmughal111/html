<?php

namespace XenAddons\Showcase\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $series_part_id
 * @property int $series_id
 * @property int $user_id
 * @property int $item_id
 * @property int $display_order
 * @property int $create_date
 * @property int $edit_date
 * 
 * GETTERS
 *
 * RELATIONS
 * @property \XF\Entity\User User
 * @property \XenAddons\Showcase\Entity\SeriesItem $Series
 * @property \XenAddons\Showcase\Entity\Item $Item
 * @property \XF\Entity\DeletionLog $DeletionLog
 */
class SeriesPart extends Entity
{
	public function canView(&$error = null)
	{
		return (
			$this->Item->canView() 
			&& $this->Series->canView());
	}
	
	public function canEdit(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($this->hasPermission('editAnySeries'))
		{
			return true;
		}

		return (
			$this->Series->user_id == $visitor->user_id
			&& $this->hasPermission('editOwnSeries')
		);
	}	

	public function canRemove(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}

		if ($this->hasPermission('editAnySeries'))
		{
			return true;
		}
		
		// We want to allow Item Owners that have their Items in a Community series, to be able to remove them... 

		if (
			$this->Series->community_series
			&& $this->Item->user_id == $visitor->user_id
			&& $this->hasPermission('editOwnSeries')
		)
		{
			return true;
		}
		

		return (
			$this->Series->user_id == $visitor->user_id
			&& $this->hasPermission('editOwnSeries')
		);
	}
	
	public function hasPermission($permission)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		return $visitor->hasShowcaseSeriesPermission($permission);
	}
	
	protected function _preSave()
	{
		if (!$this->series_id || !$this->item_id)
		{
			throw new \LogicException("Need series and item IDs");
		}
	}

	protected function _postSave()
	{
		$this->updateSeriesRecord();
		$this->updateItemRecord();
		
		if ($this->isUpdate() && $this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorChanges('sc_series_part', $this);
		}
	}
	
	protected function updateSeriesRecord()
	{
		if (!$this->Series)
		{
			return;
		}
		
		$series = $this->Series;
		
		if ($this->isInsert())
		{
			$series->partAdded($this);
			$series->save();
		}
		
		if ($this->isUpdate())
		{
			$series->partUpdated($this);
			$series->save();
		}
	}	
	
	protected function updateItemRecord()
	{
		if (!$this->Item)
		{
			return;
		}
	
		$item = $this->Item;
	
		if ($this->isInsert())
		{
			$item->addedToSeries($this);
			$item->save();
		}
	}

	protected function _postDelete()
	{
		$db = $this->db();
		
		if($this->Series)
		{
			$this->Series->partRemoved($this);
			$this->Series->save();
		}
		
		if ($this->Item)
		{
			$this->Item->removedFromSeries($this);
			$this->Item->save();
		}
		
		if ($this->getOption('log_moderator'))
		{
			$this->app()->logger()->logModeratorAction('sc_series_part', $this, 'delete_hard');
		}
		
		$db->delete('xf_deletion_log', 'content_id = ? AND content_type = ?', [$this->series_part_id, 'sc_series_part']);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_series_part';
		$structure->shortName = 'XenAddons\Showcase:SeriesPart';
		$structure->primaryKey = 'series_part_id';
		$structure->contentType = 'sc_series_part';
		$structure->columns = [
			'series_part_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'series_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'item_id' => ['type' => self::UINT, 'required' => true],
			'display_order' => ['type' => self::UINT, 'default' => 1],
			'create_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'edit_date' => ['type' => self::UINT, 'default' => \XF::$time]	
		];
		$structure->getters = [
		];
		$structure->behaviors = [
		];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Series' => [
				'entity' => 'XenAddons\Showcase:SeriesItem',
				'type' => self::TO_ONE,
				'conditions' => 'series_id',
				'primary' => true
			],
			'Item' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => 'item_id',
				'primary' => true
			],
			'DeletionLog' => [
				'entity' => 'XF:DeletionLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['content_type', '=', 'sc_series_part'],
					['content_id', '=', '$series_part_id']
				],
				'primary' => true
			]
		];
		$structure->options = [
			'log_moderator' => true
		];
		$structure->defaultWith = ['Series', 'User', 'Item'];

		$structure->withAliases = [
			'full' => [
				'User', 
				'Item', 
				'Item.Featured', 
				'Item.Category', 
				'Item.CoverImage',
				function()
				{
					$userId = \XF::visitor()->user_id;
					if ($userId)
					{
						return [
							'Item.Read|' . $userId, 
							'Item.Watch|' . $userId
						];
					}
				
					return null;
				}
			]
		];		
		
		return $structure;
	}
}