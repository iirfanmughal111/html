<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF;

/**
 * Class Logger
 *
 * @package DBTech\eCommerce\XF
 */
class Logger extends XFCP_Logger
{
	/**
	 * @var \DBTech\eCommerce\ApiRequestLog\Logger|null
	 */
	protected $dbtechEcommerceApiRequestLogger;
	
	
	/**
	 * @return \DBTech\eCommerce\ApiRequestLog\Logger
	 * @throws \Exception
	 */
	public function dbtechEcommerceApiRequestLogger()
	{
		if (!$this->dbtechEcommerceApiRequestLogger)
		{
			$class = 'DBTech\eCommerce\ApiRequestLog\Logger';
			$class = $this->app->extendClass($class);
			
			$this->dbtechEcommerceApiRequestLogger = new $class();
		}
		
		return $this->dbtechEcommerceApiRequestLogger;
	}
	
	/**
	 * @param $apiKey
	 * @param $requestUri
	 * @param $referrer
	 * @param $boardUrl
	 * @param $httpHost
	 * @param $software
	 * @param $softwareVersion
	 * @param array $params
	 * @param bool $throw
	 *
	 * @return \DBTech\eCommerce\Entity\ApiRequestLog|null
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function logDbtechEcommerceApiRequest(
		$apiKey,
		$requestUri,
		$referrer,
		$boardUrl,
		$httpHost,
		$software,
		$softwareVersion,
		array $params = [],
		$throw = true
	) {
		return $this->dbtechEcommerceApiRequestLogger()
			->log($apiKey, $requestUri, $referrer, $boardUrl, $httpHost, $software, $softwareVersion, $params, $throw)
			;
	}
}