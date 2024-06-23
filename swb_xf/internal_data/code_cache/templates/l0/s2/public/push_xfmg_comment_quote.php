<?php
// FROM HASH: ae7931ad09b6840872f3d7ee8445b968
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
	' . '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' quoted your comment on media item ' . $__templater->escape($__vars['content']['Content']['title']) . '.' . '
';
	} else {
		$__finalCompiled .= '
	' . '' . ($__templater->escape($__vars['user']['username']) ?: $__templater->escape($__vars['alert']['username'])) . ' quoted your comment on album ' . $__templater->escape($__vars['content']['Content']['title']) . '.' . '
';
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('link', array('canonical:media/comments', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);