<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XFMG\Entity;

/**
 * Class Rating
 *
 * @package DBTech\Credits\XFMG\Entity
 */
class Rating extends XFCP_Rating
{
	/**
	 * @throws \Exception
	 */
	protected function _preSave()
	{
		// Do parent stuff
		$previous = parent::_preSave();
		
		if (!$this->user_id || $this->isUpdate())
		{
			return $previous;
		}
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('galleryrate')
			->testApply([
				'multiplier' => $this->rating,
				'owner_id' => $this->Content->user_id
			], $this->User)
		;
		
		if ($this->Content->user_id != $this->user_id)
		{
			$eventTriggerRepo->getHandler('galleryrated')
				->testApply([
					'multiplier' => $this->rating,
					'source_user_id' => $this->user_id
				], $this->Content->User)
			;
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
		
		if (!$this->user_id || $this->isUpdate())
		{
			return $previous;
		}
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('galleryrate')
			->apply($this->rating_id, [
				'multiplier' => $this->rating,
				'owner_id' => $this->Content->user_id,
				'content_type' => 'xfmg_rating',
				'content_id' => $this->rating_id
			], $this->User)
		;
		
		if ($this->Content->user_id != $this->user_id)
		{
			$eventTriggerRepo->getHandler('galleryrated')
				->apply($this->rating_id, [
					'multiplier' => $this->rating,
					'source_user_id' => $this->user_id,
					'content_type' => 'xfmg_rating',
					'content_id' => $this->rating_id
				], $this->Content->User)
			;
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
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('galleryrate')
			->testUndo([
				'multiplier' => $this->rating,
				'owner_id' => $this->Content->user_id
			], $this->User)
		;
		
		if ($this->Content->user_id != $this->user_id)
		{
			$eventTriggerRepo->getHandler('galleryrated')
				->testUndo([
					'multiplier' => $this->rating,
					'source_user_id' => $this->user_id
				], $this->Content->User)
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
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('galleryrate')
			->undo($this->rating_id, [
				'multiplier' => $this->rating,
				'owner_id' => $this->Content->user_id,
				'content_type' => 'xfmg_rating',
				'content_id' => $this->rating_id
			], $this->User)
		;
		
		if ($this->Content->user_id != $this->user_id)
		{
			$eventTriggerRepo->getHandler('galleryrated')
				->undo($this->rating_id, [
					'multiplier' => $this->rating,
					'source_user_id' => $this->user_id,
					'content_type' => 'xfmg_rating',
					'content_id' => $this->rating_id
				], $this->Content->User)
			;
		}
		
		// Do parent stuff
		return $previous;
	}
}