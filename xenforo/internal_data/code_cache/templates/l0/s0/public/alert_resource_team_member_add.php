<?php
// FROM HASH: 215d12e1d8b67393fbaf11cd7915bb1c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' added you as a team member for the resource ' . ((((('<a href="' . $__templater->func('link', array('resources', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('resource', $__vars['content'], ), true)) . $__templater->escape($__vars['content']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);