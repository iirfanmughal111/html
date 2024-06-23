<?php
// FROM HASH: 6c687c613f12ab6100ae9692fb2917c3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['record']['title']));
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_permission_product_macros', 'list', array(
		'product' => $__vars['record'],
		'isPrivate' => $__vars['isPrivate'],
		'userGroups' => $__vars['userGroups'],
		'users' => $__vars['users'],
		'entries' => $__vars['entries'],
		'routeBase' => 'permissions/dbtech-ecommerce-products',
	), $__vars);
	return $__finalCompiled;
}
);