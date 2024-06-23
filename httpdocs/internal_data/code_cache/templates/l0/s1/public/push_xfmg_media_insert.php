<?php
// FROM HASH: 7cc0eaeed71224c47086723995594c57
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' added the media item ' . $__templater->escape($__vars['content']['title']) . '.' . '
<push:url>' . $__templater->func('link', array('canonical:media', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);