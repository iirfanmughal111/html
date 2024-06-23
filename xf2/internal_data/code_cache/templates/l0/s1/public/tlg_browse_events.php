<?php
// FROM HASH: 6e695dfe190e12122af6bdaaa54a0411
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Upcoming events');
	$__finalCompiled .= '

';
	$__templater->includeCss('tlg_event_style.less');
	$__finalCompiled .= '

<div class="block">
    <div class="block-container">
        <div class="block-body">
            ';
	$__compilerTemp1 = true;
	if ($__templater->isTraversable($__vars['events'])) {
		foreach ($__vars['events'] AS $__vars['event']) {
			$__compilerTemp1 = false;
			$__finalCompiled .= '
                <div class="block-row">
                    ' . $__templater->callMacro('tlg_group_events', 'event_item', array(
				'event' => $__vars['event'],
				'showGroup' => true,
			), $__vars) . '
                </div>
            ';
		}
	}
	if ($__compilerTemp1) {
		$__finalCompiled .= '
                <div class="blockMessage">' . 'There are no events to display.' . '</div>
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>

    <div class="block-outer">
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'groups/browse/events',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
    </div>
</div>';
	return $__finalCompiled;
}
);