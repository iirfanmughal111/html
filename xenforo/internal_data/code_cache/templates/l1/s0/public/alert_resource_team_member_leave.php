<?php
// FROM HASH: 871c19eaf10f3cacf922f185a5dc6050
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' left the resource team for the resource ' . ((((('<a href="' . $__templater->func('link', array('resources', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('resource', $__vars['content'], ), true)) . $__templater->escape($__vars['content']['title'])) . '</a>') . '.';
	return $__finalCompiled;
}
);