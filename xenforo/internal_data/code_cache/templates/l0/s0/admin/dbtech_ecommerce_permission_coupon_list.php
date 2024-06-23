<?php
// FROM HASH: f664ecebf3052cac651f993867d8b75d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['record']['title']));
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_permission_coupon_macros', 'list', array(
		'coupon' => $__vars['record'],
		'isPrivate' => $__vars['isPrivate'],
		'userGroups' => $__vars['userGroups'],
		'users' => $__vars['users'],
		'entries' => $__vars['entries'],
		'routeBase' => 'permissions/dbtech-ecommerce-coupons',
	), $__vars);
	return $__finalCompiled;
}
);