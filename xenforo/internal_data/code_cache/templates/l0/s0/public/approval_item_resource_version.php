<?php
// FROM HASH: d52f11cc880dc26db78ba6fec68ec04f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['messageHtml'] = $__templater->preEscaped('
	<h4 class="message-title"><a href="' . $__templater->func('link', array('resources', $__vars['content']['Resource'], ), true) . '">' . $__templater->escape($__vars['content']['Resource']['title']) . ' ' . $__templater->escape($__vars['content']['version_string']) . '</a></h4>
	' . $__templater->button('Download', array(
		'class' => 'button--link',
		'href' => $__templater->func('link', array('resources/version/download', $__vars['content'], ), false),
		'target' => '_blank',
	), '', array(
	)) . '
');
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['release_date'],
		'user' => $__vars['content']['Resource']['User'],
		'messageHtml' => $__vars['messageHtml'],
		'typePhraseHtml' => 'Resource version',
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => 'Version ' . $__templater->escape($__vars['content']['version_string']) . ' of <a href="' . $__templater->func('link', array('resources', $__vars['content']['Resource'], ), true) . '">' . $__templater->escape($__vars['content']['Resource']['title']) . '</a> posted in resource category <a href="' . $__templater->func('link', array('resources/categories', $__vars['content']['Resource']['Category'], ), true) . '">' . $__templater->escape($__vars['content']['Resource']['Category']['title']) . '</a>',
	), $__vars);
	return $__finalCompiled;
}
);