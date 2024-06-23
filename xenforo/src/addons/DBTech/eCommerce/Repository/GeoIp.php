<?php /** @noinspection PhpUndefinedClassInspection */

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;
use XF\Util\File;

/**
 * Class GeoIp
 * @package DBTech\eCommerce\Repository
 */
class GeoIp extends Repository
{
	/**
	 * @return bool
	 */
	public function geoIpUpdate(): bool
	{
		try
		{
			$tempFile = File::getNamedTempFile('GeoLite2-Country.tar.gz');
			$tarFile = File::getNamedTempFile('GeoLite2-Country.tar');
			$extractionDir = File::createTempDir();
			$dbName = 'GeoLite2-Country.mmdb';
			$cacheFile = 'code-cache://dbtechEcommerce/' . $dbName;

			$licenseKey = $this->options()->offsetExists('dbtechEcommerceMaxMindApiKey')
				? $this->options()->dbtechEcommerceMaxMindApiKey
				: 'uWQJi5jw2do0flok'
			;
			if (empty($licenseKey))
			{
				\XF::logError("[eCommerce] Error updating GeoIP: No MaxMind API Key has been set. Check your settings.");
				return false;
			}
			
			$response = $this->app()->http()->reader()->getUntrusted(
				'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&license_key=' . $licenseKey . '&suffix=tar.gz'
			);
			$stream = $response->getBody();
			
			do
			{
				file_put_contents($tempFile, $stream->read(1024), FILE_APPEND);
			}
			while (!$stream->eof());

			@stream_wrapper_restore('phar');

			// decompress from gz
			$p = new \PharData($tempFile);
			$p->decompress(); // creates /path/to/my.tar

			$filePath = $p->getFilename() . DIRECTORY_SEPARATOR . $dbName;

			// unarchive from the tar
			$phar = new \PharData($tarFile);
			$phar->extractTo($extractionDir, $filePath);

			File::copyFileToAbstractedPath($extractionDir . DIRECTORY_SEPARATOR . $filePath, $cacheFile);

			@stream_wrapper_unregister('phar');
		}
		catch (\GuzzleHttp\Exception\RequestException | \UnexpectedValueException | \BadMethodCallException $e)
		{
			\XF::logException($e, false, "[eCommerce] Error updating GeoIP: ");
			return false;
		}

		return true;
	}
}