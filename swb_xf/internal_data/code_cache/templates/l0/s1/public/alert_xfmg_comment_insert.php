<?php
// FROM HASH: 1ce5c50022666051188fb75f2f89f53c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['content']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented on media item ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . '.' . '
';
	} else {
		$__finalCompiled .= '
	' . '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' commented on album ' . (((('<a href="' . $__templater->func('link', array('media/comments', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Content']['title'])) . '</a>') . '.' . '
';
	}
	return $__finalCompiled;
}
);