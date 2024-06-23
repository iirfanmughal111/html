<?php
// FROM HASH: 1e4e99f63eaff0e0b05fb331de782742
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['messageHtml'] = $__templater->preEscaped('
	<h4 class="message-title"><a href="' . $__templater->func('link', array('resources', $__vars['content']['Resource'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h4>
	' . $__templater->func('bb_code', array($__vars['content']['message'], 'resource_update', $__vars['content'], ), true) . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['post_date'],
		'user' => $__vars['content']['Resource']['User'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Resource update',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => 'Update to <a href="' . $__templater->func('link', array('resources', $__vars['content']['Resource'], ), true) . '">' . $__templater->escape($__vars['content']['Resource']['title']) . '</a> in resource category <a href="' . $__templater->func('link', array('resources/categories', $__vars['content']['Resource']['Category'], ), true) . '">' . $__templater->escape($__vars['content']['Resource']['Category']['title']) . '</a>',
	), $__vars);
	return $__finalCompiled;
}
);