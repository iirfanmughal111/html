<?php
// FROM HASH: 20fcf4c51afa443f5e8537485bd32b6e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' left the resource team for the resource ' . ($__templater->func('prefix', array('resource', $__vars['content'], 'plain', ), true) . $__templater->escape($__vars['content']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:resources', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);