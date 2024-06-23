<?php
// FROM HASH: 48f887ff028db0ecab157e1de32325ab
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' added a new page \'' . (((('<a href="' . $__templater->func('link', array('showcase/page', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['title'])) . '</a>') . '\' to the item ' . ((((('<a href="' . $__templater->func('link', array('showcase', $__vars['content']['Item'], ), true)) . '" class="">') . $__templater->func('prefix', array('sc_item', $__vars['content']['Item'], ), true)) . $__templater->escape($__vars['content']['Item']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);