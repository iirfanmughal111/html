<?php
// FROM HASH: fe0ca991bf8a62833feb63f99eb65cbb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>' . 'New sale at ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' (Order #' . $__templater->escape($__vars['item']['order_id']) . ')' . '</mail:subject>

' . '<p>' . $__templater->escape($__vars['purchaser']['username']) . ' purchased the following service: ' . (((((('<a href="' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['item']['Product'], ), true)) . '">') . $__templater->escape($__vars['item']['quantity'])) . 'x ') . $__templater->escape($__vars['item']['full_title'])) . '</a>') . '.</p>' . '

<p>' . 'Please perform this service A.S.A.P. You may also wish to reply to this email to let the user know when you will be performing the service, or to discuss details.' . '</p>

';
	if ($__vars['item']['discussion_thread_id'] AND !$__templater->test($__vars['item']['Discussion'], 'empty', array())) {
		$__finalCompiled .= '
	' . '<p>' . $__templater->escape($__vars['purchaser']['username']) . ' provided additional information with this order. You can find this information here: ' . ((((('<a href="' . $__templater->func('link', array('canonical:threads', $__vars['item']['Discussion'], ), true)) . '">') . $__templater->func('prefix', array('thread', $__vars['item']['Discussion'], 'escaped', ), true)) . $__templater->escape($__vars['item']['Discussion']['title'])) . '</a>') . '</p>' . '
';
	}
	return $__finalCompiled;
}
);