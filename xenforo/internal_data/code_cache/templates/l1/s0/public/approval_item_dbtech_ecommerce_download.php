<?php
// FROM HASH: 663672b5c51970cdf63c0eb38e61072d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['headerPhraseHtml'] = $__templater->preEscaped($__templater->func('trim', array('
	' . 'Release \'' . (((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/release', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['title'])) . '</a>') . '\' in product \'' . (((('<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['content']['Product'], ), true)) . '">') . $__templater->escape($__vars['content']['Product']['title'])) . '</a>') . '\'' . '
'), false));
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['release_date'],
		'user' => $__vars['content']['Product']['User'],
		'messageHtml' => $__templater->func('bb_code', array($__vars['content']['change_log'], 'dbtech_ecommerce_download', $__vars['content'], ), false),
		'typePhraseHtml' => 'Download',
		'actionsHtml' => $__vars['actionsHtml'],
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => $__vars['headerPhraseHtml'],
	), $__vars);
	return $__finalCompiled;
}
);