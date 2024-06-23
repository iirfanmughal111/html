<?php
// FROM HASH: c8c457fb5874ef7fe0276de98251803a
return array(
'macros' => array('event_notices' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'event' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                    ';
	$__vars['guest'] = $__vars['event']['Guests'][$__vars['xf']['visitor']['user_id']];
	$__compilerTemp1 .= '
                    ';
	if (!$__templater->test($__vars['guest'], 'empty', array())) {
		$__compilerTemp1 .= '
                        ';
		if ($__vars['guest']['intend'] === 'going') {
			$__compilerTemp1 .= '
                            ' . 'You intend to participate in this event.' . '
                            ';
		} else if ($__vars['guest']['intend'] === 'maybe') {
			$__compilerTemp1 .= '
                            ' . 'You intend to maybe participate in this event.' . '
                            ';
		} else if ($__vars['guest']['intend'] === 'not_going') {
			$__compilerTemp1 .= '
                            ' . 'You are not participating in this event.' . '
                        ';
		}
		$__compilerTemp1 .= '
                        ';
		if ($__templater->method($__vars['event'], 'canIntend', array())) {
			$__compilerTemp1 .= '
                            ' . $__templater->button('Change', array(
				'href' => $__templater->func('link', array('group-events/intend', $__vars['event'], ), false),
				'class' => 'button--link',
				'overlay' => 'true',
			), '', array(
			)) . '
                        ';
		}
		$__compilerTemp1 .= '
                        ';
	} else if ($__templater->method($__vars['event'], 'canIntend', array())) {
		$__compilerTemp1 .= '
                        ' . 'You are not participating in this event.' . '
                        ' . $__templater->button('Change', array(
			'href' => $__templater->func('link', array('group-events/intend', $__vars['event'], ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
                    ';
	}
	$__compilerTemp1 .= '
                ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
        <div class="block-outer">
            <div class="blockMessage blockMessage--important blockMessage--eventNotice">
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
'event_actions' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'event' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->method($__vars['event'], 'canWatchUnwatch', array())) {
		$__finalCompiled .= '
        ' . $__templater->button($__templater->fontAwesome('fa-eye', array(
		)) . ' ' . ($__templater->method($__vars['event'], 'isWatched', array()) ? 'Unwatch' : 'Watch'), array(
			'href' => $__templater->func('link', array('group-events/watch', $__vars['event'], ), false),
			'class' => 'button--link',
		), '', array(
		)) . '
    ';
	}
	$__finalCompiled .= '
    ';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
                        ';
	if ($__templater->method($__vars['event'], 'canEdit', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-events/edit', $__vars['event'], ), true) . '"
                               class="menu-linkRow">' . 'Edit event' . '</a>
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__templater->method($__vars['event'], 'canCancel', array())) {
		$__compilerTemp1 .= '
                            ';
		if ($__templater->method($__vars['event'], 'isCancelled', array())) {
			$__compilerTemp1 .= '
                                <a href="' . $__templater->func('link', array('group-events/uncancel', $__vars['event'], ), true) . '"
                                   class="menu-linkRow">' . 'Un-cancel event' . '</a>
                                ';
		} else {
			$__compilerTemp1 .= '
                                <a href="' . $__templater->func('link', array('group-events/cancel', $__vars['event'], ), true) . '" data-xf-click="overlay"
                                   class="menu-linkRow">' . 'Cancel event' . '</a>
                            ';
		}
		$__compilerTemp1 .= '
                        ';
	}
	$__compilerTemp1 .= '
                        ';
	if ($__templater->method($__vars['event'], 'canDelete', array())) {
		$__compilerTemp1 .= '
                            <a href="' . $__templater->func('link', array('group-events/delete', $__vars['event'], ), true) . '" data-xf-click="overlay"
                               class="menu-linkRow">' . 'Delete event' . '</a>
                        ';
	}
	$__compilerTemp1 .= '
                    ';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
        <div class="buttonGroup-buttonWrapper">
            <div class="menu eventItem--menuControls" data-menu="menu" aria-hidden="true">
                <div class="menu-content">
                    ' . $__compilerTemp1 . '
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
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'tab_information' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'event' => '!',
		'data' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="block block-eventDescription">
        <div class="block-container">
            <div class="block-body">
                ' . $__templater->callMacro('tlg_comment_macros', 'comment_root', array(
		'comment' => $__vars['data']['description'],
		'content' => $__vars['event'],
		'showUser' => false,
	), $__vars) . '
            </div>
        </div>
    </div>

    <div class="block">
        <div class="block-container">
            <div class="block-body">
                ';
	if ($__vars['event']['display_address']) {
		$__finalCompiled .= '
                    <p class="block-minorHeader">
                        ' . $__templater->fontAwesome('fa-map-marker', array(
		)) . ' ' . 'Location' . '</p>
                    <div class="block-separator"></div>
                    <div class="block-row">
                        ' . $__templater->escape($__vars['event']['display_address']) . '
                        ';
		if ($__templater->method($__vars['event'], 'getEmbedMapUrl', array())) {
			$__finalCompiled .= '
                            <iframe width="100%" height="350" frameborder="0" style="border:0"
                                    src="' . $__templater->escape($__templater->method($__vars['event'], 'getEmbedMapUrl', array())) . '" allowfullscreen></iframe>
                        ';
		}
		$__finalCompiled .= '
                    </div>
                ';
	}
	$__finalCompiled .= '

                ';
	if ($__vars['xf']['options']['enableTagging'] AND ($__templater->method($__vars['event'], 'canEditTags', array()) OR $__vars['event']['tags'])) {
		$__finalCompiled .= '
                    <p class="block-minorHeader">' . $__templater->fontAwesome('fa-tags', array(
		)) . ' ' . 'Tags' . '</p>
                    <div class="block-separator"></div>
                    <div class="block-row">
                        ';
		if ($__vars['event']['tags']) {
			$__finalCompiled .= '
                            ';
			if ($__templater->isTraversable($__vars['event']['tags'])) {
				foreach ($__vars['event']['tags'] AS $__vars['tag']) {
					$__finalCompiled .= '
                                <a href="' . $__templater->func('link', array('tags', $__vars['tag'], ), true) . '" class="tagItem" dir="auto">' . $__templater->escape($__vars['tag']['tag']) . '</a>
                            ';
				}
			}
			$__finalCompiled .= '
                            ';
		} else {
			$__finalCompiled .= '
                            ' . 'None' . '
                        ';
		}
		$__finalCompiled .= '

                        ';
		if ($__templater->method($__vars['event'], 'canEditTags', array())) {
			$__finalCompiled .= '
                            <a href="' . $__templater->func('link', array('group-events/tags', $__vars['event'], ), true) . '" class="u-concealed" data-xf-click="overlay"
                               data-xf-init="tooltip" title="' . $__templater->filter('Edit tags', array(array('for_attr', array()),), true) . '">
                                ' . $__templater->fontAwesome('fa-pencil', array(
			)) . '
                                <span class="u-srOnly">' . 'Edit' . '</span>
                            </a>
                        ';
		}
		$__finalCompiled .= '
                    </div>
                ';
	}
	$__finalCompiled .= '

                <p class="block-minorHeader">' . $__templater->fontAwesome('fa-user-secret', array(
	)) . ' ' . 'Host' . '</p>
                <div class="block-separator"></div>
                <div class="block-row">' . $__templater->func('avatar', array($__vars['event']['User'], 's', false, array(
		'defaultname' => $__vars['event']['username'],
	))) . '</div>

                <p class="block-minorHeader">' . $__templater->fontAwesome('far fa-calendar', array(
	)) . ' ' . 'Event Date' . '</p>
                <div class="block-separator"></div>
                <div class="block-row">
                    <div class="eventItem-meta--date">
                        <dl class="pairs--inline pairs">
                            <dt>' . 'Event begin' . '</dt>
                            <dd>' . $__templater->escape($__templater->method($__vars['event'], 'getBeginDateOutput', array())) . '</dd>
                        </dl>
                    </div>

                    <div class="eventItem-meta--date">
                        <dl class="pairs--inline pairs">
                            <dt>' . 'Event end' . '</dt>
                            <dd>' . $__templater->escape($__templater->method($__vars['event'], 'getEndDateOutput', array())) . '</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
';
	return $__finalCompiled;
}
),
'tab_comments' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'event' => '!',
		'data' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__vars['afterHtml'] = $__templater->preEscaped('
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['data']['page'],
		'total' => $__vars['data']['totalComments'],
		'link' => 'group-events',
		'data' => $__vars['data']['event'],
		'params' => $__vars['data']['pageNavParams'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['data']['perPage'],
	))) . '
    ');
	$__finalCompiled .= '
    ' . $__templater->callMacro('tlg_comment_macros', 'comment_list', array(
		'afterHtml' => $__vars['afterHtml'],
		'comments' => $__vars['data']['comments'],
		'content' => $__vars['event'],
	), $__vars) . '

    ' . $__templater->callMacro('tlg_comment_macros', 'comment_form_block', array(
		'formAction' => $__templater->func('link', array('group-events/comment', $__vars['event'], ), false),
		'comments' => $__vars['data']['comments'],
		'content' => $__vars['event'],
		'attachmentData' => $__vars['data']['attachmentData'],
	), $__vars) . '
';
	return $__finalCompiled;
}
),
'tab_guests' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'event' => '!',
		'data' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->isTraversable($__vars['data']['guests'])) {
		foreach ($__vars['data']['guests'] AS $__vars['intend'] => $__vars['subData']) {
			$__finalCompiled .= '
        <div class="block">
            <div class="block-container">
                <h3 class="block-minorHeader">' . $__templater->escape($__vars['subData']['title']) . ' (' . $__templater->filter($__vars['subData']['total'], array(array('number', array()),), true) . ')</h3>
                <ul class="block-row listInline">
                    ';
			if ($__templater->isTraversable($__vars['subData']['users'])) {
				foreach ($__vars['subData']['users'] AS $__vars['user']) {
					$__finalCompiled .= '
                        ' . $__templater->func('avatar', array($__vars['user']['User'], 's', false, array(
						'defaultname' => 'Guest',
					))) . '
                    ';
				}
			}
			$__finalCompiled .= '
                </ul>
                ';
			if ($__vars['subData']['nextPage']) {
				$__finalCompiled .= '
                    <div class="block-footer">
                        <a href="' . $__templater->func('link', array('group-events', $__vars['event'], array('tab' => 'guests', 'intend' => $__vars['intend'], 'page' => $__vars['subData']['nextPage'], ), ), true) . '">' . 'More' . $__vars['xf']['language']['ellipsis'] . '</a>
                    </div>
                ';
			}
			$__finalCompiled .= '
            </div>
        </div>
    ';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'countdown_template' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'time' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="countdown-wrapper" data-xf-init="tlg-event-countdown"
         data-class-prefix=".countdown-tick--"
         data-time="' . $__templater->escape($__vars['time']) . '">
        <div class="countdown-tick countdown-tick--days">
            <div class="countdown-tick--wrapper">
                <span class="countdown-tick--number">' . $__templater->callback('Truonglv\\Groups\\Callback', 'getCountdownUnit', $__templater->escape($__vars['time']), array('unit' => 'days', )) . '</span>
                <span class="countdown-tick--text">' . 'Days' . '</span>
            </div>
        </div>
        <div class="countdown-tick countdown-tick--hours">
            <div class="countdown-tick--wrapper">
                <span class="countdown-tick--number">' . $__templater->callback('Truonglv\\Groups\\Callback', 'getCountdownUnit', $__templater->escape($__vars['time']), array('unit' => 'hours', )) . '</span>
                <span class="countdown-tick--text">' . 'Hours' . '</span>
            </div>
        </div>
        <div class="countdown-tick countdown-tick--minutes">
            <div class="countdown-tick--wrapper">
                <span class="countdown-tick--number">' . $__templater->callback('Truonglv\\Groups\\Callback', 'getCountdownUnit', $__templater->escape($__vars['time']), array('unit' => 'minutes', )) . '</span>
                <span class="countdown-tick--text">' . 'Minutes' . '</span>
            </div>
        </div>
        <div class="countdown-tick countdown-tick--seconds">
            <div class="countdown-tick--wrapper">
                <span class="countdown-tick--number">' . $__templater->callback('Truonglv\\Groups\\Callback', 'getCountdownUnit', $__templater->escape($__vars['time']), array('unit' => 'seconds', )) . '</span>
                <span class="countdown-tick--text">' . 'Seconds' . '</span>
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['event']['event_name']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('events');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	$__templater->includeCss('tlg_event_style.less');
	$__finalCompiled .= '
';
	$__templater->includeJs(array(
		'src' => 'Truonglv/Groups/event.js',
		'min' => '1',
		'addon' => 'Truonglv/Groups',
	));
	$__finalCompiled .= '

';
	$__vars['fpSnippet'] = $__templater->func('snippet', array($__vars['event']['FirstComment']['message'], 0, array('stripBbCode' => true, ), ), false);
	$__finalCompiled .= '

';
	$__templater->setPageParam('ldJsonHtml', '
    <script type="application/ld+json">' . $__templater->filter($__templater->method($__vars['event'], 'getSchemaStructuredData', array()), array(array('json', array()),array('raw', array()),), true) . '</script>
');
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title">
        <h2 class="p-title-value">' . $__templater->escape($__vars['event']['event_name']) . '</h2>
        ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                        ' . $__templater->callMacro(null, 'event_actions', array(
		'event' => $__vars['event'],
	), $__vars) . '
                    ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
            <div class="p-title-pageAction">
                <div class="buttonGroup">
                    ' . $__compilerTemp2 . '
                </div>
            </div>
        ';
	}
	$__finalCompiled .= '
    </div>
</div>

<div class="block">
    <div class="block-outer">
        <h2 class="block-tabHeader tabs--standalone tabs hScroller"
            data-xf-init="tabs h-scroller"
            data-panes=".js-eventTabPanes"
            data-state="replace"
            role="tablist" style="margin-bottom:10px">
			<span class="hScroller-scroll">
                <a href="' . $__templater->func('link', array('group-events', $__vars['event'], ), true) . '"
                           class="tabs-tab' . (($__vars['tabFilter'] === 'information') ? ' is-active' : '') . '">
                    ' . $__templater->fontAwesome('fa-info-circle', array(
	)) . ' ' . 'Information' . '</a>
                <a href="' . $__templater->func('link', array('group-events', $__vars['event'], array('tab' => 'comments', ), ), true) . '"
                   class="tabs-tab' . (($__vars['tabFilter'] === 'comments') ? ' is-active' : '') . '">
                    ' . $__templater->fontAwesome('fa-comments', array(
	)) . ' ' . 'Comments' . '</a>
                <a href="' . $__templater->func('link', array('group-events', $__vars['event'], array('tab' => 'guests', ), ), true) . '"
                   class="tabs-tab' . (($__vars['tabFilter'] === 'guests') ? ' is-active' : '') . '">
                    ' . $__templater->fontAwesome('fa-users', array(
	)) . ' ' . 'Guests' . '</a>
            </span>
        </h2>
    </div>

    ';
	if ($__templater->method($__vars['event'], 'isCancelled', array())) {
		$__finalCompiled .= '
        <div class="block-outer">
            <div class="blockMessage blockMessage--important">
                ' . 'Event is cancelled' . '
            </div>
        </div>
    ';
	} else {
		$__finalCompiled .= '
        ' . $__templater->callMacro(null, 'event_notices', array(
			'event' => $__vars['event'],
		), $__vars) . '
    ';
	}
	$__finalCompiled .= '

    <ul class="tabPanes js-eventTabPanes">
        <li class="is-active" role="tabpanel">
            ' . $__templater->callMacro(null, $__vars['macroName'], array(
		'event' => $__vars['event'],
		'data' => $__vars['macroParams'],
	), $__vars) . '
        </li>
    </ul>
</div>

';
	if (!$__templater->method($__vars['event'], 'isCancelled', array())) {
		$__finalCompiled .= '
    ';
		$__compilerTemp3 = '';
		$__compilerTemp3 .= '
                    ';
		if ($__vars['event']['begin_date'] > $__vars['xf']['time']) {
			$__compilerTemp3 .= '
                        ' . 'Event opening in' . $__vars['xf']['language']['label_separator'] . '
                    ';
		} else if ($__vars['event']['end_date'] > $__vars['xf']['time']) {
			$__compilerTemp3 .= '
                        ' . 'Event closing in' . $__vars['xf']['language']['label_separator'] . '
                    ';
		}
		$__compilerTemp3 .= '
                ';
		if (strlen(trim($__compilerTemp3)) > 0) {
			$__finalCompiled .= '
    <div class="block">
        <div class="block-container">
            <h3 class="block-minorHeader">
                ' . $__compilerTemp3 . '
            </h3>

            <div class="block-separator"></div>

            <div class="block-body">
                ';
			if ($__vars['event']['begin_date'] > $__vars['xf']['time']) {
				$__finalCompiled .= '
                    ' . $__templater->callMacro(null, 'countdown_template', array(
					'time' => $__vars['event']['begin_date'],
				), $__vars) . '
                ';
			} else if ($__vars['event']['end_date'] > $__vars['xf']['time']) {
				$__finalCompiled .= '
                    ' . $__templater->callMacro(null, 'countdown_template', array(
					'time' => $__vars['event']['end_date'],
				), $__vars) . '
                ';
			}
			$__finalCompiled .= '
            </div>
        </div>
    </div>
    ';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . '

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);