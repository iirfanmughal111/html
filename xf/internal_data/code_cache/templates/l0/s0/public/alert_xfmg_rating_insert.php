<?php
// FROM HASH: 6a0a0ee1e21781c907ab184ef1fb7be0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
	';
		if ($__vars['content']['Comment']) {
			$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reviewed the media item ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content']['Comment'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Media']['title'])) . '</a>') . '.' . '
	';
		} else {
			$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' rated the media item ' . (((('<a href="' . $__templater->func('link', array('media', $__vars['content']['Media'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Media']['title'])) . '</a>') . '.' . '
	';
		}
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		if ($__vars['content']['Comment']) {
			$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' reviewed the album ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content']['Comment'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Album']['title'])) . '</a>') . '.' . '
	';
		} else {
			$__finalCompiled .= '
		' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' rated the album ' . (((('<a href="' . $__templater->func('link', array('media/albums', $__vars['content']['Album'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Album']['title'])) . '</a>') . '.' . '
	';
		}
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);