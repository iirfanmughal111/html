<?php
// FROM HASH: 1bd708de2eed6d51d402a948acc7039d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Groups');
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:groups', null, array('page' => $__vars['page'], ), ), false),
	), $__vars) . '

';
	$__templater->setPageParam('searchConstraints', array('Groups' => array('search_type' => 'tl_group', ), ));
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'canAddGroup', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
    ' . $__templater->button('Add new group' . $__vars['xf']['language']['ellipsis'], array(
			'href' => $__templater->func('link', array('groups/add', ), false),
			'class' => 'button--cta',
			'icon' => 'write',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->callMacro('tlg_group_list_macros', 'group_list_block', array(
		'baseUrl' => 'groups',
		'groups' => $__vars['groups'],
		'page' => $__vars['page'],
		'perPage' => $__vars['perPage'],
		'total' => $__vars['total'],
		'filters' => $__vars['filters'],
		'creatorFilter' => $__vars['creatorFilter'],
		'canInlineMod' => $__vars['canInlineMod'],
		'showMembers' => true,
		'columnsPerRow' => 2,
	), $__vars) . '

';
	$__templater->setPageParam('sideNavTitle', 'Categories');
	$__finalCompiled .= '
';
	$__templater->modifySideNavHtml(null, '
    ' . $__templater->callMacro('tlg_category_list_macros', 'simple_list_block', array(
		'categoryTree' => $__vars['categoryTree'],
		'categoryExtras' => $__vars['categoryExtras'],
	), $__vars) . '
', 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySideNavHtml('_xfWidgetPositionSideNav949c555b52b03cb681179450fbef9f86', $__templater->widgetPosition('tlg_group_index', array()), 'replace');
	return $__finalCompiled;
}
);