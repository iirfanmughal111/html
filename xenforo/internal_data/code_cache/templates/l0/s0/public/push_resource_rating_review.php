<?php
// FROM HASH: 634312b4bdcdbc8e5f204a728e3ce091
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reviewed the resource ' . ($__templater->func('prefix', array('resource', $__vars['content']['Resource'], 'plain', ), true) . $__templater->escape($__vars['content']['Resource']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:resources/review', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);