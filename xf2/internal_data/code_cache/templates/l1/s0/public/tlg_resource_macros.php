<?php
// FROM HASH: 413a0a8855a05a8063332645cf4dd267
return array(
'macros' => array('resource_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resources' => '!',
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="structItemContainer">
        ';
	if ($__templater->isTraversable($__vars['resources'])) {
		foreach ($__vars['resources'] AS $__vars['resource']) {
			$__finalCompiled .= '
            ' . $__templater->callMacro(null, 'resource_list_item', array(
				'resource' => $__vars['resource'],
				'group' => $__vars['group'],
			), $__vars) . '
        ';
		}
	}
	$__finalCompiled .= '
    </div>
';
	return $__finalCompiled;
}
),
'resource_actions' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
            ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                                ';
	if ($__templater->method($__vars['resource'], 'canEdit', array())) {
		$__compilerTemp2 .= '
                                    <a href="' . $__templater->func('link', array('group-resources/edit', $__vars['resource'], ), true) . '"
                                       class="menu-linkRow">' . 'Edit' . '</a>
                                    <a href="' . $__templater->func('link', array('group-resources/icon', $__vars['resource'], ), true) . '"
                                       class="menu-linkRow">' . 'Change resource icon' . '</a>
                                ';
	}
	$__compilerTemp2 .= '
                                ';
	if ($__templater->method($__vars['resource'], 'canDelete', array())) {
		$__compilerTemp2 .= '
                                    <a href="' . $__templater->func('link', array('group-resources/delete', $__vars['resource'], ), true) . '" data-xf-click="overlay"
                                       class="menu-linkRow">' . 'Delete' . '</a>
                                ';
	}
	$__compilerTemp2 .= '
                                ';
	if ($__templater->method($__vars['resource'], 'canViewDownloadLogs', array())) {
		$__compilerTemp2 .= '
                                    <a href="' . $__templater->func('link', array('group-resources/logs', $__vars['resource'], ), true) . '"
                                       class="menu-linkRow">' . 'Download logs' . '</a>
                                ';
	}
	$__compilerTemp2 .= '
                            ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
                <div class="buttonGroup-buttonWrapper">
                    <div class="menu eventItem--menuControls" data-menu="menu" aria-hidden="true">
                        <div class="menu-content">
                            ' . $__compilerTemp2 . '
                        </div>
                    </div>

                    ' . $__templater->button('&#8226;&#8226;&#8226;', array(
			'class' => 'button--link menuTrigger',
			'data-xf-click' => 'menu',
			'aria-expanded' => 'false',
			'aria-haspopup' => 'true',
			'data-menu' => '< .buttonGroup | .eventItem--menuControls',
			'title' => 'More options',
		), '', array(
		)) . '
                </div>
            ';
	}
	$__compilerTemp1 .= '

            ';
	if ($__templater->method($__vars['resource'], 'canDownload', array())) {
		$__compilerTemp1 .= '
                ' . $__templater->button('Download', array(
			'href' => $__templater->func('link', array('group-resources/download', $__vars['resource'], ), false),
			'icon' => 'download',
			'class' => 'button--link',
		), '', array(
		)) . '
            ';
	}
	$__compilerTemp1 .= '
        ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
    <div class="buttonGroup">
        ' . $__compilerTemp1 . '
    </div>
';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'resource_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'resource' => '!',
		'group' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '
    <div class="structItem structItem--resource"
         data-author="' . ($__templater->escape($__vars['resource']['User']['username']) ?: $__templater->escape($__vars['resource']['username'])) . '">
        <div class="structItem-cell structItem-cell--icon structItem-cell--iconExpanded">
            <div class="structItem-iconContainer">
                ';
	if ($__vars['resource']['display_icon_url']) {
		$__finalCompiled .= '
                    <a href="' . $__templater->func('link', array('group-resources', $__vars['resource'], ), true) . '" class="avatar avatar--s">
                        <img src="' . $__templater->escape($__vars['resource']['display_icon_url']) . '" alt="' . $__templater->escape($__vars['resource']['title']) . '" />
                    </a>
                ';
	} else {
		$__finalCompiled .= '
                    ' . $__templater->func('avatar', array($__vars['resource']['User'], 's', false, array(
			'defaultname' => $__vars['resource']['username'],
		))) . '
                ';
	}
	$__finalCompiled .= '
            </div>
        </div>
        <div class="structItem-cell structItem-cell--main" data-xf-init="touch-proxy">
            <div class="structItem-title">
                <a href="' . $__templater->func('link', array('group-resources', $__vars['resource'], ), true) . '" data-tp-primary="on">' . $__templater->escape($__vars['resource']['title']) . '</a>
            </div>

            <div class="structItem-minor">
                <ul class="structItem-parts">
                    <li>' . $__templater->func('username_link', array($__vars['resource']['User'], false, array(
		'defaultname' => $__vars['resource']['username'],
	))) . '</li>
                    <li class="structItem-startDate"><a href="' . $__templater->func('link', array('group-resources', $__vars['resource'], ), true) . '"
                                                        rel="nofollow">' . $__templater->func('date_dynamic', array($__vars['resource']['resource_date'], array(
	))) . '</a></li>
                    <li class="structItem-downloadCount">
                        ' . 'Downloads' . $__vars['xf']['language']['label_separator'] . '&nbsp;' . $__templater->filter($__vars['resource']['download_count'], array(array('number', array()),), true) . '
                    </li>
                </ul>
            </div>
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

';
	return $__finalCompiled;
}
);