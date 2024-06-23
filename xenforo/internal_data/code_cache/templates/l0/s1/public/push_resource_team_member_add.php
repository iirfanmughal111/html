<?php
// FROM HASH: 37cf2cc0e3d7fa56ecd5038614e78372
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' added you as a team member for the resource ' . ($__templater->func('prefix', array('resource', $__vars['content'], 'plain', ), true) . $__templater->escape($__vars['content']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:resources', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);