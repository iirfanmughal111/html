<?php
// FROM HASH: ecc630ccc6a03852b91823499a23b574
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' replied to your update ' . (((('<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['ItemUpdate']['title'])) . '</a>') . ' posted on the item ' . ((((('<a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true)) . '">') . $__templater->func('prefix', array('sc_item', $__vars['content']['ItemUpdate']['Item'], ), true)) . $__templater->escape($__vars['content']['ItemUpdate']['Item']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);