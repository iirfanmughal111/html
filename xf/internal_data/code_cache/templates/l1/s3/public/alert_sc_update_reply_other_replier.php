<?php
// FROM HASH: e867bdb631dd7ba7540ef532f12fe937
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' also replied to the update ' . (((('<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['ItemUpdate']['title'])) . '</a>') . ' on the item ' . ((((('<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['content']['ItemUpdate']['Item'], ), true)) . $__templater->escape($__vars['content']['ItemUpdate']['Item']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);