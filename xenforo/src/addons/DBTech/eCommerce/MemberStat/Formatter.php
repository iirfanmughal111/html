<?php

namespace DBTech\eCommerce\MemberStat;

use XF\Entity\MemberStat;
use XF\Finder\User;

/**
 * Class Formatter
 *
 * @package DBTech\eCommerce\MemberStat
 */
class Formatter
{
	/**
	 * @param MemberStat $memberStat
	 * @param User $finder
	 *
	 * @return array|\XF\Mvc\Entity\ArrayCollection
	 */
	public static function amountSpent(MemberStat $memberStat, User $finder)
	{
		if ($memberStat->show_value)
		{
			$valueField = $memberStat->sort_order;
			$finder->where($valueField, '>', 0);
		}
		else
		{
			$valueField = null;
		}
		
		$results = $finder->fetch($memberStat->user_limit * 3);
		
		if ($valueField)
		{
			$results = $results->pluckNamed($valueField, 'user_id');
			$results = array_map(function (float $value): string
			{
				return \XF::app()->data('XF:Currency')->languageFormat($value, \XF::options()->dbtechEcommerceCurrency);
			}, $results);
		}
		else
		{
			$results = $results->pluck(function (\XF\Entity\User $user): array
			{
				return [$user->user_id, null];
			}, false);
		}
		return $results;
	}
	
	/**
	 * @param MemberStat $memberStat
	 * @param User $finder
	 *
	 * @return array|\XF\Mvc\Entity\ArrayCollection
	 */
	public static function number(MemberStat $memberStat, User $finder)
	{
		if ($memberStat->show_value)
		{
			$valueField = $memberStat->sort_order;
			$finder->where($valueField, '>', 0);
		}
		else
		{
			$valueField = null;
		}
		
		$results = $finder->fetch($memberStat->user_limit * 3);
		
		if ($valueField)
		{
			$results = $results->pluckNamed($valueField, 'user_id');
			$results = array_map(function (float $value): string
			{
				return \XF::language()->numberFormat($value);
			}, $results);
		}
		else
		{
			$results = $results->pluck(function (\XF\Entity\User $user): array
			{
				return [$user->user_id, null];
			}, false);
		}
		return $results;
	}
}