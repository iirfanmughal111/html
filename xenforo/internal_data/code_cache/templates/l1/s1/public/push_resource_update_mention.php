<?php
// FROM HASH: 6f9af0080a58efb1a6477fe81cde0118
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['content'], 'isDescription', array())) {
		$__finalCompiled .= '
	' . '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' mentioned you in the resource ' . ($__templater->func('prefix', array('resource', $__vars['content']['Resource'], 'plain', ), true) . $__templater->escape($__vars['content']['Resource']['title'])) . '.' . '
	<push:url>' . $__templater->func('link', array('canonical:resources', $__vars['content']['Resource'], ), true) . '</push:url>
';
	} else {
		$__finalCompiled .= '
	' . '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' mentioned you in an update to the resource ' . ($__templater->func('prefix', array('resource', $__vars['content']['Resource'], 'plain', ), true) . $__templater->escape($__vars['content']['Resource']['title'])) . '.' . '
	<push:url>' . $__templater->func('link', array('canonical:resources/update', $__vars['content'], ), true) . '</push:url>
';
	}
	return $__finalCompiled;
}
);