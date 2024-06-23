<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Attachment;

use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class Post extends XFCP_Post
{
	/**
	 * @param \XF\Http\Upload $upload
	 * @param \XF\Attachment\Manipulator $manipulator
	 *
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function validateAttachmentUpload(\XF\Http\Upload $upload, \XF\Attachment\Manipulator $manipulator)
	{
		$nodeId = 0;
		
		$context = $manipulator->getContext();
		if (isset($context['post_id']))
		{
			/** @var \XF\Entity\Post $content */
			$content = \XF::em()->find('XF:Post', $context['post_id']);
			$nodeId = $content->Thread->node_id;
		}
		elseif (isset($context['thread_id']))
		{
			/** @var \XF\Entity\Thread $content */
			$content = \XF::em()->find('XF:Thread', $context['thread_id']);
			$nodeId = $content->node_id;
		}
		elseif (isset($context['node_id']))
		{
			$nodeId = $context['node_id'];
		}
		
		if ($nodeId)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = \XF::repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('upload')
				->testApply([
					'node_id'      => $nodeId,
					'multiplier'   => $upload->getFileSize(),
					'extension'    => strtolower($upload->getExtension())
				], \XF::visitor())
			;
		}
		
		parent::validateAttachmentUpload($upload, $manipulator);
	}
	
	/**
	 * @param Attachment $attachment
	 * @param Entity|null $container
	 *
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function onAssociation(Attachment $attachment, Entity $container = null)
	{
		/** @var \XF\Entity\Post $container */
		
		if ($container && $container->isVisible())
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = \XF::repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('upload')
				->apply($attachment->attachment_id, [
					'node_id'      => $container->Thread->node_id,
					'multiplier'   => $attachment->getFileSize(),
					'extension'    => strtolower($attachment->getExtension()),
					'content_type' => $attachment->content_type,
					'content_id'   => $attachment->content_id,
				], $attachment->Data->User)
			;
		}
		
		parent::onAssociation($attachment, $container);
	}
	
	/**
	 * @param Attachment $attachment
	 * @param Entity|null $container
	 *
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function beforeAttachmentDelete(Attachment $attachment, Entity $container = null)
	{
		/** @var \XF\Entity\Post $container */
		
		if ($container)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = \XF::repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('upload')
				->testUndo([
					'node_id'      => $container->Thread->node_id,
					'multiplier'   => $attachment->getFileSize(),
					'extension'    => strtolower($attachment->getExtension()),
					'content_type' => $attachment->content_type,
					'content_id'   => $attachment->content_id,
				], $attachment->Data->User)
			;
		}
		parent::beforeAttachmentDelete($attachment, $container);
	}
	
	/**
	 * @param Attachment $attachment
	 * @param Entity|null $container
	 *
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
	{
		if ($container)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $attachment->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('upload')
				->undo($attachment->attachment_id, [
					'node_id'      => $container->Thread->node_id,
					'multiplier'   => $attachment->getFileSize(),
					'extension'    => strtolower($attachment->getExtension()),
					'content_type' => $attachment->content_type,
					'content_id'   => $attachment->content_id,
				], $attachment->Data->User)
			;
		}
		
		parent::onAttachmentDelete($attachment, $container);
	}
}