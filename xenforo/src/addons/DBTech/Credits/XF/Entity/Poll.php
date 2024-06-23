<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Entity;

class Poll extends XFCP_Poll
{
	/**
	 * @throws \Exception
	 */
	protected function _preSave()
	{
		// Do parent stuff
		$previous = parent::_preSave();
		
		$contentInfo = $this->getContent();
		if ($contentInfo !== null && $contentInfo->isValidRelation('User'))
		{
			$nodeId = 0;
			switch ($this->content_type)
			{
				case 'thread':
					$nodeId = $contentInfo->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$pollEvent = $eventTriggerRepo->getHandler('poll');
			
			if ($this->isUpdate())
			{
				// Undo previous event
				$pollEvent->testUndo([
					'multiplier' => count($this->getPreviousValue('responses')),
					'node_id' => $nodeId
				], $contentInfo->User);
			}

			// Apply the event
			$pollEvent->testApply([
				'multiplier' => count($this->responses),
				'node_id' => $nodeId
			], $contentInfo->User);
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

		$contentInfo = $this->getContent();
		if ($contentInfo !== null && $contentInfo->isValidRelation('User'))
		{
			$nodeId = 0;
			switch ($this->content_type)
			{
				case 'thread':
					$nodeId = $contentInfo->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$pollEvent = $eventTriggerRepo->getHandler('poll');
			
			if ($this->isUpdate())
			{
				// Undo previous event
				$pollEvent->undo($this->poll_id, [
					'multiplier' => count($this->getPreviousValue('responses')),
					'node_id' => $nodeId,
					'content_type' => $this->content_type,
					'content_id' => $this->content_id
				], $contentInfo->User);
			}
			
			// Apply the event
			$pollEvent->apply($this->poll_id, [
				'multiplier' => count($this->responses),
				'node_id' => $nodeId,
				'content_type' => $this->content_type,
				'content_id' => $this->content_id
			], $contentInfo->User);
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

		$contentInfo = $this->getContent();
		if ($contentInfo !== null && $contentInfo->isValidRelation('User'))
		{
			$nodeId = 0;
			switch ($this->content_type)
			{
				case 'thread':
					$nodeId = $contentInfo->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			// Undo event
			$eventTriggerRepo->getHandler('poll')
				->testUndo([
					'multiplier' => count($this->getPreviousValue('responses')),
					'node_id' => $nodeId
				], $contentInfo->User)
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

		$contentInfo = $this->getContent();
		if ($contentInfo !== null && $contentInfo->isValidRelation('User'))
		{
			$nodeId = 0;
			switch ($this->content_type)
			{
				case 'thread':
					$nodeId = $contentInfo->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			// Undo event
			$eventTriggerRepo->getHandler('poll')
				->undo($this->poll_id, [
					'multiplier' => count($this->getPreviousValue('responses')),
					'node_id' => $nodeId,
					'content_type' => $this->content_type,
					'content_id' => $this->content_id
				], $contentInfo->User)
			;
		}

		return $previous;
	}
}