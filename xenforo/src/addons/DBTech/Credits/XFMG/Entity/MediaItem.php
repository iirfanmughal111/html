<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XFMG\Entity;

/**
 * Class MediaItem
 *
 * @package DBTech\Credits\XFMG\Entity
 */
class MediaItem extends XFCP_MediaItem
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
		
		if ($this->isUpdate())
		{
			$visibilityChange = $this->isStateChanged('media_state', 'visible');
			if ($visibilityChange == 'leave')
			{
				// Undo the event
				
				if ($this->Attachment)
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('galleryupload')
						->testUndo([
							'multiplier' => $this->Attachment->getFileSize(),
							'extension'  => $this->Attachment->getExtension(),
						], $this->User)
					;
				}
				else
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('galleryupload')
						->testUndo([], $this->User)
					;
				}
			}
			elseif ($visibilityChange == 'enter')
			{
				// Reapply the event
				
				if ($this->Attachment)
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('galleryupload')
						->testApply([
							'multiplier' => $this->Attachment->getFileSize(),
							'extension'  => $this->Attachment->getExtension(),
						], $this->User)
					;
				}
				else
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('galleryupload')
						->testApply([], $this->User)
					;
				}
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
		
		if ($this->isUpdate())
		{
			$visibilityChange = $this->isStateChanged('media_state', 'visible');
			if ($visibilityChange == 'leave')
			{
				// Undo the event
				
				if ($this->Attachment)
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('galleryupload')
						->undo($this->media_id, [
							'multiplier' => $this->Attachment->getFileSize(),
							'extension'  => $this->Attachment->getExtension(),
							'content_type' => 'xfmg_media',
							'content_id'   => $this->media_id
						], $this->User)
					;
				}
				else
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('galleryupload')
						->undo($this->media_id, [
							'content_type' => 'xfmg_media',
							'content_id'   => $this->media_id
						], $this->User)
					;
				}
			}
			elseif ($visibilityChange == 'enter')
			{
				// Reapply the event
				
				if ($this->Attachment)
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('galleryupload')
						->apply($this->media_id, [
							'multiplier' => $this->Attachment->getFileSize(),
							'extension'  => $this->Attachment->getExtension(),
							'content_type' => 'xfmg_media',
							'content_id'   => $this->media_id
						], $this->User)
					;
				}
				else
				{
					/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
					$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
					$eventTriggerRepo->getHandler('galleryupload')
						->apply($this->media_id, [
							'content_type' => 'xfmg_media',
							'content_id'   => $this->media_id
						], $this->User)
					;
				}
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
		
		if ($this->Attachment)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('galleryupload')
				->testUndo([
					'multiplier' => $this->Attachment->getFileSize(),
					'extension'  => $this->Attachment->getExtension(),
				], $this->User)
			;
		}
		else
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('galleryupload')
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
		// Do parent stuff
		$previous = parent::_postDelete();
		
		if (!$this->user_id)
		{
			return $previous;
		}
		
		if ($this->Attachment)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('galleryupload')
				->undo($this->media_id, [
					'multiplier' => $this->Attachment->getFileSize(),
					'extension'  => $this->Attachment->getExtension(),
					'content_type' => 'xfmg_media',
					'content_id' => $this->media_id
				], $this->User)
			;
		}
		else
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('galleryupload')
				->undo($this->media_id, [
					'content_type' => 'xfmg_media',
					'content_id' => $this->media_id
				], $this->User)
			;
		}
		
		// Do parent stuff
		return $previous;
	}
}