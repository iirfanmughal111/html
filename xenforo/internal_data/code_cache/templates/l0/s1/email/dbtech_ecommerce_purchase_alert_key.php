<?php
// FROM HASH: 363410b016123b2669f5ce347fab39bd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<mail:subject>' . 'Your serial key from ' . $__templater->escape($__vars['xf']['options']['boardTitle']) . ' (Order #' . $__templater->escape($__vars['item']['order_id']) . ')' . '</mail:subject>

' . '<p>Your serial key for ' . (((('<a href="' . $__templater->func('link', array('canonical:dbtech-ecommerce', $__vars['item']['Product'], ), true)) . '">') . $__templater->escape($__vars['item']['full_title'])) . '</a>') . ' is shown below.</p>' . '

<h2>' . $__templater->escape($__vars['serialKey']['serial_key']) . '</h2>';
	return $__finalCompiled;
}
);