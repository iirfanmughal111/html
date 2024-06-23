<?php
// FROM HASH: f83c165032f291d453695699ac8920c8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in an update ' . (((('<a href="' . $__templater->func('link', array('showcase/update', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . ' to the item ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);