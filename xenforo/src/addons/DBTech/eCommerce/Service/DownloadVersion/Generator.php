<?php

namespace DBTech\eCommerce\Service\DownloadVersion;

use DBTech\eCommerce\Entity\DownloadVersion;
use DBTech\eCommerce\Entity\License;

use \XF\Mvc\Controller;

use XF\Util\File;
use XF\Service\AddOn\JsMinifier;

/**
 * Class Generator
 *
 * @package DBTech\eCommerce\Service\DownloadVersion
 */
class Generator extends \XF\Service\AbstractService
{
	/** @var \XF\Mvc\Controller */
	protected $controller;
	
	/** @var \DBTech\eCommerce\Entity\DownloadVersion */
	protected $version;
	
	/** @var \DBTech\eCommerce\Entity\License */
	protected $license;
	
	/** @var string */
	protected $buildRoot;
	
	/** @var string */
	protected $uploadRoot;
	
	/** @var string */
	protected $tempFile;

	/** @var \RecursiveIteratorIterator|\SplFileInfo[] */
	protected $localFs;

	/** @var \ZipArchive */
	protected $zipArchive;
	
	/** @var bool */
	protected $generateHashes = true;
	
	/** @var bool */
	protected $hashesGenerated = false;
	
	/** @var bool */
	protected $skipBuildTasks = false;
	
	/** @var bool */
	protected $buildTasksComplete = false;


	/**
	 * Generator constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\DownloadVersion $version
	 * @param \DBTech\eCommerce\Entity\License|null $license
	 */
	public function __construct(\XF\App $app, DownloadVersion $version, ?License $license = null)
	{
		parent::__construct($app);
		
		$this->version = $version;
		$this->license = $license;

		$this->prepareDirectories();
		$this->prepareFilesToCopy();
		$this->prepareFsAdapters();
	}

	/**
	 * @param \XF\Mvc\Controller $controller
	 *
	 * @return $this
	 */
	public function setController(Controller $controller): Generator
	{
		$this->controller = $controller;

		return $this;
	}

	/**
	 * @return \XF\Mvc\Controller
	 */
	public function getController(): Controller
	{
		return $this->controller;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\DownloadVersion
	 */
	public function getVersion(): DownloadVersion
	{
		return $this->version;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\License
	 */
	public function getLicense(): License
	{
		return $this->license;
	}

	/**
	 * @return string
	 */
	public function getBuildRoot(): string
	{
		return $this->buildRoot;
	}

	/**
	 * @return string
	 */
	public function getUploadRoot(): string
	{
		return $this->uploadRoot;
	}

	/**
	 * @param bool $generate
	 *
	 * @return $this
	 */
	public function setGenerateHashes(bool $generate): Generator
	{
		$this->generateHashes = $generate;

		return $this;
	}

	/**
	 * @param bool $skip
	 *
	 * @return $this
	 */
	public function setSkipBuildTasks(bool $skip): Generator
	{
		$this->skipBuildTasks = $skip;

		return $this;
	}

	/**
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 */
	protected function prepareDirectories(): Generator
	{
		$ds = \XF::$DS;

		$buildDir = File::getTempDir() . $ds . 'xf' . microtime(true);

		$uploadDir = $buildDir . $ds . 'upload';

		if (file_exists($buildDir))
		{
			File::deleteDirectory($buildDir);
		}

		File::createDirectory($buildDir, false);
		File::createDirectory($uploadDir, false);

		$this->buildRoot = $buildDir;
		$this->uploadRoot = $uploadDir;

		return $this;
	}

	/**
	 *
	 */
	protected function prepareFilesToCopy()
	{
		$ds = \XF::$DS;

		$exclude = [];

		$directories = $this->version->getDirectoryList();

		foreach ($directories as $directory)
		{
			$filesIterator = File::getRecursiveDirectoryIterator($directory);
			foreach ($filesIterator AS $file)
			{
				$path = File::stripRootPathPrefix($file->getPathname(), $directory);
				if ($this->isPartOfExcludedDirectory($path))
				{
					continue;
				}

				if ($this->isExcludedFileName($file->getFilename()))
				{
					$exclude[$file->getPathname()] = true;
					continue;
				}
				
				if (array_key_exists($file->getPath(), $exclude))
				{
					$exclude[$file->getPathname()] = true;
					continue;
				}
				
				if (!$file->isDir())
				{
					if ($path === 'build.json')
					{
						continue;
					}
					
					File::copyFile($file->getPathname(), $this->buildRoot . $ds . $path, false);
				}
			}
		}
	}
	
	/**
	 *
	 */
	protected function prepareFsAdapters()
	{
		$this->localFs = File::getRecursiveDirectoryIterator($this->buildRoot);
		$this->tempFile = File::getTempFile() . '.zip';

		$zipArchive = new \ZipArchive();
		$zipArchive->open($this->tempFile, \ZipArchive::CREATE);
		$this->zipArchive = $zipArchive;
	}

	/**
	 * @param array $hashConfig
	 *
	 * @return array|null|string
	 * @throws \InvalidArgumentException
	 * @throws \XF\PrintableException
	 */
	public function generateHashes(array $hashConfig)
	{
		if ($this->hashesGenerated || !$this->generateHashes || empty($hashConfig))
		{
			return null;
		}

		$ds = \XF::$DS;

		$output = [];
		
		try
		{
			switch ($hashConfig['type'])
			{
				case 'xf1':
					/** @var HashGenerator $hashGenerator */
					$hashGenerator = $this->service(
						'DBTech\eCommerce:DownloadVersion\HashGenerator',
						$this->version,
						$hashConfig,
						$this->uploadRoot,
						rtrim($hashConfig['path'], $ds) . $ds . 'hashes.json',
						'xf1'
					);
					
					$output = $hashGenerator->generate();
					break;
				
				case 'xf2':
					/** @var HashGenerator $hashGenerator */
					$hashGenerator = $this->service(
						'DBTech\eCommerce:DownloadVersion\HashGenerator',
						$this->version,
						$hashConfig,
						$this->uploadRoot,
						rtrim($hashConfig['path'], $ds) . $ds . 'hashes.json',
						'xf2'
					);
					
					$output = $hashGenerator->generate();
					break;
				
				case 'vb34':
					if (empty($hashConfig['varname']))
					{
						throw new \InvalidArgumentException('The \'varname\' parameter is required to write vB3/vB4 hashes.');
					}
					
					/** @var HashGenerator $hashGenerator */
					$hashGenerator = $this->service(
						'DBTech\eCommerce:DownloadVersion\HashGenerator',
						$this->version,
						$hashConfig,
						$this->uploadRoot,
						rtrim($hashConfig['path'], $ds) . $ds . 'md5_sums_' . $hashConfig['varname'] . '.php',
						'vb34'
					);
					
					$output = $hashGenerator->generate();
					break;
			}
		}
		catch (\InvalidArgumentException | \ErrorException $e)
		{
			File::deleteDirectory($this->buildRoot);
			throw new \XF\PrintableException('Unexpected error while generating hashes: ' . $e->getMessage());
		}

		$this->hashesGenerated = true;

		return $output;
	}

	/**
	 * @throws \InvalidArgumentException
	 * @throws \XF\PrintableException
	 */
	public function performBuildTasks()
	{
		$version = $this->version;
		$buildJsonPath = $version->getBuildJsonPath();

		if ($this->buildTasksComplete || $this->skipBuildTasks || !file_exists($buildJsonPath))
		{
			return;
		}

		if (!$this->testBuildJson($error))
		{
			File::deleteDirectory($this->buildRoot);
			throw new \XF\PrintableException('Cannot build download due to build.json error' . ($error ? ': ' . $error : ''). '.');
		}

		$buildJson = $version->getBuildJson();

		$this->app->fire('dbtech_ecommerce_pre_build_tasks', [$this, $buildJson]);

		$this->replaceTokens($buildJson['replaceTokens']);
		$this->minifyJs($buildJson['minify']);
		$this->rollupJs($buildJson['rollup']);
		$this->execCmds($buildJson['exec']);
		$this->generateHashes($buildJson['hashes']);

		$this->app->fire('dbtech_ecommerce_post_build_tasks', [$this, $buildJson]);

		$this->buildTasksComplete = true;
	}

	/**
	 * @param null $error
	 *
	 * @return bool
	 */
	protected function testBuildJson(&$error = null): bool
	{
		$version = $this->version;

		$baseBuildJson = @json_decode(file_get_contents($version->getBuildJsonPath()), true);
		if (!is_array($baseBuildJson))
		{
			$error = json_last_error_msg();
			return false;
		}

		return true;
	}

	/**
	 * @param bool $replace
	 */
	protected function replaceTokens(bool $replace)
	{
		if (!$replace)
		{
			return;
		}
		
		/** @var \XF\Language $language */
		$language = \XF::language();
		$replacements = [
			'%YEAR%' 			=> $language->date(\XF::$time, 'Y'),
			'%TIME%' 			=> $language->time(\XF::$time, 'absolute'),
			'%LICENSEKEY%' 		=> $this->license ? $this->license->license_key : '',
			'%PRODUCT%' 		=> $this->version->Download->Product->title,
			'%PRODUCTID%' 		=> $this->version->Download->Product->product_id,
			'%VERSION%' 		=> $this->version->Download->version_string,
			'%VERSIONNUMBER%' 	=> str_replace('.', '', $this->version->Download->version_string),
		];
		
		$filesIterator = File::getRecursiveDirectoryIterator($this->uploadRoot);
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
			
			// Skip .lock files
			if (!in_array($file->getExtension(), [
				'php',
				'js',
				'xml'
			]))
			{
				continue;
			}
			
			if ($file->getFilename() == 'Generator.php')
			{
				// Crude hack to avoid replacing when downloading this file
				continue;
			}
			
			$output = file_get_contents($file->getPathname());
			$output = strtr($output, $replacements);
			
			File::writeFile($file->getPathname(), $output, false);
		}
	}
	
	/**
	 * @param mixed $minify
	 *
	 * @throws \InvalidArgumentException
	 * @throws \XF\PrintableException
	 */
	protected function minifyJs($minify)
	{
		if (!$minify)
		{
			return;
		}

		$uploadRoot = $this->uploadRoot;
		$ds = \XF::$DS;

		if (!is_array($minify) && $minify === '*')
		{
			$minify = [];

			$iterator = File::getRecursiveDirectoryIterator($uploadRoot . $ds . 'js');
			foreach ($iterator AS $file)
			{
				if ($file->isDir())
				{
					continue;
				}

				$fileName = $file->getBasename();

				if (strpos($fileName, '.js') === false || strpos($fileName, '.min.js') !== false)
				{
					continue;
				}
				$minify[] = str_replace($uploadRoot . $ds, '', $file->getPathname());
			}
		}

		foreach ($minify AS $file)
		{
			/** @var JsMinifier $minifier */
			$minifier = $this->service('XF:AddOn\JsMinifier', $uploadRoot . $ds . $file);

			try
			{
				$minifier->minify();
			}
			catch (\ErrorException $e)
			{
				File::deleteDirectory($this->buildRoot);
				throw new \XF\PrintableException('Unexpected error while minifying JS: ' . $e->getMessage());
			}
		}
	}

	/**
	 * @param array $rollup
	 */
	protected function rollupJs(array $rollup)
	{
		if (!$rollup)
		{
			return;
		}

		foreach ($rollup AS $rollupPath => $files)
		{
			$output = '';
			foreach ($files AS $file)
			{
				$output .= file_get_contents($this->uploadRoot . \XF::$DS . $file);
				$output .= "\n\n";
			}
			File::writeFile($this->uploadRoot . \XF::$DS . $rollupPath, trim($output), false);
		}
	}

	/**
	 * @param array $exec
	 */
	protected function execCmds(array $exec)
	{
		if (empty($exec))
		{
			return;
		}

		$version = $this->version;

		foreach ($exec AS $cmd)
		{
			$cmd = preg_replace_callback(
				'/({([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)})/',
				function ($match) use ($version): string
				{
					$placeholder = $match[1];
					$property = $match[2];

					$value = $version->{$property};

					if (!$value || !is_scalar($value))
					{
						return $placeholder;
					}

					return escapeshellarg($value);
				},
				$cmd
			);

			/** @noinspection DisconnectedForeachInstructionInspection */
			chdir($this->buildRoot);
			passthru($cmd);
		}
	}

	/**
	 * @return bool
	 *
	 * @throws \InvalidArgumentException
	 * @throws \ErrorException
	 * @throws \XF\PrintableException
	 */
	public function build(): bool
	{
		$this->performBuildTasks();

		$localFs = $this->localFs;
		$zipArchive = $this->zipArchive;

		foreach ($localFs AS $file)
		{
			// skip hidden dot files, e.g. .DS_Store, .gitignore etc.
			if ($this->isExcludedFileName($file->getBasename()))
			{
				continue;
			}

			$localName = str_replace('\\', '/', substr($file->getPathname(), strlen($this->buildRoot) + 1));

			if ($file->isDir())
			{
				$localName .= '/';
				$zipArchive->addEmptyDir($localName);
				$perm = 040755 << 16; // dir: 0755
			}
			else
			{
				$zipArchive->addFile($file->getPathname(), $localName);
				$perm = 0100644 << 16; // file: 0644
			}

			if (method_exists($zipArchive, 'setExternalAttributesName'))
			{
				/** @noinspection PhpUndefinedClassConstantInspection */
				$zipArchive->setExternalAttributesName($localName, \ZipArchive::OPSYS_UNIX, $perm);
			}
		}

		if (!$zipArchive->close())
		{
			File::deleteDirectory($this->buildRoot);
			throw new \ErrorException($zipArchive->getStatusString());
		}

		return true;
	}

	/**
	 *
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	public function finalizeRelease()
	{
		File::copyFileToAbstractedPath($this->tempFile, $this->version->getReleaseAbstractPath($this->license));

		File::deleteDirectory($this->buildRoot);
	}

	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	protected function isPartOfExcludedDirectory(string $path): bool
	{
		foreach ($this->getExcludedDirectories() AS $dir)
		{
			if (strpos($path, $dir) === 0)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * @return array
	 */
	protected function getExcludedDirectories(): array
	{
		return [
			'.git',
			'.svn',
		];
	}

	/**
	 * @param string $fileName
	 *
	 * @return bool
	 */
	protected function isExcludedFileName(string $fileName): bool
	{
		if ($fileName === '' || $fileName === false || $fileName === null)
		{
			return true;
		}

		if ($fileName[0] == '.' && $fileName != '.htaccess')
		{
			return true;
		}

		return false;
	}
}