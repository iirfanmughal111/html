<?php
// FROM HASH: 270147c4398de565f6036b0f6bbf185f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['messageHtml'] = $__templater->preEscaped('
	<h4 class="message-title"><a href="' . $__templater->func('link', array('members', $__vars['content']['User'], ), true) . '">' . 'Withdrawal Request from ' . $__templater->escape($__vars['content']['User']['username']) . '.' . '</a></h4>
');
	$__finalCompiled .= '
' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['created_at'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Withdraw Amount Request',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => '' . $__templater->escape($__vars['content']['User']['username']) . ' requested to withdraw <strong>$' . $__templater->escape($__vars['content']['amount']) . '</strong> from <strong>' . $__templater->escape($__vars['content']['address_from']) . '</strong>.',
	), $__vars);
	return $__finalCompiled;
}
);