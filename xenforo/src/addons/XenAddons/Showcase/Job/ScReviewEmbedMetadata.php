<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractEmbedMetadataJob;
use XF\Mvc\Entity\Entity;

class ScReviewEmbedMetadata extends AbstractEmbedMetadataJob
{
	protected function getIdsToRebuild(array $types)
	{
		$db = $this->app->db();

		// Note: only attachments are supported currently, so we filter based on attach count for efficiency.
		// If other types become available, this condition will need to change.
		return $db->fetchAllColumn($db->limit(
			"
				SELECT rating_id
				FROM xf_xa_sc_item_rating
				WHERE rating_id > ?
					AND is_review = 1
					AND attach_count > 0
				ORDER BY rating_id
			", $this->data['batch']
		), $this->data['start']);
	}

	protected function getRecordToRebuild($id)
	{
		return $this->app->em()->find('XenAddons\Showcase:ItemRating', $id);
	}

	protected function getPreparerContext()
	{
		return 'sc_rating';
	}

	protected function getMessageContent(Entity $record)
	{
		return $record->message;
	}

	protected function rebuildAttachments(Entity $record, \XF\Service\Message\Preparer $preparer, array &$embedMetadata)
	{
		$embedMetadata['attachments'] = $preparer->getEmbeddedAttachments();
	}

	protected function getActionDescription()
	{
		$rebuildPhrase = \XF::phrase('rebuilding');
		$type = \XF::phrase('xa_sc_reviews');
		return sprintf('%s... %s', $rebuildPhrase, $type);
	}
}