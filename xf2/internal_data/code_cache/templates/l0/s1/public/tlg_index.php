<?php
// FROM HASH: 7c30c6ba48f2c1034c9f76107193ddd3
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
	return $__finalCompiled;
}
);