<?php
// FROM HASH: 2fa96d985d8771912098411423af1cd5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'category_delete_form', array(
		'linkPrefix' => 'xa-sc/categories',
		'category' => $__vars['category'],
	), $__vars);
	return $__finalCompiled;
}
);