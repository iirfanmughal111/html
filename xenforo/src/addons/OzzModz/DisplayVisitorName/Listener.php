<?php

namespace OzzModz\DisplayVisitorName;

/**
* Class Listener
 * 
 * @package OzzModz\DisplayVisitorName
 */
class Listener
{

    /**
     * Fired when the Templater object has been setup.
     *
     * @param \XF\Container          $container Dependency injection container object.
     * @param \XF\Template\Templater $templater The Templater object. Note: This could also be
     *                                          the Mailer templater.
     */
    public static function templaterSetup(\XF\Container $container, \XF\Template\Templater &$templater)
    {
		/** @var \XFRM\Template\TemplaterSetup $templaterSetup */
		$class = \XF::extendClass('OzzModz\DisplayVisitorName\Template\TemplaterSetup');
		$templaterSetup = new $class();
     // var_dump('avd');exit;
		$templater->addFilter('replace_visitor_name', [$templaterSetup, 'filterReplaceVisitorName']);
    }

}