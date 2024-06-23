<?php
// FROM HASH: 03c17956b920cc43d9608425b9d61fc5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['noH1'] = true;
	$__finalCompiled .= '

';
	if ($__templater->test($__vars['noBreadcrumbs'], 'empty', array())) {
		$__finalCompiled .= '
    ';
		$__templater->breadcrumbs($__templater->method($__vars['group'], 'getBreadcrumbs', array($__vars['pageSelected'] != 'newsFeed', )));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '

';
	$__templater->setPageParam('sideNavTitle', $__templater->filter($__vars['group']['name'], array(array('raw', array()),), true));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['group'], 'canInvitePeople', array())) {
		$__compilerTemp1 .= '
        ' . $__templater->callMacro('tlg_group_macros', 'invite_member_form', array(
			'group' => $__vars['group'],
		), $__vars) . '
    ';
	}
	$__templater->modifySideNavHtml(null, '
    ' . '

    ' . $__templater->callMacro('tlg_group_macros', 'avatar_block', array(
		'group' => $__vars['group'],
		'blockClasses' => 'groupAvatar-block',
	), $__vars) . '

    ' . $__templater->callMacro('tlg_group_wrapper_macros', 'tabs', array(
		'group' => $__vars['group'],
		'selected' => $__vars['pageSelected'],
	), $__vars) . '

    ' . $__compilerTemp1 . '

    ' . $__templater->callMacro('tlg_group_macros', 'share_block', array(
		'group' => $__vars['group'],
	), $__vars) . '

    ' . $__templater->callMacro('tlg_group_macros', 'quick_overview', array(
		'group' => $__vars['group'],
	), $__vars) . '

    ' . $__templater->callMacro('tlg_group_macros', 'share_qr_code', array(
		'group' => $__vars['group'],
	), $__vars) . '

    ' . '
', 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySideNavHtml('_xfWidgetPositionSideNavecf204e5f234a9b0e1c9bb2ddc3aedd4', $__templater->widgetPosition('tlg_group_view_sidenav', array(
		'group' => $__vars['group'],
		'selected' => $__vars['pageSelected'],
	)), 'replace');
	$__finalCompiled .= '

';
	if ($__templater->test($__vars['noPageOptions'], 'empty', array())) {
		$__finalCompiled .= '
    ' . $__templater->callMacro('tlg_group_page_options', 'page_options', array(
			'category' => $__vars['group']['Category'],
			'group' => $__vars['group'],
		), $__vars) . '
';
	}
	$__finalCompiled .= '

<div class="groupWrapper groupWrapper-' . $__templater->escape($__vars['group']['group_id']) . '">
    ' . $__templater->callMacro('tlg_group_wrapper_macros', 'header', array(
		'group' => $__vars['group'],
	), $__vars) . '

    ' . $__templater->callMacro('tlg_group_wrapper_macros', 'cover_header', array(
		'isEditing' => $__vars['coverEditor'],
		'navSelected' => $__vars['pageSelected'],
		'group' => $__vars['group'],
	), $__vars) . '

    ';
	if ($__vars['group']['Member'] AND $__templater->method($__vars['group']['Member'], 'isInvited', array())) {
		$__finalCompiled .= '
        <div class="blockMessage blockMessage--important">
            ' . 'Somebody has invited you join this group' . '
            ' . $__templater->button('No thanks', array(
			'href' => $__templater->func('link', array('group-members/leave', $__vars['group']['Member'], ), false),
			'class' => 'button--cta',
			'overlay' => 'true',
		), '', array(
		)) . '
            ' . $__templater->button('Accept', array(
			'href' => $__templater->func('link', array('group-members/accepted', $__vars['group']['Member'], ), false),
			'class' => 'button--link',
		), '', array(
		)) . '
        </div>
    ';
	}
	$__finalCompiled .= '

    ' . $__templater->callMacro('tlg_group_wrapper_macros', 'status', array(
		'group' => $__vars['group'],
	), $__vars) . '

    ' . $__templater->filter($__vars['innerContent'], array(array('raw', array()),), true) . '
</div>
';
	return $__finalCompiled;
}
);