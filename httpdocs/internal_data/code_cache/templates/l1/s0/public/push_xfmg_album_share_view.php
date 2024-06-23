<?php
// FROM HASH: 7faabd1f32e6afd4e12f2ba8e6e9abaf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' has shared their album ' . $__templater->escape($__vars['content']['title']) . ' with you.' . '
<push:url>' . $__templater->func('link', array('canonical:media/albums', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);