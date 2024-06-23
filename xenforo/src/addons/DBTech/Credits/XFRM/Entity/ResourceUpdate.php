<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XFRM\Entity;

/**
 * Class ResourceUpdate
 *
 * @package DBTech\Credits\XFRM\Entity
 */
class ResourceUpdate extends XFCP_ResourceUpdate
{
	/**
	 * @throws \Exception
	 */
	protected function _preSave()
	{
		// Do parent stuff
		$previous = parent::_preSave();
		
		if ($this->isInsert() && !$this->isDescription())
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('resourceupdate')
				->testApply([], $this->Resource->User)
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
		
		if ($this->isInsert() && !$this->isDescription())
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTriggerRepo->getHandler('resourceupdate')
				->apply($this->resource_update_id, [
					'content_type' => 'resource_update',
					'content_id' => $this->resource_update_id
				], $this->Resource->User)
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
		$eventTriggerRepo->getHandler('resourceupdate')
			->testUndo([], $this->Resource->User)
		;
		
		return $previous;
	}
	
	/**
	 * @throws \Exception
	 */
	protected function _postDelete()
	{
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		$eventTriggerRepo->getHandler('resourceupdate')
			->undo($this->resource_update_id, [
				'content_type' => 'resource_update',
				'content_id' => $this->resource_update_id
			], $this->Resource->User)
		;
		
		// Do parent stuff
		return parent::_postDelete();
	}
}