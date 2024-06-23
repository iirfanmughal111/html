<?php
// FROM HASH: 7c260ca911c3db7403bc5b8837b1be59
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' added the item ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['content']['Item'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . ' to the series ' . (((('<a href="' . $__templater->func('link', array('showcase/series', $__vars['content']['Series'], ), true)) . '">') . $__templater->escape($__vars['content']['Series']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);