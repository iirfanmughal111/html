<?php /** @noinspection PhpUndefinedClassInspection */

namespace DBTech\eCommerce\Service;

/**
 * Class GeoLocate
 *
 * @package DBTech\eCommerce\Service
 */
class GeoLocate extends \XF\Service\AbstractService
{
	/**
	 * @var \GeoIp2\Database\Reader
	 */
	protected $reader = null;

	/**
	 * @var string
	 */
	protected $type;


	/**
	 * GeoLocate constructor.
	 *
	 * @param \XF\App $app
	 */
	public function __construct(\XF\App $app)
	{
		parent::__construct($app);

		$mmDbFile = \XF\Util\File::getCodeCachePath() .
			DIRECTORY_SEPARATOR . 'dbtechEcommerce' . DIRECTORY_SEPARATOR . 'GeoLite2-Country.mmdb';

		if (function_exists('geoip_record_by_name'))
		{
			// We have the function for this
			$this->type = 'function';
		}
		elseif (
			file_exists($mmDbFile)
			&& is_readable($mmDbFile)
		) {
			try
			{
				// Set the reader
				$this->reader = new \GeoIp2\Database\Reader($mmDbFile);

				// The reader seems to work
				$this->type = 'reader';
			}
			catch (\MaxMind\Db\Reader\InvalidDatabaseException $e)
			{
				\XF::logException($e, false, "[eCommerce] Invalid GeoIp2 database: ");
			}
		}
		else
		{
			$handled = false;
			$this->_handleFallback($handled);

			if (!$handled)
			{
				$this->type = 'web_service';
			}
		}

		$this->type = 'web_service';
	}

	/**
	 * @param string $ip
	 *
	 * @return array|bool
	 */
	public function lookup(string $ip)
	{
		$ipString = \XF\Util\Ip::convertIpBinaryToString($ip);
		$ip = $ipString ?: $ip;

		// If localhost, use DBTech's server IP for debugging purposes
		$ip = ($ip == '127.0.0.1' || $ip == '::1') ? '78.157.218.102' : $ip;

		$method = '_lookup' . \XF\Util\Php::camelCase($this->type);
		if (method_exists($this, $method))
		{
			return $this->$method($ip);
		}

		return [
			'country_code' => '',
			'country_name' => ''
		];
	}

	/**
	 * @param string $ip
	 *
	 * @return array|bool
	 */
	protected function _lookupReader(string $ip)
	{
		try
		{
			/** @var \GeoIp2\Model\Country $record */
			$record = $this->reader->country($ip);
		}
		catch (\MaxMind\Db\Reader\InvalidDatabaseException $e)
		{
			\XF::logException($e, false, "[eCommerce] Invalid GeoIp2 database: ");
			return false;
		}
		catch (\GeoIp2\Exception\AddressNotFoundException $e)
		{
			return false;
		}

		return [
			// 'continent_code' 	=> NA,
			'country_code' => $record->country->isoCode,
			// 'country_code3' 	=> USA,
			'country_name' => $record->country->name
		];
	}

	/**
	 * @param string $ip
	 *
	 * @return array|bool
	 */
	protected function _lookupFunction(string $ip)
	{
		if (!\function_exists('geoip_record_by_name'))
		{
			return false;
		}

		$record = \geoip_record_by_name($ip);

		if (\is_array($record))
		{
			// Get rid of some stuff that the "reader" object doesn't have
			unset($record['continent_code'], $record['country_code3'], $record['dma_code'], $record['area_code']);

			return $record;
		}

		return false;
	}

	/**
	 * @param string $ip
	 *
	 * @return array|bool
	 */
	protected function _lookupWebService(string $ip)
	{
		$result = null;

		try
		{
			$response = $this->app->http()->reader()
				->getUntrusted('http://ip-api.com/json/' . $ip)
			;
			if ($response)
			{
				$jsonText = $response->getBody()->getContents();

				$response->getBody()->close();

				if ($response->getStatusCode() == 200)
				{
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
						\XF::logException($e, false, '[eCommerce] WebService error:');
						return false;
					}
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
		}
		catch (\GuzzleHttp\Exception\RequestException $e)
		{
			\XF::logException($e, false, "[eCommerce] WebService error: ");
			return false;
		}

		if (!is_array($result))
		{
			\XF::logError('Unexpected result from WebService: ' . var_export($result, true));
			return false;
		}

		if ($result['status'] === 'fail')
		{
			// Expected failure
			return false;
		}

		if (empty($result['country']))
		{
			\XF::logError('Unexpected result from WebService: ' . var_export($result, true));
			return false;
		}

		return [
			// 'continent_code' 	=> NA,
			'country_code' => $result['countryCode'],
			// 'country_code3' 	=> USA,
			'country_name' => $result['country'],
			'region'       => '',
			'city'         => $result['city'],
			'postal_code'  => $result['zip'],
			'latitude'     => $result['lat'],
			'longitude'    => $result['lon'],
			// 'dma_code' 			=> 803,
			// 'area_code' 		=> 310,
		];
	}

	/**
	 * @param bool $handled
	 */
	protected function _handleFallback(bool &$handled)
	{
	}
}