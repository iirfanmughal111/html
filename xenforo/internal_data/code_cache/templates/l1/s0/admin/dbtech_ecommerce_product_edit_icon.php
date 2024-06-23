<?php
// FROM HASH: 918f51ca15110f9a1fdfb9e80fe20e6f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Product icon');
	$__finalCompiled .= '

' . $__templater->callMacro('public:dbtech_ecommerce_product_edit_icon_macros', 'edit_icon', array(
		'context' => 'admin',
		'linkPrefix' => 'dbtech-ecommerce/products',
		'product' => $__vars['product'],
	), $__vars);
	return $__finalCompiled;
}
);