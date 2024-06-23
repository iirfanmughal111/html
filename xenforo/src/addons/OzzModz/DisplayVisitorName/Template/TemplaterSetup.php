<?php

namespace OzzModz\DisplayVisitorName\Template;

class TemplaterSetup
{
	/** @noinspection PhpUnusedParameterInspection */
	public function filterReplaceVisitorName($templater, $string)
	{
		/** @var \OzzModz\DisplayVisitorName\Helper\Replacer $replacer */
		$replacer = \XF::helper('OzzModz\DisplayVisitorName:Replacer');
		return $replacer->replaceVisitorName($string);
	}
}
