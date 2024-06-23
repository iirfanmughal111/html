<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Entity;

class UserFollow extends XFCP_UserFollow
{
	/**
	 * @throws \Exception
	 */
	protected function _preSave()
	{
		// Do parent stuff
		$previous = parent::_preSave();
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('follow')
			->testApply([
				'owner_id' => $this->follow_user_id
			], $this->User)
		;
		
		$eventTriggerRepo->getHandler('followed')
			->testApply([
				'source_user_id' => $this->user_id
			], $this->FollowUser)
		;

		return $previous;
	}
	
	/**
	 * @throws \Exception
	 */
	protected function _postSave()
	{
		// Do parent stuff
		$previous = parent::_postSave();
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('follow')
			->apply($this->follow_user_id, [
				'owner_id'     => $this->follow_user_id,
				'content_type' => 'user',
				'content_id'   => $this->follow_user_id
			], $this->User)
		;
		
		$eventTriggerRepo->getHandler('followed')
			->apply($this->user_id, [
				'source_user_id' => $this->user_id,
				'content_type'   => 'user',
				'content_id'     => $this->follow_user_id
			], $this->FollowUser)
		;

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
		
		$eventTriggerRepo->getHandler('follow')
			->testUndo([
				'owner_id' => $this->follow_user_id
			], $this->User)
		;
		
		$eventTriggerRepo->getHandler('followed')
			->testUndo([
				'source_user_id' => $this->user_id
			], $this->FollowUser)
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
		
		$eventTriggerRepo->getHandler('follow')
			->undo($this->follow_user_id, [
				'owner_id'     => $this->follow_user_id,
				'content_type' => 'user',
				'content_id'   => $this->follow_user_id
			], $this->User)
		;
		
		$eventTriggerRepo->getHandler('followed')
			->undo($this->user_id, [
				'source_user_id' => $this->user_id,
				'content_type'   => 'user',
				'content_id'     => $this->follow_user_id
			], $this->FollowUser)
		;
		
		return $previous;
	}
}