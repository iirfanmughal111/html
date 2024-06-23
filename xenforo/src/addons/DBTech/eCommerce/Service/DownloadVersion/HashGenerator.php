<?php

namespace DBTech\eCommerce\Service\DownloadVersion;

use DBTech\eCommerce\Entity\DownloadVersion;
use XF\Util\File;

/**
 * Class HashGenerator
 *
 * @package DBTech\eCommerce\Service\DownloadVersion
 */
class HashGenerator extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\DownloadVersion */
	protected $version;
	
	/** @var array */
	protected $hashParams;
	
	/** @var string */
	protected $uploadRoot;
	
	/** @var string */
	protected $hashFile;
	
	/** @var string */
	protected $hashFormat;
	
	/** @var bool */
	protected $writeHashes = true;
	
	/** @var array */
	protected $filesToHash = [];
	
	/** @var bool */
	protected $filesPrepared = false;
	
	/**
	 * HashGenerator constructor.
	 *
	 * @param \XF\App $app
	 * @param DownloadVersion $version
	 * @param array $hashParams
	 * @param string $uploadRoot
	 * @param string $hashFile
	 * @param string $hashFormat
	 */
	public function __construct(
		\XF\App $app,
		DownloadVersion $version,
		array $hashParams,
		string $uploadRoot,
		string $hashFile = '',
		string $hashFormat = 'xf2'
	) {
		parent::__construct($app);
		
		$this->version = $version;
		
		$this->hashParams = $hashParams;
		$this->uploadRoot = $uploadRoot;
		$this->hashFile = $hashFile;
		$this->hashFormat = $hashFormat;
	}

	/**
	 * @param bool $writeHashes
	 *
	 * @return $this
	 */
	public function setWriteHashes(bool $writeHashes): HashGenerator
	{
		$this->writeHashes = $writeHashes;

		return $this;
	}

	/**
	 *
	 */
	protected function prepareFilesToHash()
	{
		$uploadRoot = $this->uploadRoot;

		$filesIterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($uploadRoot, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::CHILD_FIRST
		);
		foreach ($filesIterator AS $file)
		{
			if ($file->isDir())
			{
				continue;
			}

			// skip hidden dot files, e.g. .DS_Store, .gitignore etc.
			if (strpos($file->getFilename(), '.') === 0)
			{
				continue;
			}

			// don't hash the hashes file if it already exists
			if ($file->getFilename() == pathinfo($this->hashFile, PATHINFO_FILENAME))
			{
				continue;
			}
			
			// Skip .lock files
			if ($file->getExtension() == 'lock')
			{
				continue;
			}

			$this->filesToHash[] = $file->getPathname();
		}

		$this->filesPrepared = true;
	}
	
	/**
	 * Generates the hashes for the given root path. Optionally writes to the specified path (if provided).
	 *
	 * @return array|string The generated hashes JSON
	 *
	 * @throws \InvalidArgumentException
	 * @throws \ErrorException
	 */
	public function generate()
	{
		if (!$this->filesPrepared)
		{
			$this->prepareFilesToHash();
		}

		$output = [];

		foreach ($this->filesToHash AS $path)
		{
			if (!file_exists($path))
			{
				continue;
			}

			$path = $this->standardizeSeparator($path);
			$root = $this->standardizeSeparator($this->uploadRoot);

			$key = preg_replace('#^' . $root . '/#', '', $path, 1);

			switch ($this->hashFormat)
			{
				case 'vb34':
				case 'xf1':
					$output[$key] = \XF\Util\Hash::hashTextFile($path, 'md5');
					break;

				case 'xf2':
					$output[$key] = \XF\Util\Hash::hashTextFile($path, 'sha256');
					break;
			}
		}

		ksort($output, SORT_NATURAL | SORT_FLAG_CASE);

		switch ($this->hashFormat)
		{
			case 'xf2':
			case 'xf1':
				$output = \XF\Util\Json::jsonEncodePretty($output);
				break;

			case 'vb34':
				$output = '<?php
					$md5_sums = ' . var_export($output, true) . ';
					$md5_sum_softwareid = \'' . $this->hashParams['varname'] . '\';
				?>';
				break;
		}

		if ($this->writeHashes)
		{
			if (!$this->hashFile)
			{
				throw new \InvalidArgumentException('Trying to write hashes file, but no hash file provided.');
			}

			$written = File::writeFile($this->uploadRoot . \XF::$DS . $this->hashFile, $output, false);
			if (!$written)
			{
				throw new \ErrorException('Unexpected failure while writing hashes to provided path.');
			}
		}

		return $output;
	}
	
	/**
	 * @param string $path
	 *
	 * @return string
	 */
	protected function standardizeSeparator(string $path): string
	{
		return str_replace('\\', '/', $path);
	}
}