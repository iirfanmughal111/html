<?php
// FROM HASH: df994218500ed423bdca8e6944ab846c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in a reply to an update ' . (((('<a href="' . $__templater->func('link', array('showcase/update', $__vars['content']['ItemUpdate'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['ItemUpdate']['title'])) . '</a>') . ' on the item ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['content']['ItemUpdate'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['content']['ItemUpdate']['Item'], ), true)) . $__templater->escape($__vars['content']['ItemUpdate']['Item']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);