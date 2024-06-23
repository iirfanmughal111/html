<?php

namespace DBTech\Credits\Repository;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Currency
 * @package DBTech\Credits\Repository
 */
class Currency extends Repository
{
	/**
	 * @return array
	 */
	public function getCacheData(): array
	{
		$cache = [];
		
		/** @var \DBTech\Credits\Entity\Currency[] $entities */
		$entities = $this->finder('DBTech\Credits:Currency')->fetch();
		foreach ($entities as $entity)
		{
			$cache[$entity->getIdentifier()] = $entity->toArray(false);
		}
		
		return $cache;
	}
	
	/**
	 * @return array
	 */
	public function rebuildCache(): array
	{
		$cache = $this->getCacheData();
		\XF::registry()->set('dbtCreditsCurrencies', $cache);
		return $cache;
	}
	
	/**
	 * @return Finder
	 */
	public function findCurrenciesForList(): Finder
	{
		/** @var \DBTech\Credits\Finder\Currency $finder */
		$finder = $this->finder('DBTech\Credits:Currency');
		
		return $finder->orderForList();
	}
	
	/**
	 * @param bool $onlyActive
	 *
	 * @return array|\XF\Mvc\Entity\ArrayCollection
	 */
	public function getCurrencyTitlePairs(bool $onlyActive = false)
	{
		$currencyFinder = $this->findCurrenciesForList();
		
		$currencies = $currencyFinder->fetch();
		if ($onlyActive)
		{
			$currencies = $currencies->filterViewable();
		}
		
		return $currencies->pluckNamed('title', 'currency_id');
	}
	
	/**
	 * @param bool $includeEmpty
	 * @param null $type
	 *
	 * @return array
	 */
	public function getCurrencyOptionsData($includeEmpty = true, $type = null): array
	{
		$choices = [];
		if ($includeEmpty)
		{
			$choices = [
				0 => ['_type' => 'option', 'value' => 0, 'label' => \XF::phrase('(none)')]
			];
		}
		
		$currencies = $this->getCurrencyTitlePairs();
		
		foreach ($currencies AS $currencyId => $label)
		{
			$choices[$currencyId] = [
				'value' => $currencyId,
				'label' => $label
			];
			if ($type !== null)
			{
				$choices[$currencyId]['_type'] = $type;
			}
		}
		
		return $choices;
	}
	
	/**
	 * @param bool $filterViewable
	 *
	 * @return \DBTech\Credits\Entity\Currency[]|\XF\Mvc\Entity\ArrayCollection
	 */
	public function getCurrencies(bool $filterViewable = false)
	{
		$container = $this->app()->container();
		if (isset($container['dbtechCredits.currencies']) && $currencies = $container['dbtechCredits.currencies'])
		{
			if ($filterViewable)
			{
				$currencies = $currencies->filterViewable();
			}
			
			return $currencies;
		}
		
		return $this->em->getEmptyCollection();
	}
	
	/**
	 * @return \DBTech\Credits\Entity\Currency[]|\XF\Mvc\Entity\ArrayCollection
	 */
	public function getViewableCurrencies()
	{
		return $this->getCurrencies(true);
	}
	
	/**
	 * @return Finder
	 */
	public function getDisplayCurrency(): Finder
	{
		return $this->finder('DBTech\Credits:Currency')
			->where('is_display_currency', 1);
	}
	
	/**
	 * @return \DBTech\Credits\Entity\Currency
	 */
	public function getChargeCurrency(): \DBTech\Credits\Entity\Currency
	{
		$options = $this->options();
		$currencyId = $options->dbtech_credits_eventtrigger_content_currency;
		
		if (!$currencyId)
		{
			/** @var \DBTech\Credits\Entity\Currency $currency */
			$currency = $this->finder('DBTech\Credits:Currency')
				->fetchOne()
			;
			
			/** @var \XF\Repository\Option $optionRepo */
			$optionRepo = $this->repository('XF:Option');
			$optionRepo->updateOptions([
				'dbtech_credits_eventtrigger_content_currency' => $currency->currency_id
			]);
			
			$currencyId = $currency->currency_id;
		}
		
		/** @var \DBTech\Credits\Entity\Currency $currency */
		$currency = $this->em->find('DBTech\Credits:Currency', $currencyId);
		
		return $currency;
	}
	
	/**
	 * @param \DBTech\Credits\Entity\Currency $currency
	 * @param int $limit
	 *
	 * @return Finder
	 */
	public function getRichestUsers(\DBTech\Credits\Entity\Currency $currency, int $limit = 5): Finder
	{
		return $this->finder('XF:User')
			->isValidUser()
			->order($currency->column, 'DESC')
			->limit($limit)
			;
	}
	
	/**
	 * @param ArrayCollection|null $currencies
	 */
	public function resetCurrencies(?ArrayCollection $currencies = null)
	{
		if ($currencies === null)
		{
			$currencies = $this->getCurrencies();
		}
		
		/** @var \DBTech\Credits\Entity\Currency $currency */
		foreach ($currencies as $currency)
		{
			$this->db()->update('xf_user', [
				$currency->column => 0
			], null);
		}
	}

	/**
	 * @param int $userId
	 * @param int $currencyId
	 *
	 * @return float
	 */
	public function getUserBalanceFromTransactionLog(int $userId, int $currencyId): float
	{
		/** @var \DBTech\Credits\Entity\Transaction $latestTransaction */
		$latestTransaction = $this->finder('DBTech\Credits:Transaction')
			->where('user_id', $userId)
			->where('currency_id', $currencyId)
			->order('dateline', 'desc')
			->fetchOne()
		;
		if (!$latestTransaction)
		{
			return 0.00;
		}

		return $latestTransaction->balance;
	}
}