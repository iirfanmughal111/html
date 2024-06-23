<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Repository;
use XF\Mvc\Entity\Finder;

class ItemReplyBan extends Repository
{
	/**
	 * @return Finder
	 */
	public function findReplyBansForList()
	{
		$finder = $this->finder('XenAddons\Showcase:ItemReplyBan');
		$finder->setDefaultOrder('ban_date', 'DESC')
			->with('Item', true);
		return $finder;
	}

	/**
	 * @return Finder
	 */
	public function findReplyBansForItem(\XenAddons\Showcase\Entity\Item $item)
	{
		$finder = $this->findReplyBansForList();
		$finder->where('item_id', $item->item_id)
			->with(['User', 'BannedBy']);
		return $finder;
	}

	public function cleanUpExpiredBans($cutOff = null)
	{
		if ($cutOff === null)
		{
			$cutOff = time();
		}
		$this->db()->delete('xf_xa_sc_item_reply_ban', 'expiry_date > 0 AND expiry_date < ?', $cutOff);
	}
}