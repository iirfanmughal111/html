<?php
// FROM HASH: ba1194104a12220d2691768bbf04ab3b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Product icon');
	$__finalCompiled .= '

' . $__templater->callMacro('public:dbtech_ecommerce_product_edit_icon_macros', 'edit_icon', array(
		'product' => $__vars['product'],
	), $__vars);
	return $__finalCompiled;
}
);