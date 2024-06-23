<?php
// FROM HASH: 19f52bf3e5d03ade3a2127d2db80e61b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['comment_date'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__templater->func('bb_code', array($__vars['content']['message'], 'xfmg_comment', $__vars['content'], ), false),
		'typePhraseHtml' => 'Media comment',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => (($__vars['content']['content_type'] == 'xfmg_media') ? 'Comment on media <a href="' . $__templater->func('link', array('media', $__vars['content']['Media'], ), true) . '">' . $__templater->escape($__vars['content']['Media']['title']) . '</a> by <a href="' . $__templater->func('link', array('members', $__vars['content']['Media']['User'], ), true) . '">' . $__templater->escape($__vars['content']['Media']['User']['username']) . '</a>' : 'Comment on media album <a href="' . $__templater->func('link', array('media/albums', $__vars['content']['Album'], ), true) . '">' . $__templater->escape($__vars['content']['Album']['title']) . '</a> by <a href="' . $__templater->func('link', array('members', $__vars['content']['User'], ), true) . '">' . $__templater->escape($__vars['content']['username']) . '</a>'),
	), $__vars);
	return $__finalCompiled;
}
);