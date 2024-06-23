<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Admin\Controller;

class User extends XFCP_User
{
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return \XF\Mvc\FormAction
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function userSaveProcess(\XF\Entity\User $user)
	{
		$form = parent::userSaveProcess($user);
		$input = $this->filter('credits', 'array');

		/** @var \DBTech\Credits\Entity\Currency[] $currencies */
		$currencies = $this->finder('DBTech\Credits:Currency')
			->fetch()
		;

		$form->validate(function () use (&$input, $user, $currencies)
		{
			foreach ($currencies as $currency)
			{
				// Make sure there's an adjust event
				$currency->verifyAdjustEvent();
				
				if (!isset($input[$currency->currency_id]))
				{
					// This was probably a deactivated currency
					unset($input[$currency->currency_id]);
					
					continue;
				}

				if ($user->{$currency->column} == $input[$currency->currency_id])
				{
					// No change in points
					unset($input[$currency->currency_id]);
				}
			}

			foreach ($input as $currencyId => $value)
			{
				if (!isset($currencies[$currencyId]))
				{
					// Ignore this currency
					unset($input[$currencyId]);
					continue;
				}
			}
		});

		$form->complete(function () use ($input, $user, $currencies)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$adjustHandler = $eventTriggerRepo->getHandler('adjust');
			
			/** @var \DBTech\Credits\XF\Entity\User $visitor */
			$visitor = \XF::visitor();

			foreach ($input as $currencyId => $value)
			{
				/** @var \DBTech\Credits\Entity\Currency $currency */
				$currency = $currencies[$currencyId];

				if ($user->{$currency->column} < $value)
				{
					// Adjust event (up)
					$adjustHandler
						->apply($user->user_id, [
							'currency_id' 	=> $currencyId,
							'multiplier' 	=> abs($value - $user->{$currency->column}),
							'message'  		=> \XF::language()->renderPhrase('dbtech_credits_admin_adjust'),
							'source_user_id' => $visitor->user_id,
						], $user);
				}
				elseif ($user->{$currency->column} > $value)
				{
					// Adjust event (down)
					$adjustHandler
						->apply($user->user_id, [
							'currency_id' => $currencyId,
							'multiplier' => (-1 * abs($user->{$currency->column} - $value)),
							'message' => \XF::language()->renderPhrase('dbtech_credits_admin_adjust'),
							'source_user_id' => $visitor->user_id,
						], $user);
				}
			}
		});

		return $form;
	}
}