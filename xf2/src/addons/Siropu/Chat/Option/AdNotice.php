<?php

namespace Siropu\Chat\Option;

class AdNotice extends \XF\Option\AbstractOption
{
	public static function verifyOption(array &$value)
	{
		$value = array_filter($value, function($val)
          {
               return !empty($val);
          });

		return true;
	}
}
