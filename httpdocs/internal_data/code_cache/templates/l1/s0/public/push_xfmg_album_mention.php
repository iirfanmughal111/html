<?php
// FROM HASH: c0b4e5fb31a279672333d7df4a324b45
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' mentioned you on album ' . $__templater->escape($__vars['content']['title']) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:media/albums', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);