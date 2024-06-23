<?php
// FROM HASH: 06683e2ac69d7228a9711cc1a0d9dbf3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Permissions' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['record']['title']));
	$__finalCompiled .= '

' . $__templater->callMacro('permission_category_macros', 'list', array(
		'category' => $__vars['record'],
		'isPrivate' => $__vars['isPrivate'],
		'userGroups' => $__vars['userGroups'],
		'users' => $__vars['users'],
		'entries' => $__vars['entries'],
		'routeBase' => 'dbtech-ecommerce/categories/permissions',
	), $__vars);
	return $__finalCompiled;
}
);