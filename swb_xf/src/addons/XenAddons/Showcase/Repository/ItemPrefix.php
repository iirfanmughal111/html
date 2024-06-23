<?php

namespace XenAddons\Showcase\Repository;

use XF\Repository\AbstractPrefix;

class ItemPrefix extends AbstractPrefix
{
	protected function getRegistryKey()
	{
		return 'xa_scPrefixes';
	}

	protected function getClassIdentifier()
	{
		return 'XenAddons\Showcase:ItemPrefix';
	}
	
	public function getVisiblePrefixListData()
	{
		if (!method_exists($this, '_getVisiblePrefixListData'))
		{
			// in case this version of Showcase is used on an older version of XF.
			return $this->getPrefixListData();
		}
	
		$categories = $this->finder('XenAddons\Showcase:Category')
			->with('Permissions|' . \XF::visitor()->permission_combination_id)
			->fetch();
	
		$prefixMap = $this->finder('XenAddons\Showcase:CategoryPrefix')
			->fetch()
			->groupBy('prefix_id', 'category_id');
	
		$isVisibleClosure = function(\XenAddons\Showcase\Entity\ItemPrefix $prefix) use ($prefixMap, $categories)
		{
			if (!isset($prefixMap[$prefix->prefix_id]))
			{
				return false;
			}
	
			$isVisible = false;
	
			foreach ($prefixMap[$prefix->prefix_id] AS $categoryPrefix)
			{
				/** @var \XenAddons\Showcase\Entity\CategoryPrefix $categoryPrefix */
	
				if (!isset($categories[$categoryPrefix->category_id]))
				{
					continue;
				}
	
				$isVisible = $categories[$categoryPrefix->category_id]->canView();
	
				if ($isVisible)
				{
					break;
				}
			}
	
			return $isVisible;
		};
		return $this->_getVisiblePrefixListData($isVisibleClosure);
	}
}