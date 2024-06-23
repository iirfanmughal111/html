<?php

namespace DBTech\Credits\EventTrigger;

use DBTech\Credits\Entity\Transaction;

/**
 * Class Read
 *
 * @package DBTech\Credits\EventTrigger
 */
class Read extends AbstractHandler
{
	/**
	 *
	 */
	protected function setupOptions(): void
	{
		$this->options = array_replace($this->options, [
			'canCancel' => true,
			'useOwner' => true,
		]);
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
			return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_read', $transaction);
		}
		else
		{
			return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_read', $transaction);
		}
	}
	
	/**
	 * @return string|null
	 */
	public function getOptionsTemplate(): ?string
	{
		return null;
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
}