<?php
// FROM HASH: 071f38a4f3acfa5082c4c6557c22f2bb
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Media categories');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'page_action', array(
		'linkPrefix' => 'media-gallery/categories',
	), $__vars) . '

' . $__templater->callMacro('category_tree_macros', 'category_list', array(
		'categoryTree' => $__vars['categoryTree'],
		'filterKey' => 'xfmg-categories',
		'linkPrefix' => 'media-gallery/categories',
	), $__vars);
	return $__finalCompiled;
}
);