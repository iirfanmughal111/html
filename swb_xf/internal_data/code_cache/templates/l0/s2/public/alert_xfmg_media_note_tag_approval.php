<?php
// FROM HASH: 1f829bd06905e1792606ad4de05e7543
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . $__templater->func('username_link', array($__vars['user'], false, array('defaultname' => $__vars['alert']['username'], ), ), true) . ' tagged you in media ' . (((('<a href="' . $__templater->func('link', array('media', $__vars['content']['MediaItem'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['MediaItem']['title'])) . '</a>') . '. You can either ' . (((('<a href="' . $__templater->func('link', array('media/note-approve', $__vars['content']['MediaItem'], array('note_id' => $__vars['content']['note_id'], 't' => $__templater->func('csrf_token', array(), false), ), ), true)) . '">') . 'Approve') . '</a>') . ' or ' . (((('<a href="' . $__templater->func('link', array('media/note-reject', $__vars['content']['MediaItem'], array('note_id' => $__vars['content']['note_id'], 't' => $__templater->func('csrf_token', array(), false), ), ), true)) . '">') . 'Reject') . '</a>') . ' this tag.';
	return $__finalCompiled;
}
);