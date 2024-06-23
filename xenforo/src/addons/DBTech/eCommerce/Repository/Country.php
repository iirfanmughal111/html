<?php /** @noinspection PhpUndefinedClassInspection */

namespace DBTech\eCommerce\Repository;

use DBTech\eCommerce\Exception\CountryCodeMismatchException;
use DBTech\eCommerce\Exception\InvalidVatNumberException;
use XF\Mvc\Entity\Repository;

/**
 * Class Country
 * @package DBTech\eCommerce\Repository
 */
class Country extends Repository
{
	/**
	 * @return \DBTech\eCommerce\Finder\Country
	 */
	public function findCountriesForList(): \DBTech\eCommerce\Finder\Country
	{
		return $this->finder('DBTech\eCommerce:Country')
			->orderName();
	}
	
	/**
	 * @return \DBTech\eCommerce\Finder\Country
	 */
	public function findCountriesWithTaxForList(): \DBTech\eCommerce\Finder\Country
	{
		return $this->finder('DBTech\eCommerce:Country')
			->where('sales_tax_rate', '<>', -1.000)
			->orderName();
	}
	
	/**
	 * @param string $countryCode
	 *
	 * @return \DBTech\eCommerce\Finder\Country
	 */
	public function findCountryByCode(string $countryCode): \DBTech\eCommerce\Finder\Country
	{
		return $this->finder('DBTech\eCommerce:Country')
			->where('country_code', $countryCode);
	}
	
	/**
	 * @param bool $includeEmpty
	 * @param string|null $type
	 *
	 * @return array
	 */
	public function getCountryOptionsData(bool $includeEmpty = true, ?string $type = null): array
	{
		$choices = [];
		if ($includeEmpty)
		{
			$choices = [
				'' => ['value' => '', 'label' => \XF::phrase('(none)')]
			];
			if ($type !== null)
			{
				$choices['']['_type'] = $type;
			}
		}
		
		$countryList = $this->findCountriesForList();
		
		/** @var \DBTech\eCommerce\Entity\Country $entry */
		foreach ($countryList AS $entry)
		{
			$choices[$entry->country_code] = [
				'value' => $entry->country_code,
				'label' => $entry->name
			];
			if ($type !== null)
			{
				$choices[$entry->country_code]['_type'] = $type;
			}
		}
		
		return $choices;
	}
	
	/**
	 * @param bool $includeEmpty
	 * @param bool $includeAll
	 *
	 * @return array
	 */
	public function getCountrySelectData(bool $includeEmpty = true, bool $includeAll = false): array
	{
		$choices = [];
		if ($includeEmpty)
		{
			$choices = [
				'' => \XF::phrase('(none)')
			];
		}
		if ($includeAll)
		{
			$choices = [
				'-1' => \XF::phrase('(all)')
			];
		}
		
		$countryList = $this->findCountriesForList();
		
		/** @var \DBTech\eCommerce\Entity\Country $entry */
		foreach ($countryList AS $entry)
		{
			$choices[$entry->country_code] = $entry->name;
		}
		
		return $choices;
	}
	
	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function updateCountryList(): bool
	{
		$reader = $this->app()
			->http()
			->reader()
		;

		$countryList = null;
		
		try
		{
			/** @noinspection HttpUrlsUsage */
			$response = $reader->getUntrusted(
				'https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json'
			);
			if ($response)
			{
				$jsonText = $response->getBody()->getContents();
				
				$response->getBody()->close();
				
				if ($response->getStatusCode() == 200)
				{
					try
					{
						$countryList = \GuzzleHttp\json_decode($jsonText, true);
					}
					catch (\InvalidArgumentException $e)
					{
						\XF::logException($e, false, 'eCommerce error:');
						return false;
					}
				}
				else
				{
					\XF::logError(\XF::phraseDeferred('received_unexpected_response_code_x_message_y', [
						'code' => $response->getStatusCode(),
						'message' => $response->getReasonPhrase()
					]));
					return false;
				}
			}
		}
		catch (\GuzzleHttp\Exception\RequestException $e)
		{
			\XF::logException($e, false, 'eCommerce error:');
			return false;
		}

		if (!is_array($countryList))
		{
			return false;
		}
		
		/** @var \DBTech\eCommerce\Entity\Country[]|\XF\Mvc\Entity\ArrayCollection $existingCountries */
		$existingCountries = $this->findCountriesForList()->fetch();
		
		$currentCountries = [];
		foreach ($countryList as $country)
		{
			$nativeName = \reset($country['name']['native']);
			if ($nativeName === false)
			{
				$nativeName = $country['name'];
			}

			/** @var \DBTech\eCommerce\Entity\Country $newCountry */
			if (!isset($existingCountries[$country['cca2']]))
			{
				$newCountry = $this->em->create('DBTech\eCommerce:Country');
				$newCountry->bulkSet([
					'country_code' => $country['cca2'],
					'name' => $country['name']['common'],
					'native_name' => $nativeName['common'],
					'iso_code' => $country['cca3'],
				]);
				$newCountry->save();
			}
			else
			{
				$newCountry = $existingCountries[$country['cca2']];

				if ($newCountry->name != $country['name']['common'])
				{
					$newCountry->name = $country['name']['common'];
				}

				if ($newCountry->native_name != $nativeName['common'])
				{
					$newCountry->native_name = $nativeName['common'];
				}

				$newCountry->saveIfChanged();
			}
			$currentCountries[$country['cca2']] = $newCountry;
		}
		
		$missingCountries = array_diff_key($existingCountries->toArray(), $currentCountries);
		
		/** @var \DBTech\eCommerce\Entity\Country $country */
		foreach ($missingCountries as $country)
		{
			$country->delete();
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \UnexpectedValueException
	 */
	public function updateVatRates(): bool
	{
		// Ensure the path exists, we can't use the remote data here
		$path = \XF\Util\File::getCodeCachePath() . DIRECTORY_SEPARATOR . 'dbtechEcommerce';
		\XF\Util\File::createDirectory($path);

		// Init the Ibericode libraries
		$rates = new \Ibericode\Vat\Rates($path . DIRECTORY_SEPARATOR . 'vatRates.txt');
		$countries = new \Ibericode\Vat\Countries();

		/** @var \DBTech\eCommerce\Entity\Country[]|\XF\Mvc\Entity\ArrayCollection $existingCountries */
		$existingCountries = $this->findCountriesForList()->fetch();

		foreach ($existingCountries as $country)
		{
			if (!$countries->isCountryCodeInEU($country->country_code))
			{
				// Non-EU country, skip this
				continue;
			}

			$newRate = $rates->getRateForCountry($country->country_code);
			if ($newRate != $country->sales_tax_rate)
			{
				// The tax rate has changed, so update it
				$country->fastUpdate('sales_tax_rate', $newRate);
			}
		}
		
		return true;
	}
	
	/**
	 * @param string $vatId
	 * @param string $countryCode
	 *
	 * @return bool
	 * @throws \Exception
	 * @throws \UnexpectedValueException
	 * @throws InvalidVatNumberException
	 * @throws CountryCodeMismatchException
	 */
	public function validateVatIdForCountry(string $vatId, string $countryCode): bool
	{
		// Extract country from VAT ID
		$vatId = \strtoupper($vatId);
		$country = substr($vatId, 0, 2);

		// Work around an issue where Greek VAT IDs start with EL
		if ($country == 'EL')
		{
			$country = 'GR';
		}

		if ($country != $countryCode)
		{
			throw new CountryCodeMismatchException('eCommerce error: Country code: ' . var_export($countryCode, true) . ' does not match ' . var_export($country, true));
		}

		if ($country === 'GB')
		{
			// I hate the Tory party.
			try
			{
				$lookupVatId = \strpos($vatId, 'GB') === 0 ? \substr($vatId, 2) : $vatId;
				$response = $this->app()->http()->reader()
					->getUntrusted(
						'https://api.service.hmrc.gov.uk/organisations/vat/check-vat-number/lookup/' . $lookupVatId,
						[
							'headers' => [
								'Accept' => 'application/vnd.hmrc.1.0+json'
							]
						]
					)
				;
				if ($response)
				{
					$jsonText = $response->getBody()->getContents();
					$response->getBody()->close();

					switch ($response->getStatusCode())
					{
						case 200:
						case 400:
						case 403:
							try
							{
								$json = \GuzzleHttp\json_decode($jsonText, true);

								if ($json)
								{
									$result = $json;
								}
								else
								{
									\XF::logError(\XF::phraseDeferred('received_unexpected_response_code_x_message_y', [
										'code'    => $response->getStatusCode(),
										'message' => $response->getReasonPhrase()
									]));
									return false;
								}
							}
							catch (\InvalidArgumentException $e)
							{
								\XF::logException($e, false, '[eCommerce] HMRC VAT lookup error:');
								return false;
							}

							if (!empty($result['code']))
							{
								throw new InvalidVatNumberException(
									'eCommerce error: Invalid VAT number: ' . var_export($vatId, true) .
									' (' . var_export($result['message'], true) . ')'
								);
							}
						break;

						default:
							\XF::logError(\XF::phraseDeferred('received_unexpected_response_code_x_message_y', [
								'code'    => $response->getStatusCode(),
								'message' => $response->getReasonPhrase()
							]));
							return false;
					}
				}
				else
				{
					\XF::logError("[eCommerce] HMRC VAT lookup error: No Response.");
				}
			}
			catch (\GuzzleHttp\Exception\RequestException $e)
			{
				\XF::logException($e, false, "[eCommerce] HMRC VAT lookup error: ");
				return false;
			}
		}
		else
		{
			// Please take us back
			try
			{
				$validator = new \Ibericode\Vat\Validator();
				$result = $validator->validateVatNumber($vatId);
				if (!$result)
				{
					throw new InvalidVatNumberException('eCommerce error: Invalid VAT number: ' . var_export($vatId, true));
				}
			}
			catch (\Ibericode\Vat\Vies\ViesException $e)
			{
				throw new \UnexpectedValueException('eCommerce error: Invalid VAT Rate API response: ' . var_export($e->getMessage(), true));
			}
		}
		
		return true;
	}
	
	/**
	 * @param string $countryCode
	 *
	 * @return bool
	 * @throws \Exception
	 * @throws \UnexpectedValueException
	 * @throws CountryCodeMismatchException
	 */
	public function validateBillingAddressCountry(string $countryCode): bool
	{
		$ip = $this->app()->request()->getIp();
		
		// If localhost, use DBTech's server IP for debugging purposes
		$ip = in_array($ip, ['::1', '127.0.0.1', '172.18.0.1']) ? '78.157.218.102' : $ip;
		
		/** @var \DBTech\eCommerce\Service\GeoLocate $geoLocateService */
		$geoLocateService = \XF::app()->service('DBTech\eCommerce:GeoLocate');
		$record = $geoLocateService->lookup($ip);
		
		if (!isset($record['country_code']))
		{
			throw new \UnexpectedValueException('eCommerce error: Invalid API response: ' . var_export($record, true));
		}
		
		if ($record['country_code'] != $countryCode)
		{
			throw new CountryCodeMismatchException('eCommerce error: Country code: ' . var_export($countryCode, true) . ' does not match ' . var_export($record['country_code'], true));
		}
		
		return true;
	}
}