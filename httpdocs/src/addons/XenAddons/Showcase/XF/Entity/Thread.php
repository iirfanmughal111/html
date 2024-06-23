<?php

namespace XenAddons\Showcase\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
	public function canConvertThreadToScItem(&$error = null)
	{
		// only visible threads can be converted to a showcase item
		if ($this->discussion_state != 'visible')
		{
			return false; 
		}
		
		// Check for valid ThreadType
		if ($this->discussion_type == 'discussion' || $this->discussion_type == 'article')
		{
			return \XF::visitor()->hasNodePermission($this->node_id, 'convertTheadToScItem');
		}
		
		return false;
	}
	
	public function getScItem()
	{
		if ($this->discussion_type == 'sc_item')
		{
			/** @var \XenAddons\Showcase\Entity\Item $item */
			$item = \XF::repository('XenAddons\Showcase:Item')->findItemForThread($this)->fetchOne();
				
			if ($item && $item->canView())
			{
				return $item;
			}
		}
	
		return null;
	}
	
	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);
	
		$structure->getters['sc_item'] = true;
	
		return $structure;
	}	
}