<?php
// FROM HASH: ee97859c2ff1c1266b81a45de1154dcf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['comment_date'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__templater->func('bb_code', array($__vars['content']['message'], 'sc_comment', $__vars['content'], ), false),
		'typePhraseHtml' => 'Showcase comment',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => 'Comment on item <a href="' . $__templater->func('link', array('showcase/comments', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['Item']['title']) . '</a> by <a href="' . $__templater->func('link', array('members', $__vars['content']['Item']['User'], ), true) . '">' . $__templater->escape($__vars['content']['Item']['User']['username']) . '</a>',
	), $__vars);
	return $__finalCompiled;
}
);