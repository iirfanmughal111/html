<?php
// FROM HASH: a7ee090f1d1e9e656aaad88b3cc82dec
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Invoice logo');
	$__finalCompiled .= '

' . $__templater->callMacro('public:dbtech_ecommerce_invoice_edit_icon_macros', 'edit_icon', array(
		'context' => 'admin',
		'linkPrefix' => 'dbtech-ecommerce/invoice-icon',
	), $__vars);
	return $__finalCompiled;
}
);