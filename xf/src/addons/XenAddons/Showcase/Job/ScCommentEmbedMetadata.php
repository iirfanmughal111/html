<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractEmbedMetadataJob;
use XF\Mvc\Entity\Entity;

class ScCommentEmbedMetadata extends AbstractEmbedMetadataJob
{
	protected function getIdsToRebuild(array $types)
	{
		$db = $this->app->db();

		// Note: only attachments are supported currently, so we filter based on attach count for efficiency.
		// If other types become available, this condition will need to change.
		return $db->fetchAllColumn($db->limit(
			"
				SELECT comment_id
				FROM xf_xa_sc_comment
				WHERE comment_id > ?
					AND attach_count > 0
				ORDER BY comment_id
			", $this->data['batch']
		), $this->data['start']);
	}

	protected function getRecordToRebuild($id)
	{
		return $this->app->em()->find('XenAddons\Showcase:Comment', $id);
	}

	protected function getPreparerContext()
	{
		return 'sc_comment';
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
		$type = \XF::phrase('xa_sc_comments');
		return sprintf('%s... %s', $rebuildPhrase, $type);
	}
}