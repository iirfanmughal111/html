<?php

namespace DBTech\eCommerce\Validator;

use XF\Validator\AbstractValidator;

/**
 * Class WkHtmlToPdf
 *
 * @package DBTech\eCommerce\Validator
 */
class WkHtmlToPdf extends AbstractValidator
{
	/** @var array  */
	protected $options = [
		'verify_executable' => true
	];
	
	/**
	 * @param $value
	 * @param null $errorKey
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function isValid($value, &$errorKey = null): bool
	{
		if (!$value)
		{
			$errorKey = 'path_error';
			return false;
		}

		if (!file_exists($value))
		{
			$errorKey = 'path_find_error';
			return false;
		}

		if (!$this->getOption('verify_executable'))
		{
			return true;
		}

		$class = '\DBTech\eCommerce\WkHtmlToPdf\Runner';
		$class = \XF::extendClass($class);

		/** @var \DBTech\eCommerce\WkHtmlToPdf\Runner $wkHtml */
		$wkHtml = new $class($value, false);

		$versionNumber = $wkHtml->getVersionNumber();

		if ($versionNumber !== null && version_compare($versionNumber, '0.12.5', '<'))
		{
			$errorKey = 'version_error';
			return false;
		}

		if ($versionNumber === null)
		{
			$errorKey = 'execute_error';
			return false;
		}

		return true;
	}
	
	/**
	 * @param $value
	 *
	 * @return mixed|string
	 */
	public function coerceValue($value): string
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			$value = str_replace('/', '\\', $value);
		}

		return trim($value);
	}
	
	/**
	 * @param string $errorKey
	 *
	 * @return \XF\Phrase|null
	 */
	public function getPrintableErrorValue($errorKey): ?\XF\Phrase
	{
		switch ($errorKey)
		{
			case 'path_error':
				return \XF::phrase('dbtech_ecommerce_path_provided_was_not_valid');

			case 'path_find_error':
				return \XF::phrase('dbtech_ecommerce_could_not_find_wkhtmltopdf_at_path_specified');

			case 'version_error':
				return \XF::phrase('dbtech_ecommerce_wkhtmltopdf_version_requirement');

			case 'execute_error':
				return \XF::phrase('dbtech_ecommerce_could_not_execute_wkhtmltopdf_at_path_specified');

			default: return null;
		}
	}
}