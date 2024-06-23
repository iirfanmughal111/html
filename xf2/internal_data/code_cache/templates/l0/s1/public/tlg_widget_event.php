<?php
// FROM HASH: 662d5d1af91cbeb90aab36e2881124d5
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__templater->test($__vars['events'], 'empty', array())) {
		$__finalCompiled .= '
    ';
		$__templater->includeCss('tlg_event_style.less');
		$__finalCompiled .= '

    <div class="block widget-events"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
        <div class="block-container">
            ';
		if ($__vars['title']) {
			$__finalCompiled .= '<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>';
		}
		$__finalCompiled .= '
            <ul class="block-body">
                ';
		if ($__templater->isTraversable($__vars['events'])) {
			foreach ($__vars['events'] AS $__vars['event']) {
				$__finalCompiled .= '
                    <li class="block-row">
                        ' . $__templater->callMacro('tlg_group_events', 'event_item', array(
					'event' => $__vars['event'],
				), $__vars) . '
                    </li>
                ';
			}
		}
		$__finalCompiled .= '
            </ul>
        </div>
    </div>
';
	}
	return $__finalCompiled;
}
);