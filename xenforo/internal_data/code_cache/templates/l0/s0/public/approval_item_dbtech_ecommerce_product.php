<?php
// FROM HASH: 08bdc4d36019f4a08594ed38151579ae
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__vars['headerPhraseHtml'] = $__templater->preEscaped($__templater->func('trim', array('
	' . 'Product \'' . (((('<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['content'], ), true)) . '">') . $__templater->escape($__vars['content']['title'])) . '</a>') . '\' in category \'' . (((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/categories', $__vars['content']['Category'], ), true)) . '">') . $__templater->escape($__vars['content']['Category']['title'])) . '</a>') . '\'' . '
'), false));
	$__finalCompiled .= '

' . $__templater->callMacro('approval_queue_macros', 'item_message_type', array(
		'content' => $__vars['content'],
		'contentDate' => $__vars['content']['creation_date'],
		'user' => $__vars['content']['User'],
		'messageHtml' => $__templater->func('bb_code', array($__vars['content']['description'], 'dbtech_ecommerce_product', $__vars['content'], ), false),
		'typePhraseHtml' => 'Product',
		'actionsHtml' => $__vars['actionsHtml'],
		'spamDetails' => $__vars['spamDetails'],
		'unapprovedItem' => $__vars['unapprovedItem'],
		'handler' => $__vars['handler'],
		'headerPhraseHtml' => $__vars['headerPhraseHtml'],
	), $__vars);
	return $__finalCompiled;
}
);