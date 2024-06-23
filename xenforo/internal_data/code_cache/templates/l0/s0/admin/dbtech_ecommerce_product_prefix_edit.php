<?php
// FROM HASH: f010872f8f2e4d9802ae654dbb8a5f1f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['extraOptions'] = $__templater->preEscaped('
		' . $__templater->callMacro('dbtech_ecommerce_product_prefix_edit_macros', 'category_ids', array(
		'categoryIds' => $__vars['prefix']['category_ids'],
		'categoryTree' => $__vars['categoryTree'],
	), $__vars) . '
	');
	$__finalCompiled .= $__templater->includeTemplate('base_prefix_edit', $__compilerTemp1);
	return $__finalCompiled;
}
);