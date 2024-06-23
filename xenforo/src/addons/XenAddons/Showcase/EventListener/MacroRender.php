<?php

namespace XenAddons\Showcase\EventListener;

class MacroRender
{
    public static function preRender(\XF\Template\Templater $templater, &$type, &$template, &$name, array &$arguments, array &$globalVars)
    {
        if (!empty($arguments['group']) && $arguments['group']->group_id == 'xaShowcase')
        {
            $template = 'xa_sc_option_macros';
        }
    }
}