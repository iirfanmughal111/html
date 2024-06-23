<?php
// FROM HASH: 1ebd5e153d69e0789f8f3c875fff381c
return array(
'macros' => array('privacy_html' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <span class="groupPrivacy groupPrivacy--' . $__templater->escape($__vars['group']['privacy']) . '">
        ';
	if ($__vars['group']['privacy'] == 'public') {
		$__finalCompiled .= '
            ' . $__templater->fontAwesome('fa-globe', array(
		)) . ' ' . 'Public Group' . '
        ';
	} else if ($__vars['group']['privacy'] == 'closed') {
		$__finalCompiled .= '
            ' . $__templater->fontAwesome('fa-lock', array(
		)) . ' ' . 'Closed Group' . '
        ';
	} else if ($__vars['group']['privacy'] == 'secret') {
		$__finalCompiled .= '
            ' . $__templater->fontAwesome('fa-user-secret', array(
		)) . ' ' . 'Secret Group' . '
        ';
	}
	$__finalCompiled .= '
    </span>

    ' . '
';
	return $__finalCompiled;
}
),
'avatar_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'blockClasses' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block' . ($__vars['blockClasses'] ? (' ' . $__templater->escape($__vars['blockClasses'])) : '') . '">
        ' . $__templater->callback('Truonglv\\Groups\\Callback', 'renderAvatar', '', array('group' => $__vars['group'], )) . '
    </div>
';
	return $__finalCompiled;
}
),
'avatar_html' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'avatarText' => null,
		'attrs' => null,
		'full' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '"
       class="groupAvatar groupAvatar--link' . ($__vars['group']['AvatarAttachment'] ? '' : ' groupAvatar--default') . '"' . ($__vars['attrs'] ? (' ' . $__templater->escape($__vars['attrs'])) : '') . '>
        ';
	if ($__vars['group']['AvatarAttachment']) {
		$__finalCompiled .= '
            <img src="' . ($__vars['full'] ? $__templater->escape($__templater->method($__vars['group'], 'getAvatarUrl', array(true, ))) : $__templater->escape($__vars['group']['AvatarAttachment']['thumbnail_url'])) . '"
                 class="groupAvatar--img" width="' . ($__vars['full'] ? 250 : 100) . '" height="' . ($__vars['full'] ? 250 : 100) . '"
                 data-width="' . $__templater->escape($__vars['group']['AvatarAttachment']['width']) . '"
                 data-height="' . $__templater->escape($__vars['group']['AvatarAttachment']['height']) . '"
                 alt="' . $__templater->escape($__vars['group']['name']) . '"/>
        ';
	} else {
		$__finalCompiled .= '
            <span class="groupAvatar--text groupAvatar--dynamic">' . $__templater->escape($__vars['avatarText']) . '</span>
        ';
	}
	$__finalCompiled .= '
    </a>
';
	return $__finalCompiled;
}
),
'cover' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'forceHeight' => 0,
		'isRepositioning' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/group.js',
		'addon' => 'Truonglv/Groups',
		'min' => '1',
	));
	$__finalCompiled .= '

    ' . $__templater->callback('Truonglv\\Groups\\Callback', 'renderCover', '', array('group' => $__vars['group'], 'forceHeight' => $__vars['forceHeight'], 'isRepositioning' => $__vars['isRepositioning'], )) . '
';
	return $__finalCompiled;
}
),
'cover_html' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'attrs' => null,
		'imgAttrs' => null,
		'forceHeight' => null,
		'lazy' => true,
		'repositioning' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="groupCover groupCoverFrame' . ($__vars['repositioning'] ? ' groupCoverFrame--setup' : '') . ($__vars['group']['CoverAttachment'] ? '' : ' groupCover--default') . '"' . ($__vars['attrs'] ? (' ' . $__templater->escape($__vars['attrs'])) : '') . '>
        <a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '"' . ($__vars['attrs'] ? (' ' . $__templater->escape($__vars['attrs'])) : '') . '>
            ';
	if ($__vars['group']['CoverAttachment']) {
		$__finalCompiled .= '
                <img data-crop="' . $__templater->filter($__templater->method($__vars['group'], 'getCoverCropData', array()), array(array('json', array()),), true) . '"
                     class="groupCover--img' . ($__vars['lazy'] ? ' groupCover--lazy' : '') . '" data-xf-init="tlg-cover-setup"
                     ' . ($__vars['imgAttrs'] ? (' ' . $__templater->escape($__vars['imgAttrs'])) : '') . '
                     ' . ($__vars['forceHeight'] ? ((' data-force-height="' . $__templater->escape($__vars['forceHeight'])) . '"') : '') . '/>
            ';
	} else {
		$__finalCompiled .= '
                <span class="groupCover--text">' . $__templater->func('snippet', array($__vars['group']['name'], 25, ), true) . '</span>
            ';
	}
	$__finalCompiled .= '
        </a>
    </div>
';
	return $__finalCompiled;
}
),
'privacy_row' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => null,
		'selected' => null,
		'allowSecret' => false,
		'allowClosed' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = array(array(
		'value' => 'public',
		'label' => 'Public Group',
		'hint' => 'Anyone can find the group and view it\'s content.',
		'_dependent' => array($__templater->formCheckBox(array(
	), array(array(
		'name' => 'allow_guest_posting',
		'selected' => $__vars['group']['allow_guest_posting'],
		'value' => '1',
		'label' => 'Also allow guest posting into news feed' . $__vars['xf']['language']['ellipsis'],
		'_type' => 'option',
	)))),
		'_type' => 'option',
	));
	if ($__vars['allowClosed']) {
		$__compilerTemp1[] = array(
			'value' => 'closed',
			'label' => 'Closed Group',
			'hint' => 'Anyone can find the group. Only members of the group have permission to view group content.',
			'_type' => 'option',
		);
	}
	if ($__vars['allowSecret']) {
		$__compilerTemp1[] = array(
			'value' => 'secret',
			'label' => 'Secret Group',
			'hint' => 'Only members of the group have permission to view the group and its content.',
			'_type' => 'option',
		);
	}
	$__finalCompiled .= $__templater->formRadioRow(array(
		'name' => 'privacy',
		'value' => $__vars['selected'],
	), $__compilerTemp1, array(
		'label' => 'Privacy',
	)) . '
';
	return $__finalCompiled;
}
),
'extra_privacy_rows' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'always_moderate_join',
		'selected' => $__vars['group']['always_moderate_join'],
		'label' => 'All requests to join must be approved by a moderator.',
		'hint' => 'If checked, all requests to join this group must be approved by a moderator.',
		'_type' => 'option',
	)), array(
		'label' => '',
	)) . '
';
	return $__finalCompiled;
}
),
'quick_overview' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block">
        <div class="block-container">
            <h3 class="block-minorHeader">' . 'Quick Overview' . '</h3>
            <div class="block-body block-row">
                <dl class="pairs pairs--justified">
                    <dt>' . 'Category' . '</dt>
                    <dd><a href="' . $__templater->func('link', array('group-categories', $__vars['group']['Category'], ), true) . '">' . $__templater->escape($__vars['group']['Category']['category_title']) . '</a></dd>
                </dl>

                ';
	if ($__vars['xf']['options']['tl_groups_enableLanguage'] > 0) {
		$__finalCompiled .= '
                    <dl class="pairs pairs--justified">
                        <dt>' . 'Language' . '</dt>
                        <dd>' . $__templater->escape($__vars['group']['language_title']) . '</dd>
                    </dl>
                ';
	}
	$__finalCompiled .= '

                <dl class="pairs pairs--justified">
                    <dt>' . 'Total members' . '</dt>
                    <dd>' . $__templater->filter($__vars['group']['member_count'], array(array('number_short', array()),), true) . '</dd>
                </dl>

                <dl class="pairs pairs--justified">
                    <dt>' . 'Total events' . '</dt>
                    <dd>' . $__templater->filter($__vars['group']['event_count'], array(array('number_short', array()),), true) . '</dd>
                </dl>

                ';
	if ($__vars['xf']['options']['tl_groups_enableForums']) {
		$__finalCompiled .= '
                    <dl class="pairs pairs--justified">
                        <dt>' . 'Total discussions' . '</dt>
                        <dd>' . $__templater->filter($__vars['group']['discussion_count'], array(array('number_short', array()),), true) . '</dd>
                    </dl>
                ';
	}
	$__finalCompiled .= '

                <dl class="pairs pairs--justified">
                    <dt>' . 'Total views' . '</dt>
                    <dd>' . $__templater->filter($__vars['group']['view_count'], array(array('number_short', array()),), true) . '</dd>
                </dl>

                ';
	$__vars['isXFMGEnabled'] = $__templater->preEscaped($__templater->callback('Truonglv\\Groups\\App', 'isEnabledXenMediaAddOn', '', array()));
	$__finalCompiled .= '
                ';
	if (!$__templater->test($__vars['isXFMGEnabled'], 'empty', array())) {
		$__finalCompiled .= '
                    <dl class="pairs pairs--justified">
                        <dt>' . 'Total albums' . '</dt>
                        <dd>' . $__templater->filter($__vars['group']['album_count'], array(array('number_short', array()),), true) . '</dd>
                    </dl>
                ';
	}
	$__finalCompiled .= '

                ' . '
            </div>
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'share_block' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block">
        <div class="block-container">
            <h3 class="block-minorHeader">' . 'Share this group' . '</h3>
            <div class="block-body block-row">
                ' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '

                ';
	$__vars['groupUrl'] = $__templater->preEscaped($__templater->func('link', array('canonical:groups', $__vars['group'], ), true));
	$__finalCompiled .= '

                ' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Group URL',
		'text' => $__vars['groupUrl'],
	), $__vars) . '

                ';
	$__vars['groupUrlBbCode'] = $__templater->preEscaped('[GROUP=' . $__templater->escape($__vars['group']['group_id']) . '][/GROUP]');
	$__finalCompiled .= '
                ' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy group BBCode',
		'text' => $__vars['groupUrlBbCode'],
	), $__vars) . '
            </div>
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'invite_member_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block">
        <div class="block-container">
            <h3 class="block-minorHeader">' . 'Invite member' . '</h3>
            <div class="block-footer">' . $__templater->fontAwesome('fa-exclamation-circle', array(
	)) . ' ' . 'Invite other people to join this group.' . '</div>
            ' . $__templater->form('
                <div class="block-body block-row">
                    ' . $__templater->formTextBox(array(
		'name' => 'username',
		'ac' => 'single',
		'autocomplete' => 'off',
		'autofocus' => 'off',
		'tabindex' => '-1',
		'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'username', ), false),
		'placeholder' => 'Name' . $__vars['xf']['language']['ellipsis'],
	)) . '

                    <div class="formSubmitRow--simple formSubmitRow">
                        <div class="formSubmitRow-controls">
                            ' . $__templater->button('
                                ' . $__templater->fontAwesome('fa-paper-plane', array(
	)) . ' ' . 'Invite' . '
                            ', array(
		'type' => 'submit',
		'class' => 'button--icon button--cta',
	), '', array(
	)) . '
                        </div>
                    </div>
                </div>
            ', array(
		'action' => $__templater->func('link', array('groups/invite', $__vars['group'], ), false),
		'method' => 'post',
		'ajax' => 'true',
	)) . '
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'settings' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'advanced' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                        ' . '
                        ';
	if ($__vars['group']['Member'] AND (!$__templater->method($__vars['group']['Member'], 'isInvited', array()))) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-members/leave', $__vars['group']['Member'], ), true) . '"
                                       class="menu-linkRow"
                                       data-xf-click="overlay">
                                ' . ($__templater->method($__vars['group']['Member'], 'isValidMember', array()) ? 'Leave group' : 'Cancel requesting') . '
                            </a>
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if ($__templater->method($__vars['group'], 'canReport', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('groups/report', $__vars['group'], ), true) . '"
                               class="menu-linkRow" data-xf-click="overlay">' . 'Report group' . '</a>
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if ($__templater->method($__vars['group'], 'canUseAsBadge', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('groups/badge', $__vars['group'], ), true) . '" class="menu-linkRow">
                                ' . 'Toggle display group badge' . '
                                <span>' . ($__templater->method($__vars['group'], 'isEnabledGroupBadge', array()) ? $__vars['xf']['language']['parenthesis_open'] . 'Enabled' . $__vars['xf']['language']['parenthesis_close'] : $__vars['xf']['language']['parenthesis_open'] . 'Disabled' . $__vars['xf']['language']['parenthesis_close']) . '</span>
                            </a>
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if ($__vars['group']['Member'] OR $__templater->method($__vars['group'], 'canReport', array())) {
		$__compilerTemp1 .= '<hr class="menu-separator" />';
	}
	$__compilerTemp1 .= '

                        ' . '

                        ';
	if (!$__templater->test($__vars['advanced'], 'empty', array())) {
		$__compilerTemp1 .= '
                            ' . $__templater->callMacro(null, 'settings_advanced', array(
			'group' => $__vars['group'],
		), $__vars) . '
                        ';
	}
	$__compilerTemp1 .= '

                        ' . '
                    ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
        <div class="buttonGroup-buttonWrapper">
            ' . $__templater->button($__templater->fontAwesome('fa-cog', array(
		)), array(
			'class' => 'button--link menuTrigger',
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
        </div>
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'settings_advanced' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->method($__vars['group'], 'canUpdatePrivacy', array())) {
		$__finalCompiled .= '
        <a href="' . $__templater->func('link', array('groups/privacy', $__vars['group'], ), true) . '" class="menu-linkRow">' . 'Update privacy' . '</a>
    ';
	}
	$__finalCompiled .= '

    ';
	if ($__templater->method($__vars['group'], 'canManageAvatar', array())) {
		$__finalCompiled .= '
        <a href="' . $__templater->func('link', array('groups/avatar', $__vars['group'], ), true) . '" class="menu-linkRow">' . 'Upload avatar' . '</a>
    ';
	}
	$__finalCompiled .= '

    ';
	if ($__templater->method($__vars['group'], 'canManageCover', array())) {
		$__finalCompiled .= '
        <a href="' . $__templater->func('link', array('groups/cover', $__vars['group'], ), true) . '" class="menu-linkRow">' . 'Upload cover' . '</a>
        ';
		if ($__vars['group']['cover_attachment_id'] > 0) {
			$__finalCompiled .= '
            <a href="' . $__templater->func('link', array('groups/cover', $__vars['group'], array('reposition' => 1, ), ), true) . '" class="menu-linkRow">' . 'Reposition cover' . '</a>
        ';
		}
		$__finalCompiled .= '
    ';
	}
	$__finalCompiled .= '

    ';
	if ($__templater->method($__vars['group'], 'canAddForum', array())) {
		$__finalCompiled .= '
        <a href="' . $__templater->func('link', array('groups/add-forum', $__vars['group'], ), true) . '"
           data-xf-click="overlay"
           class="menu-linkRow">' . 'Add forum' . '</a>
    ';
	}
	$__finalCompiled .= '

    ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
            ';
	if ($__templater->method($__vars['group'], 'canApproveUnapprove', array())) {
		$__compilerTemp1 .= '
                ';
		if ($__vars['group']['group_state'] == 'moderated') {
			$__compilerTemp1 .= '
                    <a href="' . $__templater->func('link', array('groups/toggle-approve', $__vars['group'], ), true) . '"
                       class="menu-linkRow">' . 'Approve group' . '</a>
                    ';
		} else if ($__templater->method($__vars['group'], 'isVisible', array())) {
			$__compilerTemp1 .= '
                    <a href="' . $__templater->func('link', array('groups/toggle-approve', $__vars['group'], ), true) . '"
                       class="menu-linkRow">' . 'Unapprove group' . '</a>
                ';
		}
		$__compilerTemp1 .= '
            ';
	}
	$__compilerTemp1 .= '

            ';
	if ($__templater->method($__vars['group'], 'canEdit', array())) {
		$__compilerTemp1 .= '
                <a href="' . $__templater->func('link', array('groups/edit', $__vars['group'], ), true) . '"
                   class="menu-linkRow">' . 'Edit group' . '</a>
            ';
	}
	$__compilerTemp1 .= '

            ';
	if ($__templater->method($__vars['group'], 'canMove', array())) {
		$__compilerTemp1 .= '
                <a href="' . $__templater->func('link', array('groups/move', $__vars['group'], ), true) . '" data-xf-click="overlay"
                   class="menu-linkRow">' . 'Move group' . '</a>
            ';
	}
	$__compilerTemp1 .= '

            ';
	if ($__templater->method($__vars['group'], 'canDelete', array('soft', ))) {
		$__compilerTemp1 .= '
                <a href="' . $__templater->func('link', array('groups/delete', $__vars['group'], ), true) . '"
                   class="menu-linkRow" data-xf-click="overlay">' . 'Delete group' . '</a>
            ';
	}
	$__compilerTemp1 .= '

            ';
	if ($__templater->method($__vars['group'], 'canUndelete', array())) {
		$__compilerTemp1 .= '
                <a href="' . $__templater->func('link', array('groups/undelete', $__vars['group'], ), true) . '"
                   data-xf-click="overlay"
                   class="menu-linkRow">' . 'Undelete group' . '</a>
            ';
	}
	$__compilerTemp1 .= '

            ';
	if ($__templater->method($__vars['group'], 'canFeatureUnfeature', array())) {
		$__compilerTemp1 .= '
                <a href="' . $__templater->func('link', array('groups/feature', $__vars['group'], ), true) . '"
                   class="menu-linkRow" data-xf-click="overlay">' . 'Feature group' . '</a>
                ';
		if ($__templater->method($__vars['group'], 'isFeatured', array())) {
			$__compilerTemp1 .= '
                    <a href="' . $__templater->func('link', array('groups/unfeature', $__vars['group'], ), true) . '"
                       class="menu-linkRow">' . 'Unfeature group' . '</a>
                ';
		}
		$__compilerTemp1 .= '
            ';
	}
	$__compilerTemp1 .= '

            ';
	if ($__templater->method($__vars['group'], 'canReassign', array())) {
		$__compilerTemp1 .= '
                <a href="' . $__templater->func('link', array('groups/reassign', $__vars['group'], ), true) . '"
                   class="menu-linkRow" data-xf-click="overlay">' . 'Reassign group' . '</a>
            ';
	}
	$__compilerTemp1 .= '

            ';
	if ($__templater->method($__vars['group'], 'canMerge', array())) {
		$__compilerTemp1 .= '
                <a href="' . $__templater->func('link', array('groups/merge', $__vars['group'], ), true) . '"
                   class="menu-linkRow" data-xf-click="overlay">' . 'Merge group' . '</a>
            ';
	}
	$__compilerTemp1 .= '

            ' . '
        ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
        <hr class="menu-separator" />
        ' . $__compilerTemp1 . '
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'join_button' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'hiddenLeave' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__vars['group']['Member']) {
		$__finalCompiled .= '
        ';
		if ((!$__vars['hiddenLeave']) AND (!$__templater->method($__vars['group']['Member'], 'isInvited', array()))) {
			$__finalCompiled .= '
            ' . $__templater->button('
                ' . $__templater->fontAwesome('fa-sign-out', array(
			)) . '
                ' . ($__templater->method($__vars['group']['Member'], 'isValidMember', array()) ? 'Leave group' : 'Cancel requesting') . '
            ', array(
				'href' => $__templater->func('link', array('group-members/leave', $__vars['group']['Member'], ), false),
				'class' => 'button--' . ($__templater->method($__vars['group']['Member'], 'isValidMember', array()) ? 'link' : 'cta') . ' button--groupJoin',
				'overlay' => 'true',
			), '', array(
			)) . '
        ';
		}
		$__finalCompiled .= '
    ';
	} else if ($__templater->method($__vars['group'], 'canJoin', array())) {
		$__finalCompiled .= '
        ' . $__templater->button('
            ' . $__templater->fontAwesome('fa-sign-in', array(
		)) . '
            ' . 'Join group' . '
        ', array(
			'href' => $__templater->func('link', array('groups/join', $__vars['group'], ), false),
			'overlay' => 'true',
			'class' => 'button--cta button--groupJoin',
		), '', array(
		)) . '
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'member_view_tabs_heading' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('tl_groups', 'view', ))) {
		$__finalCompiled .= '
        <a href="' . $__templater->func('link', array('groups/browse/user', null, array('user_id' => $__vars['user']['user_id'], ), ), true) . '"
           rel="nofollow" class="tabs-tab" id="tlg-groups"
           role="tab">' . 'Groups' . '</a>
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'member_view_tabs_content' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'user' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->method($__vars['xf']['visitor'], 'hasPermission', array('tl_groups', 'view', ))) {
		$__finalCompiled .= '
    <li data-href="' . $__templater->func('link', array('groups/browse/user', null, array('user_id' => $__vars['user']['user_id'], 'ref' => 'profile', ), ), true) . '" role="tabpanel"
        aria-labelledby="tlg-groups">
        <div class="blockMessage">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
    </li>
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'structured_data' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__vars['fpSnippet'] = $__templater->func('snippet', array($__vars['group']['description'], 0, array('stripBbCode' => true, ), ), false);
	$__finalCompiled .= '

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CreativeWork",
            "@id": "' . $__templater->filter($__templater->func('link', array('canonical:groups', $__vars['group'], ), false), array(array('escape', array('json', )),), true) . '",
            "url": "' . $__templater->filter($__templater->func('link', array('canonical:groups', $__vars['group'], ), false), array(array('escape', array('json', )),), true) . '",
            "name": "' . $__templater->filter($__vars['group']['name'], array(array('escape', array('json', )),), true) . '",
            "about": "' . $__templater->filter($__vars['group']['short_description'], array(array('escape', array('json', )),), true) . '",
            "headline": "' . $__templater->filter($__vars['group']['short_description'], array(array('escape', array('json', )),), true) . '",
            "description": "' . $__templater->filter($__vars['fpSnippet'], array(array('escape', array('json', )),), true) . '",
            "creator": {
                "@type": "Person",
                "name": "' . $__templater->filter(($__vars['group']['User'] ? $__vars['group']['User']['username'] : $__vars['group']['owner_username']), array(array('escape', array('json', )),), true) . '"
            },
            ';
	if ($__vars['group']['AvatarAttachment']) {
		$__finalCompiled .= '"thumbnailUrl": "' . $__templater->filter($__templater->method($__vars['group'], 'getAvatarUrl', array(true, )), array(array('escape', array('json', )),), true) . '",';
	}
	$__finalCompiled .= '
            ';
	if ($__vars['group']['tags']) {
		$__finalCompiled .= '"keywords": "' . $__templater->filter($__vars['group']['tags'], array(array('pluck', array('tag', )),array('join', array(',', )),array('escape', array('json', )),), true) . '",';
	}
	$__finalCompiled .= '
            "datePublished": "' . $__templater->filter($__templater->func('date', array($__vars['group']['created_date'], 'c', ), false), array(array('escape', array('json', )),), true) . '",
            "dateModified": "' . $__templater->filter($__templater->func('date', array($__vars['group']['last_activity'], 'c', ), false), array(array('escape', array('json', )),), true) . '"
        }
    </script>
';
	return $__finalCompiled;
}
),
'user_info_badge' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '
    <div class="groupBadge' . ($__vars['group']['CoverAttachment'] ? ' has-cover' : '') . '" data-group-id="' . $__templater->escape($__vars['group']['group_id']) . '"
         style="' . ($__vars['group']['CoverAttachment'] ? (('background-image:url(' . $__templater->escape($__vars['group']['CoverAttachment']['thumbnail_url'])) . ')') : '') . '">
        ';
	if ($__vars['group']['CoverAttachment']) {
		$__finalCompiled .= '
            <div class="groupBadge-overlay"></div>
        ';
	}
	$__finalCompiled .= '
        ' . $__templater->callMacro(null, 'avatar_html', array(
		'group' => $__vars['group'],
	), $__vars) . '
        <a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '" class="groupBadge-name"
           data-xf-init="preview-tooltip"
           data-preview-url="' . $__templater->func('link', array('groups/preview', $__vars['group'], ), true) . '">' . $__templater->escape($__vars['group']['name']) . '</a>
    </div>
';
	return $__finalCompiled;
}
),
'share_qr_code' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'vendor/qrcode/jquery-qrcode.min.js',
	));
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_style.less');
	$__finalCompiled .= '
    <div class="block group-qrcode--block">
        <div class="block-container">
            <div class="block-row">
                <div class="tlg-group--qrcode"></div>
            </div>
        </div>
    </div>
    ';
	$__templater->inlineJs('
        $(\'.tlg-group--qrcode\').qrcode({
            text: \'' . $__templater->filter($__templater->func('link', array('canonical:groups', $__vars['group'], ), false), array(array('escape', array()),), false) . '\'
        })
    ');
	$__finalCompiled .= '
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

' . '

' . '

' . '

' . '

' . '

' . '

' . '

' . '

' . '

' . '

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);