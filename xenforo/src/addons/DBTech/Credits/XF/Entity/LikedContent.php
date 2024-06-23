<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Entity;

class LikedContent extends XFCP_LikedContent
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
			$contentInfo = $this->getContent();
			if ($contentInfo !== null)
			{
				$nodeId = 0;

				switch ($this->content_type)
				{
					case 'post':
						$nodeId = $contentInfo->Thread->node_id;
						break;
				}
				
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				
				$eventTriggerRepo->getHandler('like')
					->testApply([
						'node_id' => $nodeId,
						'owner_id' => $this->content_user_id
					], $this->Liker)
				;
				
				$eventTriggerRepo->getHandler('liked')
					->testApply([
						'node_id' => $nodeId,
						'source_user_id' => $this->like_user_id
					], $this->Owner)
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

		if ($this->isInsert())
		{
			$contentInfo = $this->getContent();
			if ($contentInfo !== null)
			{
				$nodeId = 0;

				switch ($this->content_type)
				{
					case 'post':
						$nodeId = $contentInfo->Thread->node_id;
						break;
				}
				
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				
				$eventTriggerRepo->getHandler('like')
					->apply($this->like_id, [
						'node_id' => $nodeId,
						'owner_id' => $this->content_user_id,
						'content_type' => $this->content_type,
						'content_id' => $this->content_id
					], $this->Liker)
				;
				
				$eventTriggerRepo->getHandler('liked')
					->apply($this->like_id, [
						'node_id' => $nodeId,
						'source_user_id' => $this->like_user_id,
						'content_type' => $this->content_type,
						'content_id' => $this->content_id
					], $this->Owner)
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

		$contentInfo = $this->getContent();
		if ($contentInfo !== null)
		{
			$nodeId = 0;

			switch ($this->content_type)
			{
				case 'post':
					$nodeId = $contentInfo->Thread->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('like')
				->testUndo([
					'node_id' => $nodeId,
					'owner_id' => $this->content_user_id
				], $this->Liker)
			;
			
			$eventTriggerRepo->getHandler('liked')
				->testUndo([
					'node_id' => $nodeId,
					'source_user_id' => $this->like_user_id
				], $this->Owner)
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
		if ($contentInfo !== null)
		{
			$nodeId = 0;

			switch ($this->content_type)
			{
				case 'post':
					$nodeId = $contentInfo->Thread->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('like')
				->undo($this->like_id, [
					'node_id' => $nodeId,
					'owner_id' => $this->content_user_id,
					'content_type' => $this->content_type,
					'content_id' => $this->content_id
				], $this->Liker)
			;
			
			$eventTriggerRepo->getHandler('liked')
				->undo($this->like_id, [
					'node_id' => $nodeId,
					'source_user_id' => $this->like_user_id,
					'content_type' => $this->content_type,
					'content_id' => $this->content_id
				], $this->Owner)
			;
		}

		return $previous;
	}
}