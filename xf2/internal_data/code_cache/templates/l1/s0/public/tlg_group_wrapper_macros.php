<?php
// FROM HASH: 6219e9475c152e142e36f5ba9828f0c3
return array(
'macros' => array('header' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'titleHtml' => null,
		'showMeta' => true,
		'metaHtml' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

    ';
	$__compilerTemp1 = '';
	if ($__vars['titleHtml'] !== null) {
		$__compilerTemp1 .= $__templater->filter($__vars['titleHtml'], array(array('raw', array()),), true);
	} else {
		$__compilerTemp1 .= $__templater->escape($__vars['group']['name']);
	}
	$__compilerTemp2 = '';
	if ($__vars['showMeta']) {
		$__compilerTemp2 .= '
                    <div class="p-description">
                        ';
		if ($__vars['metaHtml'] !== null) {
			$__compilerTemp2 .= $__templater->filter($__vars['metaHtml'], array(array('raw', array()),), true);
		} else {
			$__compilerTemp2 .= $__templater->escape($__vars['group']['short_description']);
		}
		$__compilerTemp2 .= '
                    </div>
                ';
	}
	$__templater->setPageParam('headerHtml', '
        <div class="contentRow contentRow--hideFigureNarrow">
            <div class="contentRow-main">
                <div class="p-title">
                    <h1 class="p-title-value">
                        ' . $__compilerTemp1 . '
                    </h1>
                </div>
                ' . $__compilerTemp2 . '
            </div>
        </div>
    ');
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'status' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                ';
	if ($__vars['group']['group_state'] == 'deleted') {
		$__compilerTemp1 .= '
                    <dd class="blockStatus-message blockStatus-message--deleted">
                        ' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['group']['DeletionLog'],
		), $__vars) . '
                    </dd>
                    ';
	} else if ($__vars['group']['group_state'] == 'moderated') {
		$__compilerTemp1 .= '
                    <dd class="blockStatus-message blockStatus-message--moderated">
                        ' . 'Awaiting approval before being displayed publicly.' . '
                    </dd>
                ';
	}
	$__compilerTemp1 .= '
            ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
        <dl class="blockStatus blockStatus--standalone">
            <dt>' . 'Status' . '</dt>
            ' . $__compilerTemp1 . '
        </dl>
    ';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'tabs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'selected' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block" data-selected="' . $__templater->escape($__vars['selected']) . '">
        <div class="block-container">
            <h3 class="block-minorHeader">
                <a href="' . $__templater->func('link', array('groups', $__vars['group'], ), true) . '">' . $__templater->escape($__vars['group']['name']) . '</a>
            </h3>

            <div class="block-footer">' . $__templater->callMacro('tlg_group_macros', 'privacy_html', array(
		'group' => $__vars['group'],
	), $__vars) . '</div>

            <div class="block-body">
                <ol class="groupViewNav listPlain">
                    ' . '
                    ';
	$__compilerTemp1 = $__templater->method($__vars['group'], 'getNavigationItems', array());
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['navId'] => $__vars['navItem']) {
			$__finalCompiled .= '
                        <li class="groupViewNav--item groupViewNav--' . $__templater->escape($__vars['navId']) . (($__vars['selected'] == $__vars['navId']) ? ' is-active' : '') . '">
                            <a href="' . $__templater->escape($__vars['navItem']['link']) . '">
                                <span class="groupViewNav--itemText">' . $__templater->escape($__vars['navItem']['title']) . '</span>
                                ';
			if ($__vars['navItem']['counter'] > 0) {
				$__finalCompiled .= '
                                    <span class="badge badge--highlighted">' . $__templater->escape($__vars['navItem']['counter']) . '</span>
                                ';
			}
			$__finalCompiled .= '
                            </a>
                        </li>
                    ';
		}
	}
	$__finalCompiled .= '
                    ' . '
                </ol>
            </div>
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'cover_header' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'isEditing' => false,
		'navSelected' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block">
        <div class="block-container groupCover-header ' . ($__templater->test($__vars['isEditing'], 'empty', array()) ? '' : 'groupCover-editor') . '">
            <div class="block-body">
                ' . $__templater->callMacro('tlg_group_macros', 'cover', array(
		'isRepositioning' => $__vars['isEditing'],
		'group' => $__vars['group'],
	), $__vars) . '
            </div>

            ';
	if (!$__templater->test($__vars['isEditing'], 'empty', array())) {
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'cover_editor_setup', array(
			'group' => $__vars['group'],
			'width' => $__vars['baseWidth'],
			'height' => $__vars['baseHeight'],
		), $__vars) . '
            ';
	} else {
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'cover_header_navigation', array(
			'group' => $__vars['group'],
			'selected' => $__vars['navSelected'],
		), $__vars) . '
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'cover_header_navigation' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'selected' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="groupHeader-navList h-dFlex">
        <div class="p-nav-scroller hScroller" data-xf-init="h-scroller" data-auto-scroll=".groupViewNav--item.is-active">
            <div class="hScroller-scroll">
                <ul class="p-nav-list js-offCanvasNavSource">
                    ';
	$__compilerTemp1 = $__templater->method($__vars['group'], 'getNavigationItems', array());
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['navId'] => $__vars['nav']) {
			$__finalCompiled .= '
                        <li class="groupViewNav--item groupViewNav--' . $__templater->escape($__vars['navId']) . (($__vars['selected'] == $__vars['navId']) ? ' is-active' : '') . '">
                            <a href="' . $__templater->escape($__vars['nav']['link']) . '">
                                ' . $__templater->escape($__vars['nav']['title']) . '
                                ';
			if ($__vars['nav']['counter']) {
				$__finalCompiled .= '
                                    <span class="badge badge--highlighted">' . $__templater->filter($__vars['nav']['counter'], array(array('number', array()),), true) . '</span>
                                ';
			}
			$__finalCompiled .= '
                            </a>
                        </li>
                    ';
		}
	}
	$__finalCompiled .= '
                </ul>
            </div>
        </div>

        ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                    ';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
                            ' . $__templater->callMacro('tlg_group_macros', 'join_button', array(
		'group' => $__vars['group'],
	), $__vars) . '
                        ';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '
                        ' . $__compilerTemp3 . '
                    ';
	}
	$__compilerTemp2 .= '

                    ';
	if ($__vars['group']['Member'] AND $__templater->method($__vars['group']['Member'], 'canUpdateNotify', array())) {
		$__compilerTemp2 .= '
                        ' . $__templater->button('
                            ' . $__templater->fontAwesome('fa-bell' . (($__vars['group']['Member']['alert'] == 'off') ? '-slash' : ''), array(
		)) . '
                            ' . 'Notifications' . '
                        ', array(
			'href' => $__templater->func('link', array('group-members/notify', $__vars['group']['Member'], ), false),
			'class' => 'button--link button--groupAlerts',
			'overlay' => 'true',
		), '', array(
		)) . '
                    ';
	}
	$__compilerTemp2 .= '

                    ' . '

                    ';
	$__compilerTemp4 = '';
	$__compilerTemp4 .= '
                            ' . $__templater->callMacro('tlg_group_macros', 'settings', array(
		'group' => $__vars['group'],
		'advanced' => true,
	), $__vars) . '
                        ';
	if (strlen(trim($__compilerTemp4)) > 0) {
		$__compilerTemp2 .= '
                        ' . $__compilerTemp4 . '
                    ';
	}
	$__compilerTemp2 .= '
                ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
            <div class="buttonGroup groupHeader-navList--user h-dFlex">
                ' . $__compilerTemp2 . '
            </div>
        ';
	}
	$__finalCompiled .= '
    </div>
';
	return $__finalCompiled;
}
),
'cover_editor_setup' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'group' => '!',
		'width' => '!',
		'height' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/Libs/hammer/hammer.js',
		'min' => '1',
		'addon' => 'Truonglv/Groups',
	));
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/Libs/cropbox/jquery.cropbox.js',
		'addon' => 'Truonglv/Groups',
		'min' => '1',
	));
	$__finalCompiled .= '
    ';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/cover.js',
		'addon' => 'Truonglv/Groups',
		'min' => '1',
	));
	$__finalCompiled .= '

    <div class="block-footer" style="text-align: center">
        <div class="buttonGroup">
            ' . $__templater->button('Save', array(
		'data-xf-init' => 'tlg-cover-editor',
		'data-width' => $__vars['width'],
		'data-save' => $__templater->func('link', array('groups/cover', $__vars['group'], ), false),
		'data-guide-text' => $__templater->filter('Drag to reposition cover', array(array('for_attr', array()),), false),
		'data-height' => $__vars['height'],
		'data-frame' => '< .groupCover-header | .groupCoverFrame',
		'data-hammer-js' => $__templater->func('js_url', array('Truonglv/Groups/Libs/hammer/hammer.min.js', ), false),
		'icon' => 'save',
	), '', array(
	)) . '
            ' . $__templater->button('Cancel', array(
		'href' => $__templater->func('link', array('groups', $__vars['group'], ), false),
		'class' => 'button--link',
		'icon' => 'cancel',
	), '', array(
	)) . '
        </div>
    </div>
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

';
	return $__finalCompiled;
}
);