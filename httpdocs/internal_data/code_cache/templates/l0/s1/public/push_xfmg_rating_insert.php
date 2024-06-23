<?php
// FROM HASH: 1046d089db691fdc016996c4ef6b2018
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
	';
		if ($__vars['content']['Comment']) {
			$__finalCompiled .= '
		' . '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reviewed the media item ' . $__templater->escape($__vars['content']['Media']['title']) . '.' . '
		<push:url>' . $__templater->func('link', array('canonical:media/comments', $__vars['content']['Comment'], ), true) . '</push:url>
	';
		} else {
			$__finalCompiled .= '
		' . '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' rated the media item ' . $__templater->escape($__vars['content']['Media']['title']) . '.' . '
		<push:url>' . $__templater->func('link', array('canonical:media', $__vars['content']['Media'], ), true) . '</push:url>
	';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		if ($__vars['content']['Comment']) {
			$__finalCompiled .= '
		' . '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' reviewed the album ' . $__templater->escape($__vars['content']['Album']['title']) . '.' . '
		<push:url>' . $__templater->func('link', array('canonical:media/comments', $__vars['content']['Comment'], ), true) . '</push:url>
	';
		} else {
			$__finalCompiled .= '
		' . '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' rated the album ' . $__templater->escape($__vars['content']['Album']['title']) . '.' . '
		<push:url>' . $__templater->func('link', array('canonical:media/albums', $__vars['content']['Album'], ), true) . '</push:url>
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);