<?php
// FROM HASH: a1ddc91eab0a805248ed4f1fb15779d5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['reply_date'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__templater->func('bb_code', array($__vars['content']['message'], 'sc_rating_reply', $__vars['content'], ), false),
		'typePhraseHtml' => 'Showcase rating reply',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => 'Review reply on item <a href="' . $__templater->func('link', array('showcase/review-reply', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['ItemRating']['Item']['title']) . '</a> by <a href="' . $__templater->func('link', array('members', $__vars['content']['ItemRating']['Item']['User'], ), true) . '">' . $__templater->escape($__vars['content']['ItemRating']['Item']['User']['username']) . '</a>',
	), $__vars);
	return $__finalCompiled;
}
);