<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XFMG\Service\Media;

/**
 * Class Creator
 *
 * @package DBTech\Credits\XFMG\Service\Media
 */
class Creator extends XFCP_Creator
{
	/**
	 * @return array
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _validate()
	{
		$previous = parent::_validate();
		
		if (empty($previous))
		{
			if ($this->attachment)
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				$eventTriggerRepo->getHandler('galleryupload')
					->testApply([
						'multiplier' => $this->attachment->getFileSize(),
						'extension'  => $this->attachment->getExtension(),
					], $this->mediaItem->User)
				;
			}
			else
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				$eventTriggerRepo->getHandler('galleryupload')
					->testApply([], $this->mediaItem->User)
				;
			}
		}
		
		return $previous;
	}
	
	/**
	 * @return \XFMG\Entity\MediaItem
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _save()
	{
		$mediaItem = parent::_save();
		
		if ($mediaItem && $mediaItem->isVisible())
		{
			if ($mediaItem->Attachment)
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				$eventTriggerRepo->getHandler('galleryupload')
					->apply($mediaItem->media_id, [
						'multiplier' => $mediaItem->Attachment->getFileSize(),
						'extension'  => $mediaItem->Attachment->getExtension(),
						'content_type' => 'xfmg_media',
						'content_id' => $mediaItem->media_id
					], $mediaItem->User)
				;
			}
			else
			{
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				$eventTriggerRepo->getHandler('galleryupload')
					->apply($mediaItem->media_id, [
						'content_type' => 'xfmg_media',
						'content_id' => $mediaItem->media_id
					], $mediaItem->User)
				;
			}
		}
		
		return $mediaItem;
	}
}