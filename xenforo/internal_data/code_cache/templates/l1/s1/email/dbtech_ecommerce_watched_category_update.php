<?php
// FROM HASH: bc5bdf6c557a9baf5c75d7826f4ea36e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>
	' . '' . $__templater->escape($__vars['product']['title']) . ' updated: ' . $__templater->escape($__vars['download']['title']) . '' . '
</mail:subject>

' . '<p>' . $__templater->func('username_link_email', array($__vars['product']['User'], $__vars['product']['username'], ), true) . ' updated a product within a category you are watching at ' . (((('<a href="' . $__templater->func('link', array('canonical:index', ), true)) . '">') . $__templater->escape($__vars['xf']['options']['boardTitle'])) . '</a>') . '.</p>' . '

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
		'watchType' => 'category',
	), $__vars) . '

' . $__templater->callMacro('dbtech_ecommerce_product_macros', 'watched_category_footer', array(
		'category' => $__vars['category'],
		'product' => $__vars['product'],
	), $__vars);
	return $__finalCompiled;
}
);