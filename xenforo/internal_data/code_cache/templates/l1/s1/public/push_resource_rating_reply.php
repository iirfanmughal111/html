<?php
// FROM HASH: 7c60f1c324e08a92939ee40fef1cf996
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' replied to your review of the resource ' . ($__templater->func('prefix', array('resource', $__vars['content']['Resource'], 'plain', ), true) . $__templater->escape($__vars['content']['Resource']['title'])) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:resources/review', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);