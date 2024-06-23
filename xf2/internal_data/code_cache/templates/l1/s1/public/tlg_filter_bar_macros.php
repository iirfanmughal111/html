<?php
// FROM HASH: 21d3f034a0f2961ee49b89fbbc45bd59
return array(
'macros' => array('group_list_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'baseLinkPath' => '!',
		'category' => null,
		'creatorFilter' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__vars['sortOrders'] = array('created_date' => 'Submission date', 'name' => 'Alphabetically', 'member_count' => 'Member count', 'view_count' => 'View count', 'event_count' => 'Event count', 'discussion_count' => 'Discussion count', 'last_activity' => 'Last activity', );
	$__finalCompiled .= '

    <div class="block-filterBar">
        <div class="filterBar">
            ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                        ' . '
                        ';
	if ($__vars['filters']['privacy']) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('privacy', null, )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Privacy' . $__vars['xf']['language']['label_separator'] . '</span>
                                ';
		if ($__vars['filters']['privacy'] == 'public') {
			$__compilerTemp1 .= '
                                    ' . 'Public Group' . '
                                ';
		} else if ($__vars['filters']['privacy'] == 'closed') {
			$__compilerTemp1 .= '
                                    ' . 'Closed Group' . '
                                ';
		} else if ($__vars['filters']['privacy'] == 'secret') {
			$__compilerTemp1 .= '
                                    ' . 'Secret Group' . '
                                ';
		}
		$__compilerTemp1 .= '
                            </a></li>
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__vars['filters']['creator_id'] AND $__vars['creatorFilter']) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('creator_id', null, )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Creator' . $__vars['xf']['language']['label_separator'] . '</span>
                                ' . $__templater->escape($__vars['creatorFilter']['username']) . '</a></li>
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
                                ' . $__templater->escape($__vars['sortOrders'][$__vars['filters']['order']]) . '
                                ' . $__templater->fontAwesome((($__vars['filters']['direction'] == 'asc') ? 'fa-angle-up' : 'fa-angle-down'), array(
		)) . '
                                <span class="u-srOnly">';
		if ($__vars['filters']['direction'] == 'asc') {
			$__compilerTemp1 .= 'Ascending';
		} else {
			$__compilerTemp1 .= 'Descending';
		}
		$__compilerTemp1 .= '</span>
                            </a></li>
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__vars['xf']['options']['tl_groups_enableLanguage'] AND $__vars['filters']['language_code']) {
		$__compilerTemp1 .= '
                            ';
		$__vars['languages'] = $__templater->method($__templater->method($__vars['xf']['app'], 'data', array('XF:Language', )), 'getLocaleList', array());
		$__compilerTemp1 .= '
                            ';
		if ($__vars['languages'][$__vars['filters']['language_code']]) {
			$__compilerTemp1 .= '
                                <li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array(array('language_code' => null, ), )),), false), ), true) . '"
                                       class="filterBar-filterToggle" data-xf-init="tooltip"
                                       title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
                                    <span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
                                    ' . $__templater->escape($__vars['languages'][$__vars['filters']['language_code']]) . '
                                </a></li>
                            ';
		}
		$__compilerTemp1 .= '
                        ';
	}
	$__compilerTemp1 .= '
                        ' . '
                    ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
                <ul class="filterBar-filters">
                    ' . $__compilerTemp1 . '
                </ul>
            ';
	}
	$__finalCompiled .= '

            <a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
            <div class="menu menu--wide" data-menu="menu" aria-hidden="true"
                 data-href="' . $__templater->func('link', array($__vars['baseLinkPath'] . '/filters', $__vars['category'], $__vars['filters'], ), true) . '"
                 data-load-target=".js-filterMenuBody">
                <div class="menu-content">
                    <h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
                    <div class="js-filterMenuBody">
                        <div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'member_list_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'group' => '!',
		'filterUser' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__vars['sortOrders'] = array('joined_date' => 'Join date', 'username' => 'Name', );
	$__finalCompiled .= '

    <div class="block-filterBar">
        <div class="filterBar">
            ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                        ' . '
                        ';
	if ($__vars['filters']['member_state']) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array('groups/members', $__vars['group'], $__templater->filter($__vars['filters'], array(array('replace', array('member_state', 'any', )),array('replace', array('group_id', null, )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Member status' . $__vars['xf']['language']['label_separator'] . '</span>
                                ';
		if ($__vars['filters']['member_state'] == 'valid') {
			$__compilerTemp1 .= '
                                    ' . 'Joined' . '
                                ';
		} else if ($__vars['filters']['member_state'] == 'invited') {
			$__compilerTemp1 .= '
                                    ' . 'Invited' . '
                                ';
		} else if ($__vars['filters']['member_state'] == 'banned') {
			$__compilerTemp1 .= '
                                    ' . 'Banned' . '
                                ';
		} else if ($__vars['filters']['member_state'] == 'moderated') {
			$__compilerTemp1 .= '
                                    ' . 'Moderated' . '
                                ';
		} else if ($__vars['filters']['member_state'] == 'any') {
			$__compilerTemp1 .= '
                                    ' . 'Any' . '
                                ';
		}
		$__compilerTemp1 .= '
                            </a></li>
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if ($__vars['filters']['user_id'] AND $__vars['filterUser']) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array('groups/members', $__vars['group'], $__templater->filter($__vars['filters'], array(array('replace', array('user_id', null, )),array('replace', array('group_id', null, )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Name' . $__vars['xf']['language']['label_separator'] . '</span>
                                ' . $__templater->escape($__vars['filterUser']['username']) . '</a></li>
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array('groups/members', $__vars['group'], $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),array('replace', array('group_id', null, )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
                                ' . $__templater->escape($__vars['sortOrders'][$__vars['filters']['order']]) . '
                                ' . $__templater->fontAwesome((($__vars['filters']['direction'] == 'asc') ? 'fa-angle-up' : 'fa-angle-down'), array(
		)) . '
                                <span class="u-srOnly">
                                    ';
		if ($__vars['filters']['direction'] == 'asc') {
			$__compilerTemp1 .= '
                                        ' . 'Ascending' . '
                                    ';
		} else {
			$__compilerTemp1 .= '
                                        ' . 'Descending';
		}
		$__compilerTemp1 .= '
                                </span>
                            </a></li>
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__vars['filters']['is_staff']) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array('groups/members', $__vars['group'], $__templater->filter($__vars['filters'], array(array('replace', array('is_staff', null, )),array('replace', array('group_id', null, )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Staff only' . $__vars['xf']['language']['label_separator'] . '</span>
                                ' . 'Yes' . '</a></li>
                        ';
	}
	$__compilerTemp1 .= '
                        ' . '
                    ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
                <ul class="filterBar-filters">
                    ' . $__compilerTemp1 . '
                </ul>
            ';
	}
	$__finalCompiled .= '

            <a class="filterBar-menuTrigger" data-xf-click="menu" role="button"
               tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
            <div class="menu menu--wide" data-menu="menu" aria-hidden="true"
                 data-href="' . $__templater->func('link', array('group-members/filters', null, $__vars['filters'], ), true) . '"
                 data-load-target=".js-filterMenuBody">
                <div class="menu-content">
                    <h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
                    <div class="js-filterMenuBody">
                        <div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'user_group_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__vars['sortOrders'] = array('created_date' => 'Submission date', 'name' => 'Alphabetically', 'member_count' => 'Member count', 'view_count' => 'View count', 'event_count' => 'Event count', 'discussion_count' => 'Discussion count', 'last_activity' => 'Last activity', );
	$__finalCompiled .= '
    <div class="block-filterBar">
        <div class="filterBar">
            ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                        ';
	if ($__vars['filters']['privacy']) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array('groups/browse/user', null, $__templater->filter($__vars['filters'], array(array('replace', array('privacy', null, )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Privacy' . $__vars['xf']['language']['label_separator'] . '</span>
                                ';
		if ($__vars['filters']['privacy'] == 'public') {
			$__compilerTemp1 .= '
                                    ' . 'Public Group' . '
                                    ';
		} else if ($__vars['filters']['privacy'] == 'closed') {
			$__compilerTemp1 .= '
                                    ' . 'Closed Group' . '
                                    ';
		} else if ($__vars['filters']['privacy'] == 'secret') {
			$__compilerTemp1 .= '
                                    ' . 'Secret Group' . '
                                ';
		}
		$__compilerTemp1 .= '
                            </a></li>
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if ($__vars['filters']['type']) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array('groups/browse/user', null, $__templater->filter($__vars['filters'], array(array('replace', array('type', null, )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Type' . $__vars['xf']['language']['label_separator'] . '</span>
                                ';
		if ($__vars['filters']['type'] == 'invited') {
			$__compilerTemp1 .= '
                                    ' . 'Invited groups' . '
                                    ';
		} else if ($__vars['filters']['type'] == 'admin') {
			$__compilerTemp1 .= '
                                    ' . 'Admin of groups' . '
                                ';
		}
		$__compilerTemp1 .= '
                            </a></li>
                        ';
	}
	$__compilerTemp1 .= '

                        ';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp1 .= '
                            <li><a href="' . $__templater->func('link', array('groups/browse/user', null, $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
                                   class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
                                <span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
                                ' . $__templater->escape($__vars['sortOrders'][$__vars['filters']['order']]) . '
                                ' . $__templater->fontAwesome((($__vars['filters']['direction'] == 'asc') ? 'fa-angle-up' : 'fa-angle-down'), array(
		)) . '
                                <span class="u-srOnly">';
		if ($__vars['filters']['direction'] == 'asc') {
			$__compilerTemp1 .= 'Ascending';
		} else {
			$__compilerTemp1 .= 'Descending';
		}
		$__compilerTemp1 .= '</span>
                            </a></li>
                        ';
	}
	$__compilerTemp1 .= '
                    ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
                <ul class="filterBar-filters">
                    ' . $__compilerTemp1 . '
                </ul>
            ';
	}
	$__finalCompiled .= '

            <a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0"
               aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
            <div class="menu menu--wide" data-menu="menu" aria-hidden="true"
                 data-href="' . $__templater->func('link', array('groups/browse/user/filters', null, $__vars['filters'], ), true) . '"
                 data-load-target=".js-filterMenuBody">
                <div class="menu-content">
                    <h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
                    <div class="js-filterMenuBody">
                        <div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
                    </div>
                </div>
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