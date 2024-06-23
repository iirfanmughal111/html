<?php
// FROM HASH: 8ae610ed156a3031655bf3e76b7b7273
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= 'Your license ' . (((((('<a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/license', $__vars['content'], ), true)) . '" class="fauxBlockLink-blockLink">') . $__templater->escape($__vars['content']['Product']['full_title'])) . ' - ') . $__templater->escape($__vars['content']['license_key'])) . '</a>') . ' is expiring soon.' . '
' . 'Expiry date' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['content']['expiry_date'], array(
	)));
	return $__finalCompiled;
}
);