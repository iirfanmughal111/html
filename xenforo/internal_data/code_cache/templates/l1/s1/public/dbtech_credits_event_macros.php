<?php
// FROM HASH: 3aa5765f4b7e6640113bd50c4c78e9c7
return array(
'macros' => array('event_select' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'eventId' => '!',
		'row' => true,
		'class' => '',
		'events' => null,
		'includeBlank' => true,
		'includeAny' => false,
		'includeNone' => false,
		'inputName' => 'event_id',
		'phrase' => 'Event',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->test($__vars['events'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__vars['events'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\Credits:EventTrigger', )), 'getEventTitlePairs', array());
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp1 = array();
	if ($__vars['includeBlank']) {
		$__compilerTemp1[] = array(
			'value' => '',
			'_type' => 'option',
		);
	}
	if ($__vars['includeAny']) {
		$__compilerTemp1[] = array(
			'value' => '_any',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		);
	}
	if ($__vars['includeNone']) {
		$__compilerTemp1[] = array(
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		);
	}
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['events']);
	$__vars['select'] = $__templater->preEscaped('
		' . $__templater->formSelect(array(
		'name' => $__vars['inputName'],
		'value' => $__vars['eventId'],
		'class' => $__vars['class'],
	), $__compilerTemp1) . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		' . $__templater->formRow('

			' . $__templater->filter($__vars['select'], array(array('raw', array()),), true) . '
		', array(
			'rowtype' => 'input',
			'label' => $__templater->escape($__vars['phrase']),
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['select'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'event_trigger_select' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'eventTriggerId' => '!',
		'row' => true,
		'class' => '',
		'eventTriggers' => null,
		'includeBlank' => true,
		'includeAny' => false,
		'includeNone' => false,
		'inputName' => 'event_trigger_id',
		'phrase' => 'Event Trigger',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->test($__vars['eventTriggers'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__vars['eventTriggers'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\Credits:EventTrigger', )), 'getEventTriggerTitlePairs', array(true, true, ));
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp1 = array();
	if ($__vars['includeBlank']) {
		$__compilerTemp1[] = array(
			'value' => '',
			'_type' => 'option',
		);
	}
	if ($__vars['includeAny']) {
		$__compilerTemp1[] = array(
			'value' => '_any',
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		);
	}
	if ($__vars['includeNone']) {
		$__compilerTemp1[] = array(
			'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
			'_type' => 'option',
		);
	}
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['eventTriggers']);
	$__vars['select'] = $__templater->preEscaped('
		' . $__templater->formSelect(array(
		'name' => $__vars['inputName'],
		'value' => $__vars['eventTriggerId'],
		'class' => $__vars['class'],
	), $__compilerTemp1) . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		' . $__templater->formRow('

			' . $__templater->filter($__vars['select'], array(array('raw', array()),), true) . '
		', array(
			'rowtype' => 'input',
			'label' => $__templater->escape($__vars['phrase']),
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['select'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);