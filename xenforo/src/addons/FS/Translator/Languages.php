<?php
namespace FS\Translator;

use XF\Option\AbstractOption;

class Languages extends AbstractOption
{

	public static function verifyLanguages(array &$values, \XF\Entity\Option $option)
	{
            return true;
	}
        
        public static function verifyAltFlags(array &$values, \XF\Entity\Option $option)
	{
            $values = array_filter($values);
            return true;
	}
}

