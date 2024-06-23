<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Repository;

class Attachment extends XFCP_Attachment
{
	/**
	 * @param \XF\Entity\Attachment $attachment
	 *
	 * @throws \Exception
	 */
	public function logAttachmentView(\XF\Entity\Attachment $attachment)
	{
		// Shorthand
		$visitor = \XF::visitor();

		$contentInfo = $attachment->getContainer();
		if ($contentInfo !== null)
		{
			$nodeId = 0;

			switch ($attachment->content_type)
			{
				case 'post':
					$nodeId = $contentInfo->Thread->node_id;
					break;
			}
			
			if (in_array($attachment->content_type, [
				'post'
			]))
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				
				$eventTriggerRepo->getHandler('download')
					->apply($attachment->attachment_id, [
						'node_id'      => $nodeId,
						'multiplier'   => $attachment->getFileSize(),
						'owner_id'     => $attachment->Data->user_id,
						'extension'    => strtolower($attachment->getExtension()),
						'content_type' => $attachment->content_type,
						'content_id'   => $attachment->content_id,
					], $visitor)
				;
				
				if ($visitor->user_id != $attachment->Data->user_id)
				{
					$eventTriggerRepo->getHandler('downloaded')
						->apply($attachment->attachment_id, [
							'node_id'        => $nodeId,
							'multiplier'     => $attachment->getFileSize(),
							'source_user_id' => $visitor->user_id,
							'extension'      => strtolower($attachment->getExtension()),
							'content_type'   => $attachment->content_type,
							'content_id'     => $attachment->content_id
						], $attachment->Data->User)
					;
				}
			}
		}

		parent::logAttachmentView($attachment);
	}
}