<?php
// FROM HASH: f65e24b38662f1cf4b18016ed43ded1b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Categories');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'page_action', array(
		'linkPrefix' => 'dbtech-ecommerce/categories',
	), $__vars) . '

' . $__templater->callMacro('category_tree_macros', 'category_list', array(
		'categoryTree' => $__vars['categoryTree'],
		'filterKey' => 'dbtech-ecommerce-categories',
		'linkPrefix' => 'dbtech-ecommerce/categories',
		'idKey' => 'category_id',
	), $__vars);
	return $__finalCompiled;
}
);