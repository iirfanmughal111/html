<?php
// FROM HASH: dfcc0ece3081f6e1052dde517e3262a6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . 'Order #' . $__templater->escape($__vars['content']['order_id']) . '' . ' has been shipped.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Note' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	$__finalCompiled .= '
<push:url>' . $__templater->func('link', array('canonical:dbtech-ecommerce/account/order', $__vars['content'], ), true) . '</push:url>';
	return $__finalCompiled;
}
);