<?php

namespace XenAddons\Showcase\Finder;

use XF\Mvc\Entity\Finder;

class ItemPage extends Finder
{
	public function inItem(\XenAddons\Showcase\Entity\Item $item, array $limits = [])
	{
		$limits = array_replace([
			
		], $limits);

		$this->where('item_id', $item->item_id);

		return $this;
	}
	
	public function byUser(\XF\Entity\User $user)
	{
		$this->where('user_id', $user->user_id);
	
		return $this;
	}	
}