<?php

namespace XenAddons\Showcase\Search\Data;

use XF\Mvc\Entity\Entity;
use XF\Search\Data\AbstractData;
use XF\Search\IndexRecord;
use XF\Search\MetadataStructure;

class Page extends AbstractData
{
	public function getEntityWith($forView = false)
	{
		$get = ['User','CoverImage', 'Item', 'Item.Category'];
		if ($forView)
		{
			$visitor = \XF::visitor();
			$get[] = 'Item.Category.Permissions|' . $visitor->permission_combination_id;
		}
		
		return $get;
	}

	public function getIndexData(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\Item $entity */
		
		if (!$entity->Item || !$entity->Item->Category)
		{
			return null;
		}
		
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $entity->Item;

		$index = IndexRecord::create('sc_page', $entity->page_id, [
			'title' => $entity->title_,
			'message' => $entity->meta_description_ . ' ' . $entity->description_ . ' ' . $entity->message_,
			'date' => $entity->create_date,
			'user_id' => $entity->user_id,
			'discussion_id' => $entity->item_id,
			'metadata' => $this->getMetaData($entity)
		]);

		if (!$entity->isVisible())
		{
			$index->setHidden();
		}

		return $index;
	}
	
	protected function getMetaData(\XenAddons\Showcase\Entity\ItemPage $entity)
	{
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $entity->Item;
	
		$metadata = [
			'itemcat' => $item->category_id,
			'item' => $item->item_id
		];
		if ($item->prefix_id)
		{
			$metadata['itemprefix'] = $item->prefix_id;
		}
	
		return $metadata;
	}
	
	public function setupMetadataStructure(MetadataStructure $structure)
	{
		$structure->addField('itemcat', MetadataStructure::INT);
		$structure->addField('item', MetadataStructure::INT);
		$structure->addField('itemprefix', MetadataStructure::INT);
	}

	public function getResultDate(Entity $entity)
	{
		return $entity->create_date;
	}

	public function getTemplateData(Entity $entity, array $options = [])
	{
		return [
			'page' => $entity,
			'item' => $entity->Item,
			'options' => $options
		];
	}

	public function canUseInlineModeration(Entity $entity, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $entity */
		return $entity->canUseInlineModeration($error);
	}
}