<?php

namespace XenAddons\Showcase\Search\Data;

use XF\Mvc\Entity\Entity;
use XF\Search\Data\AbstractData;
use XF\Search\IndexRecord;
use XF\Search\MetadataStructure;

class ItemUpdateReply extends AbstractData
{
	public function getEntityWith($forView = false)
	{
		return ['User', 'ItemUpdate'];
		
		$get = ['User', 'ItemUpdate' , 'ItemUpdate.Item', 'ItemUpdate.Item.Category'];
		if ($forView)
		{
			$visitor = \XF::visitor();
			$get[] = 'ItemUpdate.Item.Category.Permissions|' . $visitor->permission_combination_id;
		}
		
		return $get;
	}

	public function getIndexData(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\ItemUpdateReply $entity */

		$itemUpdate = $entity->ItemUpdate;
		if (!$itemUpdate)
		{
			return null;
		}

		$index = IndexRecord::create('sc_update_reply', $entity->reply_id, [
			'title' => '',
			'message' => $entity->message_,
			'date' => $entity->reply_date,
			'user_id' => $entity->user_id,
			'discussion_id' => $entity->item_update_id,
			'metadata' => $this->getMetaData($entity)
		]);

		if (!$entity->isVisible())
		{
			$index->setHidden();
		}

		return $index;
	}

	protected function getMetaData(\XenAddons\Showcase\Entity\ItemUpdateReply $entity)
	{
		$metadata = [];

		$metadata['content_type'] = 'sc_update';

		return $metadata;
		
		/** @var \XenAddons\Showcase\Entity\ItemUpdate $update */
		$upadte = $entity->ItemUpdate;
		
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $entity->ItemUpdate->Item;
		
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
		return $entity->reply_date;
	}

	public function getTemplateData(Entity $entity, array $options = [])
	{
		return [
			'reply' => $entity,
			'update' => $entity->ItemUpdate,
			'item' => $entity->ItemUpdate->Item,
			'options' => $options
		];
	}
}