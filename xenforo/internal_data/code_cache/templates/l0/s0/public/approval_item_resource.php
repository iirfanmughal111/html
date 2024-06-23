<?php
// FROM HASH: 45ad1ceadf669472602a16354c59f2e5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['messageHtml'] = $__templater->preEscaped('
	<h4 class="message-title"><a href="' . $__templater->func('link', array('resources', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h4>
	' . $__templater->func('bb_code', array($__vars['content']['Description']['message'], 'resource_update', $__vars['content']['Description'], ), true) . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['resource_date'],
		'user' => $__vars['content']['User'],
		'spamDetails' => $__vars['spamDetails'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Resource',
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => '<a href="' . $__templater->func('link', array('resources', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a> posted in resource category <a href="' . $__templater->func('link', array('resources/categories', $__vars['content']['Category'], ), true) . '">' . $__templater->escape($__vars['content']['Category']['title']) . '</a>',
	), $__vars);
	return $__finalCompiled;
}
);