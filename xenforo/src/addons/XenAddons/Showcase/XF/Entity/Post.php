<?php

namespace XenAddons\Showcase\XF\Entity;

use XF\Mvc\Entity\Structure;

class Post extends XFCP_Post
{
	public function canConvertPostToScReview(&$error = null)
	{
		// only visible posts can be converted to a Showcase Review
		if ($this->message_state != 'visible')
		{
			return false;
		}
	
		if (!$this->Thread->sc_item)
		{
			return false;
		}
	
		if ($this->Thread->discussion_type == 'sc_item')
		{
			// we don't want the first post of a thread to be able to be converted to a review
			if ($this->Thread->first_post_id != $this->post_id)
			{
				return \XF::visitor()->hasNodePermission($this->Thread->node_id, 'convertPostToScReview');
			}
		}
	
		return false;
	}
	
	public function canConvertPostToScUpdate(&$error = null)
	{
		// only visible posts can be converted to a Showcase Update
		if ($this->message_state != 'visible')
		{
			return false;
		}
	
		if (!$this->Thread->sc_item)
		{
			return false;
		}
	
		if ($this->Thread->discussion_type == 'sc_item')
		{
			// we don't want the first post of a thread to be able to be converted to a update
			if ($this->Thread->first_post_id != $this->post_id)
			{
				return \XF::visitor()->hasNodePermission($this->Thread->node_id, 'convertPostToScUpdate');
			}
		}
	
		return false;
	}
	
	public function getBbCodeRenderOptions($context, $type)
	{
		$options = parent::getBbCodeRenderOptions($context, $type);
		$options['showcaseItems'] =  $this->ShowcaseItems;

		return $options;
	}

	public function getShowcaseItems()
	{
		return isset($this->_getterCache['ShowcaseItems']) ? $this->_getterCache['ShowcaseItems'] : null;
	}

	public function setShowcaseItems(array $showcaseItems)
	{
		$this->_getterCache['ShowcaseItems'] = $showcaseItems;
	}
	

	public static function getStructure(Structure $structure)
	{
		$structure = parent::getStructure($structure);

		$structure->getters['ShowcaseItems'] = true;

		return $structure;
	}
}