<?php
// FROM HASH: b576d1bd1071f16c2071da1d2559ccd9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Sort categories');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'sortable_form', array(
		'categoryTree' => $__vars['categoryTree'],
		'linkPrefix' => 'media-gallery/categories',
	), $__vars);
	return $__finalCompiled;
}
);