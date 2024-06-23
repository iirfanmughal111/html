<?php
// FROM HASH: 0dc69eadc2ec5682f5fecabedf10365f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Sort categories');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'sortable_form', array(
		'categoryTree' => $__vars['categoryTree'],
		'linkPrefix' => 'dbtech-ecommerce/categories',
	), $__vars);
	return $__finalCompiled;
}
);