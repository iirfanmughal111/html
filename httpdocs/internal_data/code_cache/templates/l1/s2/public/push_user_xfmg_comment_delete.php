<?php
// FROM HASH: e4b14762d046c388794582fd9d15ae1c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['extra']['content_type'] == 'xfmg_album') {
		$__finalCompiled .= '
	' . 'Your comment on the album ' . $__templater->escape($__vars['extra']['title']) . ' was deleted.' . '
';
	} else {
		$__finalCompiled .= '
	' . 'Your comment on the media item ' . $__templater->escape($__vars['extra']['title']) . ' was deleted.' . '
';
	}
	$__finalCompiled .= '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Reason' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('base_url', array($__vars['extra']['link'], 'canonical', ), true) . '</push:url>';
	return $__finalCompiled;
}
);