<?php
// FROM HASH: 21ab1e19138581504de737147c0d6b0a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'category_delete_form', array(
		'linkPrefix' => 'auction/categories',
		'category' => $__vars['category'],
	), $__vars);
	return $__finalCompiled;
}
);