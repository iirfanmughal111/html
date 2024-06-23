<?php
// FROM HASH: daa3b6f2f4281fa3d9f00e866318c758
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['reply_date'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__templater->func('bb_code', array($__vars['content']['message'], 'sc_update_reply', $__vars['content'], ), false),
		'typePhraseHtml' => 'Showcase update reply',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => 'Reply to an update <a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['ItemUpdate']['title']) . '</a> on the item <a href="' . $__templater->func('link', array('showcase/update-reply', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['ItemUpdate']['Item']['title']) . '</a> by <a href="' . $__templater->func('link', array('members', $__vars['content']['ItemUpdate']['Item']['User'], ), true) . '">' . $__templater->escape($__vars['content']['ItemUpdate']['Item']['User']['username']) . '</a>',
	), $__vars);
	return $__finalCompiled;
}
);