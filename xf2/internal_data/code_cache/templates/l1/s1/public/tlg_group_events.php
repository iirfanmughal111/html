<?php
// FROM HASH: 7692f4ea6f3e55025fc406e6e84cbb0c
return array(
'macros' => array('event_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'event' => '!',
		'showGroup' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__vars['dayNames'] = array('0' => 'Sunday', '1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', );
	$__finalCompiled .= '

    <div class="contentRow">
        <div class="contentRow-figure">
            <div class="eventItem-date">
                <span class="eventItem-date--day">' . $__templater->func('date', array($__vars['event']['begin_date'], 'd/m', ), true) . '</span>
                <span class="eventItem-date--dayName u-muted">' . $__templater->escape($__vars['dayNames'][$__templater->func('date', array($__vars['event']['begin_date'], 'w', ), false)]) . '</span>
            </div>
        </div>

        <div class="contentRow-main">
            ';
	if ($__templater->method($__vars['event'], 'canIntend', array()) AND (!$__templater->method($__vars['event'], 'isIntended', array()))) {
		$__finalCompiled .= '
                <div class="contentRow-extra">
                    ' . $__templater->button('Register now', array(
			'href' => $__templater->func('link', array('group-events/intend', $__vars['event'], array('quick' => 1, ), ), false),
			'class' => 'button--link',
		), '', array(
		)) . '
                </div>
            ';
	}
	$__finalCompiled .= '
            <h3 class="contentRow-title">
                <a href="' . $__templater->func('link', array('group-events', $__vars['event'], ), true) . '">' . $__templater->escape($__vars['event']['event_name']) . '</a>
            </h3>

            <div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['event']['FirstComment']['message'], 300, array('stripQuote' => true, ), ), true) . '</div>

            <div class="contentRow-minor contentRow-minor--hideLinks">
                <ul class="listInline listInline--bullet">
                    <li>
                        ' . $__templater->func('username_link', array($__vars['event']['User'], false, array(
		'defaultname' => $__vars['event']['username'],
	))) . '
                    </li>
                    <li>
                        <span>' . '' . $__templater->escape($__vars['event']['attendee_count']) . ' attendee(s)' . '</span>
                    </li>
                    ';
	if ($__vars['event']['max_attendees'] > 0) {
		$__finalCompiled .= '
                        <li>
                            <span>' . 'Remaining ' . $__templater->escape($__templater->method($__vars['event'], 'getRemainingAttendeesSlots', array())) . ' slot(s)' . '</span>
                        </li>
                    ';
	}
	$__finalCompiled .= '
                    ';
	if ($__vars['showGroup']) {
		$__finalCompiled .= '
                        <li>
                            <span>' . 'In group' . $__vars['xf']['language']['label_separator'] . '</span>
                            <a href="' . $__templater->func('link', array('groups', $__vars['event']['Group'], ), true) . '" data-xf-init="preview"
                               data-preview-url="' . $__templater->func('link', array('groups/preview', $__vars['event']['Group'], ), true) . '">' . $__templater->escape($__vars['event']['Group']['name']) . '</a>
                        </li>
                    ';
	}
	$__finalCompiled .= '
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Events');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('events');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title">
        <h2 class="p-title-value">' . 'Events' . '</h2>
        ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                    ';
	if ($__templater->method($__vars['group'], 'canAddEvent', array())) {
		$__compilerTemp2 .= '
                        ' . $__templater->button('Add new event', array(
			'href' => $__templater->func('link', array('group-events/add', null, array('group_id' => $__vars['group']['group_id'], ), ), false),
			'icon' => 'write',
			'class' => 'button--link',
		), '', array(
		)) . '
                    ';
	}
	$__compilerTemp2 .= '
                    ' . $__templater->button('Calendar', array(
		'href' => $__templater->func('link', array('groups/events', $__vars['group'], array('view' => 'calendar', ), ), false),
		'class' => 'button--link',
	), '', array(
	)) . '
                ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
            <div class="p-title-pageAction">
                ' . $__compilerTemp2 . '
            </div>
        ';
	}
	$__finalCompiled .= '
    </div>
</div>

';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/event.js',
		'addon' => 'Truonglv/Groups',
		'min' => '1',
	));
	$__finalCompiled .= '
';
	$__templater->includeCss('tlg_event_style.less');
	$__finalCompiled .= '

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '"
     data-type="tl_group_event"
     data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
    <div class="block-outer">
        <h2 class="block-tabHeader tabs--standalone tabs hScroller"
            data-xf-init="tabs h-scroller"
            data-panes=".js-eventTabPanes"
            data-state="replace"
            role="tablist" style="margin-bottom: 10px">
			<span class="hScroller-scroll">
                <a href="' . $__templater->func('link', array('groups/events', $__vars['group'], array('filter' => 'ongoing', 'view' => 'list', ), ), true) . '"
                   role="tab"
                   class="tabs-tab' . (($__vars['tabFilter'] == 'ongoing') ? ' is-active' : '') . '">
                    ' . $__templater->fontAwesome('fa-truck', array(
	)) . ' ' . 'Ongoing' . '</a>
                <a href="' . $__templater->func('link', array('groups/events', $__vars['group'], array('filter' => 'upcoming', 'view' => 'list', ), ), true) . '"
                   role="tab"
                   class="tabs-tab' . (($__vars['tabFilter'] == 'upcoming') ? ' is-active' : '') . '">
                    ' . $__templater->fontAwesome('fa-calendar', array(
	)) . ' ' . 'Upcoming' . '</a>
                <a href="' . $__templater->func('link', array('groups/events', $__vars['group'], array('filter' => 'closed', 'view' => 'list', ), ), true) . '"
                   role="tab"
                   class="tabs-tab' . (($__vars['tabFilter'] == 'closed') ? ' is-active' : '') . '">
                    ' . $__templater->fontAwesome('fa-lock', array(
	)) . ' ' . 'Closed' . '</a>
            </span>
        </h2>
    </div>

    <ul class="tabPanes js-eventTabPanes">
        <li class="is-active" role="tabpanel">
            <div class="block-container">
                <div class="block-body">
                    ';
	$__compilerTemp3 = true;
	if ($__templater->isTraversable($__vars['events'])) {
		foreach ($__vars['events'] AS $__vars['event']) {
			$__compilerTemp3 = false;
			$__finalCompiled .= '
                        <div class="block-row">
                            ' . $__templater->callMacro(null, 'event_item', array(
				'event' => $__vars['event'],
			), $__vars) . '
                        </div>
                    ';
		}
	}
	if ($__compilerTemp3) {
		$__finalCompiled .= '
                        <div class="blockMessage">' . 'There are no events to display.' . '</div>
                    ';
	}
	$__finalCompiled .= '
                </div>
            </div>
        </li>
    </ul>

    <div class="block-outer block-outer--after">
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'linkdata' => $__vars['group'],
		'link' => 'groups/events',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
    </div>
</div>

' . '
';
	return $__finalCompiled;
}
);