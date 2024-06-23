<?php
// FROM HASH: 43a43523f13e686a777261181c1d5849
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
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['record']['title'])), $__templater->func('link', array('permissions/dbtech-ecommerce-categories', $__vars['record'], ), false), array(
	));
	$__finalCompiled .= '

' . $__templater->callMacro('permission_category_macros', 'edit', array(
		'category' => $__vars['record'],
		'permissionData' => $__vars['permissionData'],
		'typeEntries' => $__vars['typeEntries'],
		'routeBase' => 'permissions/dbtech-ecommerce-categories',
		'saveParams' => $__vars['saveParams'],
	), $__vars);
	return $__finalCompiled;
}
);