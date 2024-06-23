<?php

namespace XenAddons\Showcase\Search\Data;

use XF\Mvc\Entity\Entity;
use XF\Search\Data\AbstractData;
use XF\Search\IndexRecord;
use XF\Search\MetadataStructure;

class RatingReply extends AbstractData
{
	public function getEntityWith($forView = false)
	{
		return ['User', 'ItemRating'];
		
		$get = ['User', 'ItemRating' , 'ItemRating.Item', 'ItemRating.Item.Category'];
		if ($forView)
		{
			$visitor = \XF::visitor();
			$get[] = 'ItemRating.Item.Category.Permissions|' . $visitor->permission_combination_id;
		}
		
		return $get;
	}

	public function getIndexData(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\ItemRatingReply $entity */

		$itemRating = $entity->ItemRating;
		if (!$itemRating)
		{
			return null;
		}

		$index = IndexRecord::create('sc_rating_reply', $entity->reply_id, [
			'title' => '',
			'message' => $entity->message_,
			'date' => $entity->reply_date,
			'user_id' => $entity->user_id,
			'discussion_id' => $entity->rating_id,
			'metadata' => $this->getMetaData($entity)
		]);

		if (!$entity->isVisible())
		{
			$index->setHidden();
		}

		return $index;
	}

	protected function getMetaData(\XenAddons\Showcase\Entity\ItemRatingReply $entity)
	{
		$metadata = [];

		$metadata['content_type'] = 'sc_rating';

		return $metadata;
		
		/** @var \XenAddons\Showcase\Entity\ItemRating $review */
		$review = $entity->ItemRating;
		
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $entity->ItemRating->Item;
		
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
			'review' => $entity->ItemRating,
			'item' => $entity->ItemRating->Item,
			'options' => $options
		];
	}
}