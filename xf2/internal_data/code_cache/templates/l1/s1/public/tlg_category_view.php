<?php
// FROM HASH: 93a0098a1b06ba623c472ace9c64cc3c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['category']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped($__templater->escape($__vars['category']['description']));
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:group-categories', $__vars['category'], array('page' => $__vars['page'], ), ), false),
	), $__vars) . '

' . $__templater->callMacro('tlg_group_page_options', 'page_options', array(
		'category' => $__vars['category'],
	), $__vars) . '

';
	if ($__templater->method($__vars['category'], 'canAddGroup', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
    ' . $__templater->button('Add new group' . $__vars['xf']['language']['ellipsis'], array(
			'href' => $__templater->func('link', array('group-categories/add', $__vars['category'], ), false),
			'class' => 'button--cta',
			'icon' => 'write',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->callMacro('tlg_group_list_macros', 'group_list_block', array(
		'baseUrl' => 'group-categories',
		'groups' => $__vars['groups'],
		'page' => $__vars['page'],
		'perPage' => $__vars['perPage'],
		'total' => $__vars['total'],
		'filters' => $__vars['filters'],
		'category' => $__vars['category'],
		'linkData' => $__vars['category'],
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
		'selected' => $__vars['category']['category_id'],
		'categoryTree' => $__vars['categoryTree'],
		'categoryExtras' => $__vars['categoryExtras'],
	), $__vars) . '
', 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySideNavHtml('_xfWidgetPositionSideNav07130f8b536905e991c42ee6bc58fb0a', $__templater->widgetPosition('tlg_category_view', array(
		'category' => $__vars['category'],
	)), 'replace');
	return $__finalCompiled;
}
);