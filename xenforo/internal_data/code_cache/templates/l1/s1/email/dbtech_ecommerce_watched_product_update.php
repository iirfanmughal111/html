<?php
// FROM HASH: ef0cf64d45d1c615b177accda16c38a4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . '' . $__templater->escape($__vars['product']['title']) . ' updated: ' . $__templater->escape($__vars['download']['title']) . '' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['download']['User'], $__vars['product']['username'], ), true) . ' updated a product you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

<h2><a href="' . $__templater->func('link', array('canonical:dbtech-ecommerce/release', $__vars['download'], ), true) . '">' . $__templater->escape($__vars['product']['title']) . ' - ' . $__templater->escape($__vars['download']['title']) . '</a></h2>

';
	if ($__vars['xf']['options']['emailWatchedThreadIncludeMessage']) {
		$__finalCompiled .= '
	<div class="message">' . $__templater->func('bb_code_type', array('emailHtml', $__vars['download']['change_log'], 'dbtech_ecommerce_download', $__vars['download'], ), true) . '</div>
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_product_macros', 'go_product_bar', array(
		'product' => $__vars['product'],
		'watchType' => 'product',
	), $__vars) . '

' . $__templater->callMacro('dbtech_ecommerce_product_macros', 'watched_product_footer', array(
		'product' => $__vars['product'],
	), $__vars);
	return $__finalCompiled;
}
);