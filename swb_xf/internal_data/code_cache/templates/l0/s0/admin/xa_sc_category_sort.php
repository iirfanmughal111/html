<?php
// FROM HASH: 4a3ac7b077b667e8c7dc2d2a9f911a9c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Sort categories');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'sortable_form', array(
		'categoryTree' => $__vars['categoryTree'],
		'linkPrefix' => 'xa-sc/categories',
	), $__vars);
	return $__finalCompiled;
}
);