<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Entity;

class Warning extends XFCP_Warning
{
	/**
	 * @throws \Exception
	 */
	protected function _preSave()
	{
		// Do parent stuff
		$previous = parent::_preSave();

		if ($this->isInsert())
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('warning')
				->testApply([
					'multiplier'     => $this->points,
					'source_user_id' => $this->warning_user_id
				], $this->User)
			;
			
			$eventTriggerRepo->getHandler('punish')
				->testApply([
					'multiplier'     => $this->points,
					'source_user_id' => $this->user_id
				], $this->WarnedBy)
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

		if ($this->isInsert())
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('warning')
				->apply($this->warning_id, [
					'multiplier'     => $this->points,
					'source_user_id' => $this->warning_user_id,
					'content_type'   => $this->content_type,
					'content_id'     => $this->content_id
				], $this->User)
			;
			
			$eventTriggerRepo->getHandler('punish')
				->apply($this->warning_id, [
					'multiplier'     => $this->points,
					'source_user_id' => $this->user_id,
					'content_type'   => $this->content_type,
					'content_id'     => $this->content_id
				], $this->WarnedBy)
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
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('warning')
			->testUndo([
				'multiplier'     => $this->points,
				'source_user_id' => $this->warning_user_id
			], $this->User)
		;
		
		$eventTriggerRepo->getHandler('punish')
			->testUndo([
				'multiplier'     => $this->points,
				'source_user_id' => $this->user_id
			], $this->WarnedBy)
		;

		return $previous;
	}
	
	/**
	 * @throws \Exception
	 */
	protected function _postDelete()
	{
		// Do parent stuff
		$previous = parent::_postDelete();
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('warning')
			->undo($this->warning_id, [
				'multiplier'     => $this->points,
				'source_user_id' => $this->warning_user_id,
				'content_type'   => $this->content_type,
				'content_id'     => $this->content_id
			], $this->User)
		;
		
		$eventTriggerRepo->getHandler('punish')
			->undo($this->warning_id, [
				'multiplier'     => $this->points,
				'source_user_id' => $this->user_id,
				'content_type'   => $this->content_type,
				'content_id'     => $this->content_id
			], $this->WarnedBy)
		;

		return $previous;
	}
}