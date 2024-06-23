<?php

namespace XenAddons\Showcase\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $item_reply_ban_id
 * @property int $item_id
 * @property int $user_id
 * @property int $ban_date
 * @property int|null $expiry_date
 * @property string $reason
 * @property int $ban_user_id
 *
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\Item $Item
 * @property \XF\Entity\User $User
 * @property \XF\Entity\User $BannedBy
 */
class ItemReplyBan extends Entity
{
	protected function _preSave()
	{
		$ban = $this->em()->findOne('XenAddons\Showcase:ItemReplyBan', [
			'item_id' => $this->item_id,
			'user_id' => $this->user_id
		]);
		if ($ban && $ban != $this)
		{
			$this->error(\XF::phrase('xa_sc_this_user_is_already_reply_banned_from_this_item'));
		}
	}

	protected function _postDelete()
	{
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsFromUser($this->BannedBy, 'sc_item', $this->item_id, 'reply_ban');

		$this->app()->logger()->logModeratorAction(
			'sc_item', $this->Item, 'reply_ban_delete', ['name' => $this->User->username]
		);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_xa_sc_item_reply_ban';
		$structure->shortName = 'XenAddons\Showcase:ItemReplyBan';
		$structure->primaryKey = 'item_reply_ban_id';
		$structure->columns = [
			'item_reply_ban_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'item_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'ban_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'expiry_date' => ['type' => self::UINT, 'required' => true, 'nullable' => true],
			'reason' => ['type' => self::STR, 'default' => '', 'maxLength' => 100],
			'ban_user_id' => ['type' => self::UINT, 'required' => true],
		];
		$structure->getters = [];
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
			],
			'BannedBy' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => [['user_id', '=', '$ban_user_id']],
				'primary' => true
			]
		];

		return $structure;
	}
}