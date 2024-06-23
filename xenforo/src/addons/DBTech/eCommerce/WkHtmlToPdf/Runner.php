<?php

namespace DBTech\eCommerce\WkHtmlToPdf;

class Runner
{
	/** @var string */
	protected $path;

	/** @var string */
	protected $fileName;
	
	
	/**
	 * Runner constructor.
	 *
	 * @param string $path
	 * @param bool $validatePath
	 *
	 * @throws \Exception
	 */
	public function __construct(string $path, bool $validatePath = true)
	{
		$this->setWkHtmlToPdfPath($path, $validatePath);
	}
	
	/**
	 * @param string $path
	 * @param string $validatePath
	 *
	 * @throws \Exception
	 */
	protected function setWkHtmlToPdfPath(string $path, string $validatePath)
	{
		if ($validatePath)
		{
			/** @var \DBTech\eCommerce\Validator\WkHtmlToPdf $validator */
			$validator = \XF::app()->validator('DBTech\eCommerce:WkHtmlToPdf');
			$validator->setOption('verify_executable', false);

			$path = $validator->coerceValue($path);
			if (!$validator->isValid($path, $errorKey))
			{
				throw new \LogicException($validator->getPrintableErrorValue($errorKey));
			}
		}

		$this->path = $path;
	}
	
	/**
	 * @param string $fileName
	 */
	public function setFileName(string $fileName)
	{
		if (file_exists($fileName) && is_file($fileName) && is_readable($fileName))
		{
			$this->fileName = $fileName;
		}
		else
		{
			$this->fileName = null;
			throw new \InvalidArgumentException("File '$fileName' does not exist or cannot be read");
		}
	}
	
	/**
	 * @return string
	 */
	public function getFileName(): string
	{
		return $this->fileName;
	}
	
	/**
	 * @return string|null
	 */
	public function getVersionNumber(): ?string
	{
		$output = $this->run('--version');

		foreach ($output AS $line)
		{
			$line = trim($line);
			if (preg_match('/(\d+\.\d+\.?\d?)/is', $line, $matches))
			{
				if (isset($matches[1]))
				{
					return $matches[1];
				}
			}
		}

		return null;
	}
	
	/**
	 * @param string $command
	 * @param array $args
	 * @param null $return
	 *
	 * @return array
	 */
	public function run(string $command, array $args = [], &$return = null): array
	{
		$path = escapeshellarg($this->path);

		$origCommand = $command;

		preg_match_all('#\{([a-z0-9_]+)}#i', $command, $matches, PREG_SET_ORDER);
		foreach ($matches AS $match)
		{
			$key = $match[1];
			if (!isset($args[$key]))
			{
				throw new \InvalidArgumentException("Command '$origCommand' did not provide argument '$key'");
			}

			$value = escapeshellarg($args[$key]);
			$command = str_replace($match[0], $value, $command);
		}

		$output = [];
		exec("$path $command 2>&1", $output, $return);

		return $output;
	}
}