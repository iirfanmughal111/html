<?php
// FROM HASH: ed775b8c3ba391ffb46c67e7dc328897
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
	$__templater->breadcrumb($__templater->preEscaped('Permissions' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['record']['title'])), $__templater->func('link', array('dbtech-ecommerce/coupons/permissions', $__vars['record'], ), false), array(
	));
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_permission_coupon_macros', 'edit', array(
		'coupon' => $__vars['record'],
		'permissionData' => $__vars['permissionData'],
		'typeEntries' => $__vars['typeEntries'],
		'routeBase' => 'dbtech-ecommerce/coupons/permissions',
		'saveParams' => $__vars['saveParams'],
	), $__vars);
	return $__finalCompiled;
}
);