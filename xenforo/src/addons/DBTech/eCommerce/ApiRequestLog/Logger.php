<?php

namespace DBTech\eCommerce\ApiRequestLog;

class Logger
{
	/**
	 * @param string $apiKey
	 * @param string $requestUri
	 * @param string $referrer
	 * @param string $boardUrl
	 * @param string $httpHost
	 * @param string $software
	 * @param string $softwareVersion
	 * @param array $params
	 * @param bool $throw
	 * @param \XF\Entity\User|null $actor
	 *
	 * @return \DBTech\eCommerce\Entity\ApiRequestLog|null
	 * @throws \XF\PrintableException
	 */
	public function log(
		string $apiKey,
		string $requestUri,
		string $referrer,
		string $boardUrl,
		string $httpHost,
		string $software,
		string $softwareVersion,
		array $params = [],
		bool $throw = true,
		\XF\Entity\User $actor = null
	): ?\DBTech\eCommerce\Entity\ApiRequestLog {
		$actor = $actor ?: \XF::visitor();
		
		/** @var \DBTech\eCommerce\Entity\ApiRequestLog $log */
		$log = \XF::em()->create('DBTech\eCommerce:ApiRequestLog');
		$log->api_key = $apiKey;
		$log->user_id = $actor->user_id;
		$log->ip_address = \XF\Util\Ip::convertIpStringToBinary(
			\XF::app()
			->request()
			->getIp()
		);
		$log->log_date = \XF::$time;
		$log->request_uri = $requestUri;
		$log->referrer = $referrer;
		$log->board_url = $boardUrl;
		$log->http_host = $httpHost;
		$log->software = $software;
		$log->software_version = $softwareVersion;
		$log->raw_data = $this->checkUtf8($params);
		$log->save($throw, false);
		
		return $log;
	}

	/**
	 * @param array $var
	 *
	 * @return array
	 */
	protected function checkUtf8(array $var): array
	{
		array_walk_recursive($var, function (&$v, $k)
		{
			if (is_string($v))
			{
				$v = mb_convert_encoding($v, 'UTF-8', 'UTF-8');
			}
		});

		return $var;
	}
}