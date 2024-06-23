<?php
// FROM HASH: 8a8c6b80bfa26576abfe9182cd7f407c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['messageHtml'] = $__templater->preEscaped('
	<h4 class="message-title"><a href="' . $__templater->func('link', array('showcase', $__vars['content']['Item'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h4>
	' . $__templater->func('bb_code', array($__vars['content']['message'], 'sc_update', $__vars['content'], ), true) . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['update_date'],
		'user' => $__vars['content']['Item']['User'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Showcase update',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => 'Update to <a href="' . $__templater->func('link', array('showcase', $__vars['content']['Item'], ), true) . '">' . $__templater->escape($__vars['content']['Item']['title']) . '</a> in category <a href="' . $__templater->func('link', array('showcase/categories', $__vars['content']['Item']['Category'], ), true) . '">' . $__templater->escape($__vars['content']['Item']['Category']['title']) . '</a>',
	), $__vars);
	return $__finalCompiled;
}
);