<?php
// FROM HASH: 0427f7bb6025eb77741c89cd148c59da
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['record']['title']));
	$__finalCompiled .= '

' . $__templater->callMacro('permission_category_macros', 'list', array(
		'category' => $__vars['record'],
		'isPrivate' => $__vars['isPrivate'],
		'userGroups' => $__vars['userGroups'],
		'users' => $__vars['users'],
		'entries' => $__vars['entries'],
		'routeBase' => 'permissions/xa-sc-categories',
	), $__vars);
	return $__finalCompiled;
}
);