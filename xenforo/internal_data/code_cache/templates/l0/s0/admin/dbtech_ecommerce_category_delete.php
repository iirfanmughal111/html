<?php
// FROM HASH: 4b204e235676dfb40381bc7361f3a2af
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->callMacro('category_tree_macros', 'category_delete_form', array(
		'linkPrefix' => 'dbtech-ecommerce/categories',
		'category' => $__vars['category'],
	), $__vars);
	return $__finalCompiled;
}
);