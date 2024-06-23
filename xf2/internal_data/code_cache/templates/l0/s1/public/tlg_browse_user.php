<?php
// FROM HASH: 8293aa0650d9929b4a7c842987ae6e8c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('
    ' . (($__vars['user']['user_id'] == $__vars['xf']['visitor']['user_id']) ? 'Your groups' : 'Groups of user ' . $__templater->escape($__vars['user']['username']) . '') . '
');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['user']['username'])), $__templater->func('link', array('canonical:members', $__vars['user'], ), false), array(
	));
	$__finalCompiled .= '

' . $__templater->callMacro('tlg_group_list_macros', 'group_list_block', array(
		'baseUrl' => 'groups/browse/user',
		'groups' => $__vars['groups'],
		'page' => $__vars['page'],
		'perPage' => $__vars['perPage'],
		'total' => $__vars['total'],
		'filters' => $__vars['filters'],
		'creatorFilter' => $__vars['creatorFilter'],
		'canInlineMod' => false,
		'showMembers' => true,
		'columnsPerRow' => 3,
		'filterBarMacroName' => 'tlg_filter_bar_macros::user_group_filter_bar',
	), $__vars) . '
';
	return $__finalCompiled;
}
);