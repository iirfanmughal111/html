<?php
// FROM HASH: 32c2d1683134f35b58ceb2885c1b3ec4
return array(
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
                    ' . $__templater->button('List compact', array(
		'href' => $__templater->func('link', array('groups/events', $__vars['group'], array('view' => 'list', ), ), false),
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
	$__templater->includeCss('tlg_full_calendar.css');
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/Libs/fullCalendar/main.js',
		'min' => '1',
		'addon' => 'Truonglv/Groups',
	));
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/event.js',
		'min' => '1',
		'addon' => 'Truonglv/Groups',
	));
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
        <div class="block-row">
            <div data-xf-init="tlg-event--fullcalendar"
                 data-source="' . $__templater->func('link', array('group-events/calendar', null, array('group_id' => $__vars['group']['group_id'], ), ), true) . '"
                 data-button-today="' . $__templater->filter('Today', array(array('for_attr', array()),), true) . '"
                 data-button-month="' . $__templater->filter('Month', array(array('for_attr', array()),), true) . '"
                 data-button-week="' . $__templater->filter('Week', array(array('for_attr', array()),), true) . '"
                 data-button-day="' . $__templater->filter('Day', array(array('for_attr', array()),), true) . '"
                 data-button-list="' . $__templater->filter('List', array(array('for_attr', array()),), true) . '"
            ></div>
        </div>
    </div>
</div>';
	return $__finalCompiled;
}
);