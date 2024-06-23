<?php
// FROM HASH: bc6809b940ad568da052f522c1fa269c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' mentioned you on media item ' . $__templater->escape($__vars['content']['title']) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:media', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);