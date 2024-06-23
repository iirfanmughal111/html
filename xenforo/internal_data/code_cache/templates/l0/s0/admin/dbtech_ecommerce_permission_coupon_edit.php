<?php
// FROM HASH: c20df92d5755040eba87c90379d78bd4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['userGroup']) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['userGroup']['title']));
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['user']['username']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['record']['title'])), $__templater->func('link', array('permissions/dbtech-ecommerce-coupons', $__vars['record'], ), false), array(
	));
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_permission_coupon_macros', 'edit', array(
		'coupon' => $__vars['record'],
		'permissionData' => $__vars['permissionData'],
		'typeEntries' => $__vars['typeEntries'],
		'routeBase' => 'permissions/dbtech-ecommerce-coupons',
		'saveParams' => $__vars['saveParams'],
	), $__vars);
	return $__finalCompiled;
}
);