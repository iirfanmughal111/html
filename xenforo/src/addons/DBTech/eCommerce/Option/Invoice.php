<?php

namespace DBTech\eCommerce\Option;

use XF\Option\AbstractOption;

/**
 * Class Invoice
 *
 * @package DBTech\eCommerce\Option
 */
class Invoice extends AbstractOption
{
	/**
	 * @param array $values
	 * @param \XF\Entity\Option $option
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function verifyHtmlInvoice(array &$values, \XF\Entity\Option $option): bool
	{
		if ($option->isInsert())
		{
			return true;
		}

		if (empty($values['enabled']))
		{
			return true;
		}
		
		/** @var \DBTech\eCommerce\Validator\WkHtmlToPdf $validator */
		$validator = \XF::app()->validator('DBTech\eCommerce:WkHtmlToPdf');

		$path = $validator->coerceValue($values['path']);

		if (!$validator->isValid($path, $errorKey))
		{
			$option->error($validator->getPrintableErrorValue($errorKey), $option->option_id);
			return false;
		}

		return true;
	}
}