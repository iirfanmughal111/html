<?php

namespace DBTech\Credits\EventTrigger;

use DBTech\Credits\Entity\Transaction;

/**
 * Class Followed
 *
 * @package DBTech\Credits\EventTrigger
 */
class Followed extends AbstractHandler
{
	/**
	 *
	 */
	protected function setupOptions(): void
	{
		$this->options = array_replace($this->options, [
			'isGlobal' => true,
			'canRevert' => true,
			'canCancel' => true,
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
		
		if ($transaction->negate)
		{
			if ($which == 'spent')
			{
				return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_followed_negate', $transaction);
			}
			else
			{
				return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_followed_negate', $transaction);
			}
		}
		else
		{
			if ($which == 'spent')
			{
				return $this->getAlertPhrase('dbtech_credits_lost_x_y_via_followed', $transaction);
			}
			else
			{
				return $this->getAlertPhrase('dbtech_credits_gained_x_y_via_followed', $transaction);
			}
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