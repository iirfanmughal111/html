<?php
// FROM HASH: d42ec33b8acbee00919352bf1be2ff91
return array(
'macros' => array('member_list_item_actions' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'member' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                ';
	if ($__templater->method($__vars['member'], 'canBePromote', array())) {
		$__compilerTemp1 .= '
                    ';
		if ((!$__templater->method($__vars['member'], 'isAdmin', array())) AND (!$__templater->method($__vars['member'], 'isModerator', array()))) {
			$__compilerTemp1 .= '
                        <a href="' . $__templater->func('link', array('group-members/promote', $__vars['member'], ), true) . '"
                           class="menu-linkRow" data-xf-click="overlay">' . 'Promote member' . '</a>
                    ';
		}
		$__compilerTemp1 .= '

                    ';
		if ($__templater->method($__vars['member'], 'isAdmin', array())) {
			$__compilerTemp1 .= '
                        <a href="' . $__templater->func('link', array('group-members/promote', $__vars['member'], array('type' => 'admin', ), ), true) . '"
                           class="menu-linkRow">' . 'Remove as Admin' . '</a>
                    ';
		} else {
			$__compilerTemp1 .= '
                        <a href="' . $__templater->func('link', array('group-members/promote', $__vars['member'], array('type' => 'admin', ), ), true) . '"
                           class="menu-linkRow">' . 'Make Admin' . '</a>
                    ';
		}
		$__compilerTemp1 .= '

                    ';
		if ($__templater->method($__vars['member'], 'isModerator', array())) {
			$__compilerTemp1 .= '
                        <a href="' . $__templater->func('link', array('group-members/promote', $__vars['member'], array('type' => 'moderator', ), ), true) . '"
                           class="menu-linkRow">' . 'Remove as Moderator' . '</a>
                    ';
		} else {
			$__compilerTemp1 .= '
                        <a href="' . $__templater->func('link', array('group-members/promote', $__vars['member'], array('type' => 'moderator', ), ), true) . '"
                           class="menu-linkRow">' . 'Make Moderator' . '</a>
                    ';
		}
		$__compilerTemp1 .= '
                ';
	}
	$__compilerTemp1 .= '

                ';
	if ($__templater->method($__vars['member'], 'canLiftBan', array())) {
		$__compilerTemp1 .= '
                    <a href="' . $__templater->func('link', array('group-members/lift-ban', $__vars['member'], ), true) . '"
                       class="menu-linkRow">' . 'Lift ban' . '</a>
                ';
	} else if ($__templater->method($__vars['member'], 'canBeBanned', array())) {
		$__compilerTemp1 .= '
                    <a href="' . $__templater->func('link', array('group-members/ban', $__vars['member'], ), true) . '"
                       class="menu-linkRow" data-xf-click="overlay">' . 'Ban member' . '</a>
                ';
	}
	$__compilerTemp1 .= '

                ';
	if ($__templater->method($__vars['member'], 'canBeApproved', array())) {
		$__compilerTemp1 .= '
                    <a href="' . $__templater->func('link', array('group-members/approve', $__vars['member'], ), true) . '"
                       class="menu-linkRow">' . 'Approve member' . '</a>
                ';
	}
	$__compilerTemp1 .= '

                ' . '

                ';
	if ($__templater->method($__vars['member'], 'canBeRemove', array())) {
		$__compilerTemp1 .= '
                    <span class="divider"></span>
                    <a href="' . $__templater->func('link', array('group-members/remove', $__vars['member'], ), true) . '"
                       class="menu-linkRow" data-xf-click="overlay">' . 'Remove member' . '</a>
                ';
	}
	$__compilerTemp1 .= '
            ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
    ' . $__templater->button($__templater->fontAwesome('fa-cog', array(
		)), array(
			'class' => 'button--link menuTrigger groupList-item--actions',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'title' => $__templater->filter('More options', array(array('for_attr', array()),), false),
		), '', array(
		)) . '
    <div class="menu" data-menu="menu" aria-hidden="true">
        <div class="menu-content">
            ' . $__compilerTemp1 . '
        </div>
    </div>
';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'member_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'members' => '!',
		'canInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '

<ol class="groupMembers listPlain">
    ';
	if ($__templater->isTraversable($__vars['members'])) {
		foreach ($__vars['members'] AS $__vars['member']) {
			$__finalCompiled .= '
        ' . $__templater->callMacro(null, 'member_list_item', array(
				'member' => $__vars['member'],
				'canInlineMod' => $__vars['canInlineMod'],
			), $__vars) . '
    ';
		}
	}
	$__finalCompiled .= '
</ol>
';
	return $__finalCompiled;
}
),
'member_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'member' => '!',
		'canInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <li class="block-row ' . $__templater->escape($__vars['member']['member_role_id']) . ' ' . $__templater->escape($__vars['member']['member_state']) . ($__vars['canInlineMod'] ? ' js-inlineModContainer' : '') . '"
        data-author="' . ($__vars['member']['User'] ? $__templater->escape($__vars['member']['User']['username']) : $__templater->escape($__vars['member']['username'])) . '">
        <div class="contentRow">
            <div class="contentRow-figure">
                ' . $__templater->func('avatar', array($__vars['member']['User'], 'm', false, array(
		'defaultname' => $__vars['member']['username'],
	))) . '
            </div>

            <div class="contentRow-main">
                ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= $__templater->callMacro(null, 'member_list_item_actions', array(
		'member' => $__vars['member'],
	), $__vars);
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
                    <div class="contentRow-extra contentRow-extra--large">
                        ' . $__compilerTemp1 . '
                    </div>
                ';
	}
	$__finalCompiled .= '

                <h3 class="contentRow-title">
                    ' . $__templater->func('username_link', array($__vars['member']['User'], false, array(
		'defaultname' => $__vars['member']['username'],
	))) . '
                </h3>

                <div class="contentRow-minor">
                    <ul class="listInline listInline--bullet">
                        <li>
                            <span>' . 'Member role' . $__vars['xf']['language']['label_separator'] . '</span>
                            <span>' . $__templater->escape($__vars['member']['MemberRole']['title']) . '</span>
                        </li>
                        <li>
                            <span>' . 'Member status' . $__vars['xf']['language']['label_separator'] . '</span>
                            <span>' . $__templater->escape($__vars['member']['member_state_title']) . '</span>
                        </li>
                        <li>
                            <span>' . ($__templater->method($__vars['member'], 'isInvited', array()) ? 'Invited date' . $__vars['xf']['language']['label_separator'] : 'Join date' . $__vars['xf']['language']['label_separator']) . '</span>
                            <span>' . $__templater->func('date_dynamic', array($__vars['member']['joined_date'], array(
	))) . '</span>
                        </li>
                        ';
	if ($__vars['member']['GroupView']) {
		$__finalCompiled .= '
                            <li>
                                <span>' . 'Last viewed group' . $__vars['xf']['language']['label_separator'] . '</span>
                                <span>' . $__templater->func('date_dynamic', array($__vars['member']['GroupView']['view_date'], array(
		))) . '</span>
                            </li>
                        ';
	}
	$__finalCompiled .= '
                    </ul>
                </div>

                <!-- TLG_GROUP_MEMBERS: member_list_item::extra_minor -->

                ';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
                <div class="contentRow-minor contentRow-minor--hideLinks">
                    ' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'name' => 'ids[]',
			'value' => $__vars['member']['member_id'],
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '
                </div>
                ';
	}
	$__finalCompiled .= '
            </div>
        </div>
    </li>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Members');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('members');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:groups/members', $__vars['group'], ), false),
	), $__vars) . '

';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
    ';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<section class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '"
         data-type="tl_group_member' . $__templater->escape($__vars['group']['group_id']) . '" data-href="' . $__templater->func('link', array('inline-mod', null, array('group_id' => $__vars['group']['group_id'], ), ), true) . '">
    <div class="block-outer">
        ' . $__templater->callMacro('tlg_filter_bar_macros', 'member_list_filter_bar', array(
		'group' => $__vars['group'],
		'filterUser' => $__vars['filterUser'],
		'filters' => $__vars['filters'],
	), $__vars) . '

        ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                        ';
	if ($__vars['canInlineMod']) {
		$__compilerTemp2 .= '
                            ' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
                        ';
	}
	$__compilerTemp2 .= '
                    ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
            <div class="block-outer-opposite">
                <div class="buttonGroup">
                    ' . $__compilerTemp2 . '
                </div>
            </div>
        ';
	}
	$__finalCompiled .= '
    </div>

    <div class="block-container">
        <div class="block-body">
            ';
	if (!$__templater->test($__vars['members'], 'empty', array())) {
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'member_list', array(
			'members' => $__vars['members'],
			'canInlineMod' => $__vars['canInlineMod'],
		), $__vars) . '
            ';
	} else {
		$__finalCompiled .= '
                <div class="block-row">' . 'There are no members matching your filters.' . '</div>
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>

    <div class="block-outer block-outer--after">
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'groups/members',
		'data' => $__vars['group'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
        ' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
    </div>
</section>

' . '

' . '

';
	return $__finalCompiled;
}
);