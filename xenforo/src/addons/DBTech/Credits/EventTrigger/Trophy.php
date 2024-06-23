<?php

namespace DBTech\Credits\EventTrigger;

use DBTech\Credits\Entity\Event;
use DBTech\Credits\Entity\Transaction;

/**
 * Class Trophy
 *
 * @package DBTech\Credits\EventTrigger
 */
class Trophy extends AbstractHandler
{
	/**
	 *
	 */
	protected function setupOptions(): void
	{
		$this->options = array_replace($this->options, [
			'isGlobal' => true,
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
			'trophy_id' => 0,
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
			$event->getSetting('trophy')
			&& !in_array($extraParams->trophy_id, $event->getSetting('trophy'))
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
		
		if ($which == 'spent')
		{
			return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_trophy', $transaction);
		}
		else
		{
			return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_trophy', $transaction);
		}
	}
	
	/**
	 * @param array $input
	 *
	 * @return array
	 */
	public function filterOptions(array $input = []): array
	{
		return $this->app()->inputFilterer()->filterArray($input, [
			'trophy' => 'array-uint',
		]);
	}
}