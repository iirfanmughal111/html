<?php

namespace XenAddons\Showcase\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $item_id
 * @property int $user_id
 * @property int $is_co_owner
 *
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Item $Item
 * @property \XF\Entity\User $User
 */
class ItemContributor extends Entity
{
	protected function _postSave()
	{
		$this->rebuildItemContributorCache();
	}

	protected function _postDelete()
	{
		$this->rebuildItemContributorCache();

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsToUser(
			$this->user_id,
			'sc_item',
			$this->item_id,
			'contributor_add'
		);
	}

	protected function rebuildItemContributorCache()
	{
		\XF::runOnce(
			'xaScItemContributorCache' . $this->item_id,
			function()
			{
				/** @var \XenAddons\Showcase\Repository\Item */
				$itemRepo = $this->repository('XenAddons\Showcase:Item');
				$itemRepo->rebuildItemContributorCache($this->Item);
			}
		);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_contributor';
		$structure->shortName = 'XenAddons\Showcase:ItemContributor';
		$structure->primaryKey = ['item_id', 'user_id'];
		$structure->columns = [
			'item_id' => [
				'type' => self::UINT,
				'required' => true
			],
			'user_id' => [
				'type' => self::UINT,
				'required' => true
			],
			'is_co_owner' => [
				'type' => self::BOOL, 
				'default' => false
			]
		];
		$structure->relations = [
			'Item' => [
				'entity' => 'XenAddons\Showcase:Item',
				'type' => self::TO_ONE,
				'conditions' => 'item_id',
				'primary' => true
			],
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			]
		];

		return $structure;
	}
}
