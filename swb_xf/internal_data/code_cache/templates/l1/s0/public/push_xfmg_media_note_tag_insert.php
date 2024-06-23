<?php
// FROM HASH: a3b5a9fb4ae7a559bd89c9510d7e1606
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' tagged you in media ' . $__templater->escape($__vars['content']['MediaItem']['title']) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:media', $__vars['content']['MediaItem'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);