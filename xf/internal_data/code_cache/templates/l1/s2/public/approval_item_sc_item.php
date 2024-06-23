<?php
// FROM HASH: 094bf5a93060c4a9ca12fe2d36ed9f0e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['messageHtml'] = $__templater->preEscaped('
	<h4 class="message-title"><a href="' . $__templater->func('link', array('showcase', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a></h4>
	' . $__templater->func('bb_code', array($__vars['content']['message'], 'sc_item', $__vars['content'], ), true) . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['create_date'],
		'user' => $__vars['content']['User'],
		'spamDetails' => $__vars['spamDetails'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Showcase item',
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => '<a href="' . $__templater->func('link', array('showcase', $__vars['content'], ), true) . '">' . $__templater->escape($__vars['content']['title']) . '</a> posted in item category <a href="' . $__templater->func('link', array('showcase/categories', $__vars['content']['Category'], ), true) . '">' . $__templater->escape($__vars['content']['Category']['title']) . '</a>',
	), $__vars);
	return $__finalCompiled;
}
);