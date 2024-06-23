<?php
// FROM HASH: 55a7598de414fcbaa09ecefbe3d75c4f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '' . (((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/account/order', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . 'Order #' . $__templater->escape($__vars['content']['order_id']) . '') . '</a>') . ' has been shipped.' . '
';
	if ($__vars['extra']['reason']) {
		$__finalCompiled .= 'Note' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['extra']['reason']);
	}
	return $__finalCompiled;
}
);