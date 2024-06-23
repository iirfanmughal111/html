<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractEmbedMetadataJob;
use XF\Mvc\Entity\Entity;

class ScItemUpdateEmbedMetadata extends AbstractEmbedMetadataJob
{
	protected function getIdsToRebuild(array $types)
	{
		$db = $this->app->db();

		// Note: only attachments are supported currently, so we filter based on attach count for efficiency.
		// If other types become available, this condition will need to change.
		return $db->fetchAllColumn($db->limit(
			"
				SELECT item_update_id
				FROM xf_xa_sc_item_update
				WHERE item_update_id > ?
					AND attach_count > 0
				ORDER BY item_update_id
			", $this->data['batch']
		), $this->data['start']);
	}

	protected function getRecordToRebuild($id)
	{
		return $this->app->em()->find('XenAddons\Showcase:ItemUpdate', $id);
	}

	protected function getPreparerContext()
	{
		return 'sc_update';
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
		$type = \XF::phrase('xa_sc_item_updates');
		return sprintf('%s... %s', $rebuildPhrase, $type);
	}
}