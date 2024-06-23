<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Entity;

class ReactionContent extends XFCP_ReactionContent
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
						/** @var \XF\Entity\Post $contentInfo */
						$nodeId = $contentInfo->Thread->node_id;
						break;
				}
				
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				
				$eventTriggerRepo->getHandler('react')
					->testApply([
						'node_id'     => $nodeId,
						'owner_id'    => $this->content_user_id,
						'reaction_id' => $this->reaction_id
					], $this->ReactionUser)
				;
				
				if ($this->reaction_user_id != $this->content_user_id)
				{
					$eventTriggerRepo->getHandler('reacted')
						->testApply([
							'node_id'        => $nodeId,
							'source_user_id' => $this->reaction_user_id,
							'reaction_id'    => $this->reaction_id
						], $this->Owner)
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

		if ($this->isInsert())
		{
			$contentInfo = $this->getContent();
			if ($contentInfo !== null)
			{
				$nodeId = 0;
				
				switch ($this->content_type)
				{
					case 'post':
						/** @var \XF\Entity\Post $contentInfo */
						$nodeId = $contentInfo->Thread->node_id;
						break;
				}
				
				/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
				$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
				
				$eventTriggerRepo->getHandler('react')
					->apply($this->reaction_content_id, [
						'node_id'      => $nodeId,
						'owner_id'     => $this->content_user_id,
						'content_type' => $this->content_type,
						'content_id'   => $this->content_id,
						'reaction_id'  => $this->reaction_id
					], $this->ReactionUser)
				;
				
				if ($this->reaction_user_id != $this->content_user_id)
				{
					$eventTriggerRepo->getHandler('reacted')
						->apply($this->reaction_content_id, [
							'node_id'        => $nodeId,
							'source_user_id' => $this->reaction_user_id,
							'content_type'   => $this->content_type,
							'content_id'     => $this->content_id,
							'reaction_id'    => $this->reaction_id
						], $this->Owner)
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

		$contentInfo = $this->getContent();
		if ($contentInfo !== null)
		{
			$nodeId = 0;
			
			switch ($this->content_type)
			{
				case 'post':
					/** @var \XF\Entity\Post $contentInfo */
					$nodeId = $contentInfo->Thread->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('react')
				->testUndo([
					'node_id'     => $nodeId,
					'owner_id'    => $this->content_user_id,
					'reaction_id' => $this->reaction_id
				], $this->ReactionUser)
			;
			
			if ($this->reaction_user_id != $this->content_user_id)
			{
				$eventTriggerRepo->getHandler('reacted')
					->testUndo([
						'node_id'        => $nodeId,
						'source_user_id' => $this->reaction_user_id,
						'reaction_id'    => $this->reaction_id
					], $this->Owner)
				;
			}
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
					/** @var \XF\Entity\Post $contentInfo */
					$nodeId = $contentInfo->Thread->node_id;
					break;
			}
			
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('react')
				->undo($this->reaction_content_id, [
					'node_id'      => $nodeId,
					'owner_id'     => $this->content_user_id,
					'content_type' => $this->content_type,
					'content_id'   => $this->content_id,
					'reaction_id'  => $this->reaction_id
				], $this->ReactionUser)
			;
			
			if ($this->reaction_user_id != $this->content_user_id)
			{
				$eventTriggerRepo->getHandler('reacted')
					->undo($this->reaction_content_id, [
						'node_id'        => $nodeId,
						'source_user_id' => $this->reaction_user_id,
						'content_type'   => $this->content_type,
						'content_id'     => $this->content_id,
						'reaction_id'    => $this->reaction_id
					], $this->Owner)
				;
			}
		}

		return $previous;
	}
}