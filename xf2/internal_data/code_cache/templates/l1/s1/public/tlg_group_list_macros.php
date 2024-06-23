<?php
// FROM HASH: 88ff76a9ec65b5111d58a73b5299a1d0
return array(
'macros' => array('group_list_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'baseUrl' => '!',
		'groups' => '!',
		'canInlineMod' => '!',
		'page' => '!',
		'perPage' => '!',
		'total' => '!',
		'linkData' => null,
		'category' => null,
		'filters' => null,
		'creatorFilter' => null,
		'showFilters' => true,
		'showMembers' => false,
		'columnsPerRow' => 3,
		'filterBarMacroName' => 'tlg_filter_bar_macros::group_list_filter_bar',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__vars['canInlineMod']) {
		$__finalCompiled .= '
        ';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
    ';
	}
	$__finalCompiled .= '

    ';
	$__templater->includeCss('tlg_group_list.less');
	$__finalCompiled .= '

    <div class="block groupListBlock' . (($__vars['total'] > 0) ? '' : ' groupListBlock--empty') . '" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '"
         data-type="tl_group"
         data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
        <div class="block-outer">';
	$__compilerTemp1 = '';
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
		$__compilerTemp1 .= '
                <div class="block-outer-opposite">
                    <div class="buttonGroup">
                        ' . $__compilerTemp2 . '
                    </div>
                </div>
            ';
	}
	$__finalCompiled .= $__templater->func('trim', array('

            ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => $__vars['baseUrl'],
		'params' => $__vars['filters'],
		'data' => $__vars['linkData'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

            ' . $__compilerTemp1 . '

        '), false) . '</div>

        ';
	if ($__vars['showFilters']) {
		$__finalCompiled .= '
            <div class="block-container">
                ' . $__templater->callMacro(null, $__vars['filterBarMacroName'], array(
			'filters' => $__vars['filters'],
			'baseLinkPath' => $__vars['baseUrl'],
			'category' => $__vars['category'],
			'creatorFilter' => $__vars['creatorFilter'],
		), $__vars) . '
            </div>
        ';
	}
	$__finalCompiled .= '

        ';
	if (!$__templater->test($__vars['groups'], 'empty', array())) {
		$__finalCompiled .= '
            ' . $__templater->callMacro(null, 'group_list', array(
			'columnsPerRow' => $__vars['columnsPerRow'],
			'canInlineMod' => $__vars['canInlineMod'],
			'showMembers' => $__vars['showMembers'],
			'groups' => $__vars['groups'],
		), $__vars) . '
        ';
	} else if ($__vars['filters'] AND $__vars['showFilters']) {
		$__finalCompiled .= '
            <div class="blockMessage">' . 'There are no groups matching your filters.' . '</div>
        ';
	} else {
		$__finalCompiled .= '
            <div class="blockMessage">' . 'There are no groups have been created yet.' . '</div>
        ';
	}
	$__finalCompiled .= '

        <div class="block-outer block-outer--after">
            ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => $__vars['baseUrl'],
		'data' => $__vars['linkData'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
            ' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'group_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'groups' => '!',
		'canInlineMod' => false,
		'showMembers' => false,
		'columnsPerRow' => 3,
		'showSettings' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

    ';
	if (!$__templater->test($__vars['groups'], 'empty', array())) {
		$__finalCompiled .= '
        <div class="groupList h-dFlex h-dFlex--wrap gridCardList--flex--' . $__templater->escape($__vars['columnsPerRow']) . '-col" data-xf-init="tl_groups_list">
            ';
		if ($__templater->isTraversable($__vars['groups'])) {
			foreach ($__vars['groups'] AS $__vars['group']) {
				$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'group', array(
					'showInlineMod' => $__vars['canInlineMod'],
					'showMembers' => $__vars['showMembers'],
					'showSettings' => $__vars['showSettings'],
					'group' => $__vars['group'],
				), $__vars) . '
            ';
			}
		}
		$__finalCompiled .= '
        </div>
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'group_simple' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'showCategory' => true,
		'showJoinButton' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '

    <div class="contentRow">
        <div class="contentRow-figure">
            ' . $__templater->callback('Truonglv\\Groups\\Callback', 'renderAvatar', '', array('group' => $__vars['group'], )) . '
        </div>

        <div class="contentRow-main contentRow-main--close">
            <a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '"
               data-preview-url="' . $__templater->func('link', array('groups/preview', $__vars['group'], ), true) . '"
               data-xf-init="preview-tooltip">' . $__templater->escape($__vars['group']['name']) . '</a>

            <div class="contentRow-minor contentRow-minor--hideLinks">
                <ul class="listInline listInline--bullet">
                    <li>' . $__templater->callMacro('tlg_group_macros', 'privacy_html', array(
		'group' => $__vars['group'],
	), $__vars) . '</li>
                    <li>' . '' . $__templater->filter($__vars['group']['member_count'], array(array('number_short', array()),), true) . ' members' . '</li>
                </ul>
            </div>
            ';
	if ($__vars['showCategory']) {
		$__finalCompiled .= '
                <div class="contentRow-minor contentRow-minor--hideLinks">
                    <a href="' . $__templater->func('link', array('group-categories', $__vars['group']['Category'], ), true) . '">' . $__templater->escape($__vars['group']['Category']['category_title']) . '</a>
                </div>
            ';
	}
	$__finalCompiled .= '
            ';
	if ($__vars['showJoinButton'] AND $__templater->method($__vars['group'], 'canJoin', array())) {
		$__finalCompiled .= '
                ' . $__templater->button('
                    ' . $__templater->fontAwesome('fas fa-sign-in', array(
		)) . '
                    ' . 'Join group' . '
                ', array(
			'href' => $__templater->func('link', array('groups/join', $__vars['group'], ), false),
			'overlay' => 'true',
			'class' => 'button--small button--icon button--link',
		), '', array(
		)) . '
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'group' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'showInlineMod' => false,
		'showSettings' => true,
		'showMembers' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_group_list.less');
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '

    ';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/group.js',
		'addon' => 'Truonglv/Groups',
		'min' => '1',
	));
	$__finalCompiled .= '

    ';
	$__compilerTemp1 = '';
	if ($__vars['showInlineMod']) {
		$__compilerTemp1 .= '
                <span class="groupCover--inlineMod">
                    ' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'name' => 'ids[]',
			'value' => $__vars['group']['group_id'],
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'hiddenLabel' => 'true',
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '
                </span>
            ';
	}
	$__compilerTemp2 = '';
	if (!$__templater->method($__vars['group'], 'isVisible', array())) {
		$__compilerTemp2 .= '
                <span class="badge badge--highlighted">' . $__templater->escape($__templater->method($__vars['group'], 'getGroupStatePhrase', array())) . '</span>
            ';
	}
	$__vars['coverHtml'] = $__templater->preEscaped('
        <div class="groupCover--wrapper">
            ' . $__templater->callback('Truonglv\\Groups\\Callback', 'renderCover', '', array('group' => $__vars['group'], 'forceHeight' => 100, )) . '

            ' . $__compilerTemp1 . '
            ' . $__compilerTemp2 . '
        </div>
    ');
	$__finalCompiled .= '

    ';
	$__vars['avatarHtml'] = $__templater->preEscaped($__templater->callback('Truonglv\\Groups\\Callback', 'renderAvatar', '', array('group' => $__vars['group'], 'full' => false, )));
	$__finalCompiled .= '

    ';
	$__compilerTemp3 = '';
	if (($__vars['xf']['visitor']['user_id'] > 0) AND $__vars['showSettings']) {
		$__compilerTemp3 .= '
            ' . $__templater->callMacro('tlg_group_macros', 'settings', array(
			'group' => $__vars['group'],
		), $__vars) . '
        ';
	}
	$__vars['actionHtml'] = $__templater->preEscaped('
        ' . $__compilerTemp3 . '
    ');
	$__finalCompiled .= '

    ';
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['group'], 'isUnread', array())) {
		$__compilerTemp4 .= '
                <span class="badge badge--highlighted"></span>
            ';
	}
	$__vars['cardTitle'] = $__templater->preEscaped($__templater->func('trim', array('
        <a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '" class="gridCard--header--title"
           data-tp-primary="on">
            <span>' . $__templater->escape($__vars['group']['name']) . '</span>
            ' . $__compilerTemp4 . '
        </a>
    '), false));
	$__finalCompiled .= '
    ';
	$__vars['config'] = array('cardClass' => ($__vars['group']['group_state'] . ' ') . $__vars['group']['privacy'], );
	$__finalCompiled .= '

    ';
	$__compilerTemp5 = '';
	if ($__vars['xf']['options']['tl_groups_enableForums']) {
		$__compilerTemp5 .= '
                <li class="groupItem-stat groupItem-stat--discussionCount">
                    ' . $__templater->fontAwesome('fa-comment', array(
		)) . '
                    ' . $__templater->filter($__vars['group']['discussion_count'], array(array('number_short', array()),), true) . '
                </li>
            ';
	}
	$__vars['extraHeaderHtml'] = $__templater->preEscaped($__templater->func('trim', array('
        <ul class="listInline u-muted groupItem--meta">
            <li>' . $__templater->callMacro('tlg_group_macros', 'privacy_html', array(
		'group' => $__vars['group'],
	), $__vars) . '</li>
        </ul>

        <ul class="listInline group--counterList u-muted">
            <li class="groupItem-stat groupItem-stat--viewCount">
                ' . $__templater->fontAwesome('fa-eye', array(
	)) . '
                ' . $__templater->filter($__vars['group']['view_count'], array(array('number_short', array()),), true) . '
            </li>
            <li class="groupItem-stat groupItem-stat--memberCount">
                ' . $__templater->fontAwesome('fa-users', array(
	)) . '
                ' . $__templater->filter($__vars['group']['member_count'], array(array('number_short', array()),), true) . '
            </li>
            ' . $__compilerTemp5 . '
            <li class="groupItem-stat groupItem-stat--eventCount">
                ' . $__templater->fontAwesome('fa-calendar', array(
	)) . '
                ' . $__templater->filter($__vars['group']['event_count'], array(array('number_short', array()),), true) . '
            </li>
        </ul>
    '), false));
	$__finalCompiled .= '

    ';
	$__vars['bodyHtml'] = $__templater->preEscaped('
        <div class="groupList--description u-muted">' . $__templater->escape($__vars['group']['short_description']) . '</div>
    ');
	$__finalCompiled .= '

    ';
	$__compilerTemp6 = '';
	if (($__vars['xf']['options']['tl_groups_maxMembersInCard'] > 0) AND $__vars['showMembers']) {
		$__compilerTemp6 .= '
            <ol class="listInline groupItem--members">
                ';
		if ($__templater->isTraversable($__vars['group']['CardMembers'])) {
			foreach ($__vars['group']['CardMembers'] AS $__vars['member']) {
				$__compilerTemp6 .= '
                    <li>' . $__templater->func('avatar', array($__vars['member']['User'], 'xs', false, array(
					'defaultname' => $__vars['member']['username'],
				))) . '</li>
                ';
			}
		}
		$__compilerTemp6 .= '
            </ol>
        ';
	}
	$__vars['footerHtml'] = $__templater->preEscaped('
        ' . $__compilerTemp6 . '

        ' . $__templater->callMacro('tlg_group_macros', 'join_button', array(
		'hiddenLeave' => true,
		'group' => $__vars['group'],
	), $__vars) . '
    ');
	$__finalCompiled .= '

    ' . $__templater->callMacro('tlg_grid_card_macros', 'card', array(
		'cardTitle' => $__vars['cardTitle'],
		'extraHeaderHtml' => $__vars['extraHeaderHtml'],
		'config' => $__vars['config'],
		'bodyHtml' => $__vars['bodyHtml'],
		'footerHtml' => $__vars['footerHtml'],
		'actionHtml' => $__vars['actionHtml'],
		'avatarHtml' => $__vars['avatarHtml'],
		'coverHtml' => $__vars['coverHtml'],
	), $__vars) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

' . '

';
	return $__finalCompiled;
}
);