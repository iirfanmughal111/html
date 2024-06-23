<?php
// FROM HASH: b0cb3d43fbc3f7f9385c4221a0581968
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['messageHtml'] = $__templater->preEscaped('
	<h4 class="message-title"><a href="' . $__templater->func('link', array('forumGroups/', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h4>
');
	$__finalCompiled .= '
' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['created_at'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Group',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => 'Group <a href="' . $__templater->func('link', array('forumGroups', $__vars['content'], ), true) . '"><strong>' . $__templater->escape($__vars['content']['title']) . '</strong></a> created by <a href="' . $__templater->func('link', array('members', $__vars['content']['User'], ), true) . '"><strong>' . $__templater->escape($__vars['content']['User']['username']) . '</strong></a>.',
	), $__vars);
	return $__finalCompiled;
}
);