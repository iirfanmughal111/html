<?php

namespace DBTech\Credits\EventTrigger;

use DBTech\Credits\Entity\Transaction;

/**
 * Class Paycheck
 *
 * @package DBTech\Credits\EventTrigger
 */
class Paycheck extends AbstractHandler
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
	 * @param Transaction $transaction
	 */
	protected function postSave(Transaction $transaction): void
	{
		$transaction->TargetUser->fastUpdate('dbtech_credits_lastpaycheck', $transaction->dateline);
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
			return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_paycheck', $transaction);
		}
		else
		{
			return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_paycheck', $transaction);
		}
	}
	
	/**
	 * @return string|null
	 */
	public function getOptionsTemplate(): ?string
	{
		return null;
	}
}