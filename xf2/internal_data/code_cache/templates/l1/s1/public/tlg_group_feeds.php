<?php
// FROM HASH: eeb3cedfa9430ff520541d4f563b706b
return array(
'macros' => array('tab_posts' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'records' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    <div class="lbContainer"
         data-xf-init="lightbox"
         data-message-selector=".js-post"
         data-lb-id="feeds-' . $__templater->escape($__vars['xf']['visitor']['user_id']) . '"
         data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">
        ';
	$__compilerTemp1 = true;
	if ($__templater->isTraversable($__vars['records'])) {
		foreach ($__vars['records'] AS $__vars['post']) {
			$__compilerTemp1 = false;
			$__finalCompiled .= '
            ' . $__templater->callMacro('tlg_post_macros', 'post', array(
				'showGroup' => true,
				'post' => $__vars['post'],
				'showLoader' => true,
			), $__vars) . '
            ';
		}
	}
	if ($__compilerTemp1) {
		$__finalCompiled .= '
                <div class="block-row">' . 'There are no posts to display' . '</div>
        ';
	}
	$__finalCompiled .= '
    </div>
';
	return $__finalCompiled;
}
),
'tab_threads' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'records' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	if ($__templater->isTraversable($__vars['records'])) {
		foreach ($__vars['records'] AS $__vars['thread']) {
			$__finalCompiled .= '
        <div class="block-row block-row--separated ' . ($__templater->method($__vars['thread'], 'isIgnored', array()) ? 'is-ignored' : '') . '"
             data-author="' . ($__templater->escape($__vars['thread']['User']['username']) ?: $__templater->escape($__vars['thread']['username'])) . '">
            <div class="contentRow ' . ((!$__templater->method($__vars['thread'], 'isVisible', array())) ? 'is-deleted' : '') . '">
                <span class="contentRow-figure">
                    ' . $__templater->func('avatar', array($__vars['thread']['User'], 's', false, array(
				'defaultname' => $__vars['thread']['username'],
			))) . '
                </span>
                <div class="contentRow-main">
                    <h3 class="contentRow-title">
                        <a href="' . $__templater->func('link', array('threads', $__vars['thread'], ), true) . '">' . ($__templater->func('prefix', array('thread', $__vars['thread'], ), true) . $__templater->escape($__vars['thread']['title'])) . '</a>
                    </h3>

                    <div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['thread']['FirstPost']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

                    <div class="contentRow-minor contentRow-minor--hideLinks">
                        <ul class="listInline listInline--bullet">
                            <li>' . $__templater->func('username_link', array($__vars['thread']['User'], false, array(
				'defaultname' => $__vars['thread']['username'],
			))) . '</li>
                            <li>' . 'Thread' . '</li>
                            <li>' . $__templater->func('date_dynamic', array($__vars['thread']['post_date'], array(
			))) . '</li>
                            <li>' . 'Replies' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['thread']['reply_count'], array(array('number', array()),), true) . '</li>
                            <li>' . 'Forum' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('forums', $__vars['thread']['Forum'], ), true) . '">' . $__templater->escape($__vars['thread']['Forum']['title']) . '</a></li>
                        </ul>
                    </div>
                </div>
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
'tab_events' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'records' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->includeCss('tlg_event_style.less');
	$__finalCompiled .= '
    <ul class="listPlain">
        ';
	if ($__templater->isTraversable($__vars['records'])) {
		foreach ($__vars['records'] AS $__vars['event']) {
			$__finalCompiled .= '
            <div class="block-row eventItem-row">
                ' . $__templater->callMacro('tlg_group_events', 'event_item', array(
				'showGroup' => true,
				'event' => $__vars['event'],
			), $__vars) . '
            </div>
        ';
		}
	}
	$__finalCompiled .= '
    </ul>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Your feeds');
	$__finalCompiled .= '

<div class="block block--messages">
    <div class="block-outer">
        <h2 class="block-tabHeader tabs--standalone tabs hScroller">
            <span class="hScroller-scroll">
                <a href="' . $__templater->func('link', array('groups/browse/feeds', ), true) . '" class="tabs-tab' . (($__vars['tabSelected'] == 'posts') ? ' is-active' : '') . '"
                   role="tab" aria-controls="posts">' . 'Posts' . '</a>
                ';
	if ($__vars['supportThreads']) {
		$__finalCompiled .= '
                    <a href="' . $__templater->func('link', array('groups/browse/feeds', null, array('tab' => 'threads', ), ), true) . '"
                       class="tabs-tab' . (($__vars['tabSelected'] == 'threads') ? ' is-active' : '') . '" role="tab"
                       aria-controls="threads">' . 'Threads' . '</a>
                ';
	}
	$__finalCompiled .= '
                <a href="' . $__templater->func('link', array('groups/browse/feeds', null, array('tab' => 'events', ), ), true) . '"
                   class="tabs-tab' . (($__vars['tabSelected'] == 'events') ? ' is-active' : '') . '"
                   role="tab" aria-controls="events">' . 'Events' . '</a>
            </span>
        </h2>
    </div>

    <div class="block-container">
        <div class="block-body">
            ';
	if ($__vars['tabSelected'] == 'threads') {
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'tab_threads', array(
			'records' => $__vars['records'],
		), $__vars) . '
            ';
	} else if ($__vars['tabSelected'] == 'events') {
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'tab_events', array(
			'records' => $__vars['records'],
		), $__vars) . '
            ';
	} else {
		$__finalCompiled .= '
                ' . $__templater->callMacro(null, 'tab_posts', array(
			'records' => $__vars['records'],
		), $__vars) . '
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>

    <div class="block-outer block-outer--after">
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'groups/browse/feeds',
		'data' => '{\'tab\': $tabSelected}',
		'perPage' => $__vars['perPage'],
	))) . '
        ' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
    </div>
</div>

' . '

' . '

';
	return $__finalCompiled;
}
);