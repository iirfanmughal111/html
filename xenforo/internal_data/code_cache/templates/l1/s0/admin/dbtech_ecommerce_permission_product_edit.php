<?php
// FROM HASH: 69bb154b1232eea4cc055dbed7895e4f
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
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['record']['title'])), $__templater->func('link', array('permissions/dbtech-ecommerce-products', $__vars['record'], ), false), array(
	));
	$__finalCompiled .= '

' . $__templater->callMacro('dbtech_ecommerce_permission_product_macros', 'edit', array(
		'product' => $__vars['record'],
		'permissionData' => $__vars['permissionData'],
		'typeEntries' => $__vars['typeEntries'],
		'routeBase' => 'permissions/dbtech-ecommerce-products',
		'saveParams' => $__vars['saveParams'],
	), $__vars);
	return $__finalCompiled;
}
);