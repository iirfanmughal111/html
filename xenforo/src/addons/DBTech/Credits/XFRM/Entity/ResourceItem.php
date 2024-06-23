<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XFRM\Entity;

/**
 * Class ResourceItem
 *
 * @package DBTech\Credits\XFRM\Entity
 */
class ResourceItem extends XFCP_ResourceItem
{
	/**
	 * @throws \Exception
	 */
	protected function _preSave()
	{
		// Do parent stuff
		$previous = parent::_preSave();
		
		if (!$this->user_id)
		{
			return $previous;
		}
		
		if ($this->isInsert())
		{
			if ($this->resource_type == 'download')
			{
				/** @var \XF\Repository\Attachment $attachRepo */
				$attachRepo = $this->repository('XF:Attachment');
				
				/** @var \XF\Entity\Attachment[] $attachments */
				$attachments = $attachRepo->findAttachmentsByContent('resource_version', $this->current_version_id)
					->with('Data')
					->fetch()
				;
				
				foreach ($attachments as $attachment)
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('resourceupload')
						->testApply([
							'multiplier' => $attachment->getFileSize(),
							'extension' => $attachment->getExtension(),
						], $this->User)
					;
				}
			}
			else
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				$eventTriggerRepo->getHandler('resourceupload')
					->testApply([], $this->User)
				;
			}
		}
		
		return $previous;
	}
	
	/**
	 * @throws \Exception
	 */
	protected function _postSave()
	{
		// Do parent stuff
		$previous = parent::_postSave();
		
		if (!$this->user_id)
		{
			return $previous;
		}
		
		if ($this->isInsert())
		{
			if ($this->resource_type == 'download')
			{
				/** @var \XF\Repository\Attachment $attachRepo */
				$attachRepo = $this->repository('XF:Attachment');
				
				/** @var \XF\Entity\Attachment[] $attachments */
				$attachments = $attachRepo->findAttachmentsByContent('resource_version', $this->current_version_id)
					->with('Data')
					->fetch()
				;
				
				foreach ($attachments as $attachment)
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('resourceupload')
						->apply($attachment->attachment_id, [
							'multiplier' => $attachment->getFileSize(),
							'extension' => $attachment->getExtension(),
							'content_type' => 'resource',
							'content_id' => $this->resource_id
						], $this->User)
					;
				}
			}
			else
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				$eventTriggerRepo->getHandler('resourceupload')
					->apply(0, [
						'content_type' => 'resource',
						'content_id' => $this->resource_id
					], $this->User)
				;
			}
		}
		
		return $previous;
	}
	
	/**
	 * @throws \Exception
	 */
	protected function _preDelete()
	{
		// Do parent stuff
		$previous = parent::_preDelete();
		
		if (!$this->user_id)
		{
			return $previous;
		}
		
		if ($this->resource_type == 'download')
		{
			/** @var \XFRM\Entity\ResourceVersion $firstVersion */
			$firstVersion = $this->finder('XFRM:ResourceVersion')
				->where('resource_id', $this->resource_id)
				->order('resource_version_id', 'ASC')
				->fetchOne()
			;
			
			/** @var \XF\Repository\Attachment $attachRepo */
			$attachRepo = $this->repository('XF:Attachment');
			
			/** @var \XF\Entity\Attachment[] $attachments */
			$attachments = $attachRepo->findAttachmentsByContent('resource_version', $firstVersion->resource_version_id)
				->with('Data')
				->fetch()
			;
			
			foreach ($attachments as $attachment)
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				$eventTriggerRepo->getHandler('resourceupload')
					->testUndo([
						'multiplier' => $attachment->getFileSize(),
						'extension' => $attachment->getExtension(),
					], $this->User)
				;
			}
		}
		else
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('resourceupload')
				->testUndo([], $this->User)
			;
		}
		
		return $previous;
	}
	
	/**
	 * @throws \Exception
	 */
	protected function _postDelete()
	{
		if (!$this->user_id)
		{
			return parent::_postDelete();
		}
		
		if ($this->resource_type == 'download')
		{
			/** @var \XFRM\Entity\ResourceVersion $firstVersion */
			$firstVersion = $this->finder('XFRM:ResourceVersion')
				->where('resource_id', $this->resource_id)
				->order('resource_version_id', 'ASC')
				->fetchOne()
			;
			
			/** @var \XF\Repository\Attachment $attachRepo */
			$attachRepo = $this->repository('XF:Attachment');
			
			/** @var \XF\Entity\Attachment[] $attachments */
			$attachments = $attachRepo->findAttachmentsByContent('resource_version', $firstVersion->resource_version_id)
				->with('Data')
				->fetch()
			;
			
			foreach ($attachments as $attachment)
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				$eventTriggerRepo->getHandler('resourceupload')
					->undo($attachment->attachment_id, [
						'multiplier' => $attachment->getFileSize(),
						'extension' => $attachment->getExtension(),
						'content_type' => 'resource',
						'content_id' => $this->resource_id
					], $this->User)
				;
			}
		}
		else
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('resourceupload')
				->undo(0, [
					'content_type' => 'resource',
					'content_id' => $this->resource_id
				], $this->User)
			;
		}
		
		// Do parent stuff
		return parent::_postDelete();
	}
}