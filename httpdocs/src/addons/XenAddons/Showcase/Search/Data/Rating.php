<?php

namespace XenAddons\Showcase\Search\Data;

use XF\Mvc\Entity\Entity;
use XF\Search\Data\AbstractData;
use XF\Search\IndexRecord;
use XF\Search\MetadataStructure;
use XF\Search\Query\MetadataConstraint;

class Rating extends AbstractData
{
	public function getEntityWith($forView = false)
	{
		$get = ['User', 'Item', 'Item.Category'];
		if ($forView)
		{
			$visitor = \XF::visitor();
			$get[] = 'Item.Category.Permissions|' . $visitor->permission_combination_id;
		}
		
		return $get;
	}

	public function getIndexData(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\ItemRating $entity */
		
		if (!$entity->Item || !$entity->Item->Category)
		{
			return null;
		}
		
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $entity->Item;

		$index = IndexRecord::create('sc_rating', $entity->rating_id, [
			'title' => '',
			'message' => $entity->message_,
			'date' => $entity->rating_date,
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

	protected function getMetaData(\XenAddons\Showcase\Entity\ItemRating $entity)
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
		return $entity->rating_date;
	}

	public function getTemplateData(Entity $entity, array $options = [])
	{
		return [
			'review' => $entity,
			'item' => $entity->Item,
			'options' => $options
		];
	}

	public function canUseInlineModeration(Entity $entity, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemRating $entity */
		return $entity->canUseInlineModeration($error);
	}
}