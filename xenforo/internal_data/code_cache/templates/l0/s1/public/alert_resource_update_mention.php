<?php
// FROM HASH: 5d2845feadc624dc9c248c64dee50459
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['content'], 'isDescription', array())) {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in the resource ' . ((((('<a href="' . $__templater->func('link', array('resources', $__vars['content']['Resource'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('resource', $__vars['content']['Resource'], ), true)) . $__templater->escape($__vars['content']['Resource']['title'])) . '</a>') . '.' . '
';
	} else {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' mentioned you in an update to the resource ' . ((((('<a href="' . $__templater->func('link', array('resources/update', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->func('prefix', array('resource', $__vars['content']['Resource'], ), true)) . $__templater->escape($__vars['content']['Resource']['title'])) . '</a>') . '.' . '
';
	}
	return $__finalCompiled;
}
);