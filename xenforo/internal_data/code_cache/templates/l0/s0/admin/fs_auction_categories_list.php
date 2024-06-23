<?php
// FROM HASH: da64be75eb57e9a52f726576beb3018c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Auction categories');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'page_action', array(
		'linkPrefix' => 'auction/categories',
	), $__vars) . '

' . $__templater->callMacro('category_tree_macros', 'category_list', array(
		'categoryTree' => $__vars['categoryTree'],
		'filterKey' => 'auction-categories',
		'linkPrefix' => 'auction/categories',
	), $__vars);
	return $__finalCompiled;
}
);