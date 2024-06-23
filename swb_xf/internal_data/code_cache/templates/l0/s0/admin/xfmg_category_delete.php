<?php
// FROM HASH: 15a38ae34d8c2b7f5e7b7c7fed7733ff
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'category_delete_form', array(
		'linkPrefix' => 'media-gallery/categories',
		'category' => $__vars['category'],
	), $__vars);
	return $__finalCompiled;
}
);