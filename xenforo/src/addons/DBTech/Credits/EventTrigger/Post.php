<?php

namespace DBTech\Credits\EventTrigger;

use DBTech\Credits\Entity\Event;
use DBTech\Credits\Entity\Transaction;
use XF\Mvc\Entity\Entity;

/**
 * Class Post
 *
 * @package DBTech\Credits\EventTrigger
 */
class Post extends AbstractHandler
{
	/**
	 *
	 */
	protected function setupOptions(): void
	{
		$this->options = array_replace($this->options, [
			'canRevert' => true,
			'canCancel' => true,
			'useOwner' => true,
			'canRebuild' => true,
			
			'multiplier' => self::MULTIPLIER_SIZE,
		]);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 * @param mixed $refId
	 * @param bool $negate
	 * @param array $extraParams
	 *
	 * @return Transaction[]
	 * @throws \XF\PrintableException
	 */
	protected function trigger(
		\XF\Entity\User $user,
		$refId,
		bool $negate = false,
		array $extraParams = []
	): array {
		$extraParams = array_replace([
			'thread_id' => 0,
			'content_type' => 'post',
			'content_id' => $refId ?: 0,
		], $extraParams);
		
		return parent::trigger($user, $refId, $negate, $extraParams);
	}
	
	/**
	 * @param Event $event
	 * @param \XF\Entity\User $user
	 * @param \ArrayObject $extraParams
	 *
	 * @return bool
	 */
	protected function assertEvent(Event $event, \XF\Entity\User $user, \ArrayObject $extraParams): bool
	{
		if (
			$event->getSetting('threadid')
			&& $event->getSetting('threadid') != $extraParams->thread_id
		) {
			// Skip this
			return false;
		}

		return parent::assertEvent($event, $user, $extraParams);
	}
	
	/**
	 * @param Transaction $transaction
	 *
	 * @return mixed
	 */
	public function alertTemplate(Transaction $transaction): string
	{
		// For the benefit of the template
		$which = $transaction->amount < 0.00 ? 'spent' : 'earned';
		
		if ($transaction->negate)
		{
			if ($which == 'spent')
			{
				return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_post_negate', $transaction);
			}
			else
			{
				return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_post_negate', $transaction);
			}
		}
		else
		{
			if ($which == 'spent')
			{
				return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_post', $transaction);
			}
			else
			{
				return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_post', $transaction);
			}
		}
	}
	
	/**
	 * @return array
	 */
	public function getLabels(): array
	{
		$labels = parent::getLabels();
		
		$labels['owner_explain'] = \XF::phrase('dbtech_credits_event_owner_explain_thread');
		$labels['owner_only_others'] = \XF::phrase('dbtech_credits_event_owner_only_others_thread');
		$labels['owner_only_own'] = \XF::phrase('dbtech_credits_event_owner_only_own_thread');
		
		return $labels;
	}
	
	/**
	 * @param array $input
	 *
	 * @return array
	 */
	public function filterOptions(array $input = []): array
	{
		return $this->app()->inputFilterer()->filterArray($input, [
			'threadid' => 'uint',
		]);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Entity entity
	 *
	 * @throws \XF\PrintableException
	 */
	public function rebuild(Entity $entity): void
	{
		/** @var \DBTech\Credits\XF\Entity\Post $entity */
		
		if (!$entity->isFirstPost() && $entity->isVisible())
		{
			$this->apply($entity->post_id, [
				'node_id' => $entity->Thread->node_id,
				'thread_id' => $entity->thread_id,
				'multiplier' => $entity->message,
				'owner_id' => $entity->Thread->user_id,
				
				'content_type' => 'post',
				'content_id'   => $entity->post_id,
				
				'timestamp'   => $entity->post_date,
				'enableAlert' => false,
				'runPostSave' => false
			], $entity->User);
		}
	}
	
	/**
	 * @param bool $forView
	 *
	 * @return array
	 */
	public function getEntityWith(bool $forView = false): array
	{
		return ['User', 'Thread'];
	}
}