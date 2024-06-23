<?php
// FROM HASH: cd06786e1ee613f55c01774a989cba2b
return array(
'macros' => array('date_time_input_row' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'name' => 'date_time',
		'label' => '',
		'explain' => '',
		'hint' => '',
		'timestamp' => $__vars['xf']['time'],
		'dateTimeArr' => array(),
		'readOnly' => false,
		'showSeconds' => true,
		'xfInit' => '',
		'allInputAttrsHtml' => '',
		'ymdInputAttrsHtml' => '',
		'hhInputAttrsHtml' => '',
		'mmInputAttrsHtml' => '',
		'ssInputAttrsHtml' => '',
		'tzInputAttrsHtml' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->formRow('
		' . $__templater->callMacro(null, 'date_time_input', array(
		'name' => $__vars['name'],
		'timestamp' => $__vars['timestamp'],
		'dateTimeArr' => $__vars['dateTimeArr'],
		'readOnly' => $__vars['readOnly'],
		'showSeconds' => $__vars['showSeconds'],
		'xfInit' => $__vars['xfInit'],
		'allInputAttrsHtml' => $__vars['allInputAttrsHtml'],
		'ymdInputAttrsHtml' => $__vars['ymdInputAttrsHtml'],
		'hhInputAttrsHtml' => $__vars['hhInputAttrsHtml'],
		'mmInputAttrsHtml' => $__vars['mmInputAttrsHtml'],
		'ssInputAttrsHtml' => $__vars['ssInputAttrsHtml'],
		'tzInputAttrsHtml' => $__vars['tzInputAttrsHtml'],
	), $__vars) . '
	', array(
		'label' => $__templater->escape($__vars['label']),
		'explain' => $__templater->escape($__vars['explain']),
		'hint' => $__templater->escape($__vars['hint']),
		'rowtype' => 'input',
	)) . '
';
	return $__finalCompiled;
}
),
'date_time_input' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'name' => 'date_time',
		'timestamp' => $__vars['xf']['time'],
		'dateTimeArr' => array(),
		'readOnly' => false,
		'showSeconds' => true,
		'xfInit' => '',
		'allInputAttrsHtml' => '',
		'ymdInputAttrsHtml' => '',
		'hhInputAttrsHtml' => '',
		'mmInputAttrsHtml' => '',
		'ssInputAttrsHtml' => '',
		'tzInputAttrsHtml' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('svStandardLib_helper_macros_date_time_input.less');
	$__finalCompiled .= '

	';
	$__vars['selectedYMD'] = ($__vars['dateTimeArr']['ymd'] ?: ($__vars['timestamp'] ? $__templater->func('date', array($__vars['timestamp'], 'picker', ), false) : ''));
	$__finalCompiled .= '
	';
	$__vars['selectedHour'] = ($__vars['dateTimeArr']['hh'] ?: ($__vars['timestamp'] ? $__templater->func('time', array($__vars['timestamp'], 'H', ), false) : 0));
	$__finalCompiled .= '
	';
	$__vars['selectedMinute'] = ($__vars['dateTimeArr']['mm'] ?: ($__vars['timestamp'] ? $__templater->func('time', array($__vars['timestamp'], 'i', ), false) : 0));
	$__finalCompiled .= '
	';
	$__vars['selectedSecond'] = ($__vars['dateTimeArr']['ss'] ?: ($__vars['timestamp'] ? $__templater->func('time', array($__vars['timestamp'], 's', ), false) : 0));
	$__finalCompiled .= '
	';
	$__vars['selectedTz'] = ($__vars['dateTimeArr']['tz'] ?: $__vars['xf']['visitor']['timezone']);
	$__finalCompiled .= '

	<div class="inputGroup-container inputGroup-containerSvStandardLibDateTime">
		<div class="inputGroup">
			' . $__templater->callMacro(null, 'date_time_wrapper', array(
		'name' => $__vars['name'] . '[ymd]',
		'weekStart' => $__vars['xf']['language']['week_start'],
		'readOnly' => $__vars['readOnly'],
		'attrsHtml' => ((((' value="' . $__vars['selectedYMD']) . '" ') . $__vars['allInputAttrsHtml']) . $__vars['ymdInputAttrsHtml']),
	), $__vars) . '

			<span class="inputGroup-text">
				' . 'at' . $__vars['xf']['language']['label_separator'] . '
			</span>

			<select class="input input--inline input--autoSize" name="' . $__templater->escape($__vars['name']) . '[hh]" ' . ($__vars['readOnly'] ? 'disabled="disabled"' : '') . ' ' . $__templater->filter($__vars['allInputAttrsHtml'], array(array('raw', array()),), true) . ' ' . $__templater->filter($__vars['hhInputAttrsHtml'], array(array('raw', array()),), true) . '>
				';
	$__compilerTemp1 = $__templater->func('range', array(0, 23, 1, ), false);
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['hour']) {
			$__finalCompiled .= '
					';
			$__vars['readableHour'] = $__templater->filter($__vars['hour'], array(array('pad', array('0', 2, )),), false);
			$__finalCompiled .= '
					<option value="' . $__templater->escape($__vars['hour']) . '" ' . (($__vars['readableHour'] === $__vars['selectedHour']) ? 'selected="selected"' : '') . '>' . $__templater->escape($__vars['readableHour']) . '</option>
				';
		}
	}
	$__finalCompiled .= '
			</select>

			<span class="inputGroup-text">:</span>

			<select class="input input--inline input--autoSize" name="' . $__templater->escape($__vars['name']) . '[mm]" ' . ($__vars['readOnly'] ? 'disabled="disabled"' : '') . ' ' . $__templater->filter($__vars['allInputAttrsHtml'], array(array('raw', array()),), true) . ' ' . $__templater->filter($__vars['mmInputAttrsHtml'], array(array('raw', array()),), true) . '>
				';
	$__compilerTemp2 = $__templater->func('range', array(0, 59, 1, ), false);
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['minute']) {
			$__finalCompiled .= '
					';
			$__vars['readableMinute'] = $__templater->filter($__vars['minute'], array(array('pad', array('0', 2, )),), false);
			$__finalCompiled .= '
					<option value="' . $__templater->escape($__vars['minute']) . '" ' . (($__vars['readableMinute'] === $__vars['selectedMinute']) ? 'selected="selected"' : '') . '>' . $__templater->escape($__vars['readableMinute']) . '</option>
				';
		}
	}
	$__finalCompiled .= '
			</select>

			';
	if ($__vars['showSeconds']) {
		$__finalCompiled .= '
			<span class="inputGroup-text">:</span>

			<select class="input input--inline input--autoSize" name="' . $__templater->escape($__vars['name']) . '[ss]" ' . ($__vars['readOnly'] ? 'disabled="disabled"' : '') . ' ' . $__templater->filter($__vars['allInputAttrsHtml'], array(array('raw', array()),), true) . '  ' . $__templater->filter($__vars['ssInputAttrsHtml'], array(array('raw', array()),), true) . '>
				';
		$__compilerTemp3 = $__templater->func('range', array(0, 59, 1, ), false);
		if ($__templater->isTraversable($__compilerTemp3)) {
			foreach ($__compilerTemp3 AS $__vars['second']) {
				$__finalCompiled .= '
					';
				$__vars['readableSecond'] = $__templater->filter($__vars['second'], array(array('pad', array('0', 2, )),), false);
				$__finalCompiled .= '
					<option value="' . $__templater->escape($__vars['second']) . '" ' . (($__vars['readableSecond'] === $__vars['selectedSecond']) ? 'selected="selected"' : '') . '>' . $__templater->escape($__vars['readableSecond']) . '</option>
				';
			}
		}
		$__finalCompiled .= '
			</select>
			';
	}
	$__finalCompiled .= '
		</div>

		<div class="inputGroup">
			<select class="input" name="' . $__templater->escape($__vars['name']) . '[tz]" ' . ($__vars['readOnly'] ? 'disabled="disabled"' : '') . ' ' . $__templater->filter($__vars['allInputAttrsHtml'], array(array('raw', array()),), true) . ' ' . $__templater->filter($__vars['tzInputAttrsHtml'], array(array('raw', array()),), true) . '>
				';
	$__compilerTemp4 = $__templater->method($__templater->method($__vars['xf']['app'], 'data', array('XF:TimeZone', )), 'getTimeZoneOptions', array());
	if ($__templater->isTraversable($__compilerTemp4)) {
		foreach ($__compilerTemp4 AS $__vars['value'] => $__vars['readableTz']) {
			$__finalCompiled .= '
					<option value="' . $__templater->escape($__vars['value']) . '"' . (($__vars['value'] === $__vars['selectedTz']) ? 'selected="selected"' : '') . '>' . $__templater->escape($__vars['readableTz']) . '</option>
				';
		}
	}
	$__finalCompiled .= '
			</select>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'date_time_wrapper' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'name' => '!',
		'weekStart' => '!',
		'readOnly' => false,
		'class' => '',
		'xfInit' => '',
		'attrsHtml' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['xf']['versionId'] >= 2020000) {
		$__finalCompiled .= '
		';
		$__templater->includeJs(array(
			'prod' => 'xf/date_input-compiled.js',
			'dev' => 'vendor/pikaday/pikaday.js, xf/date_input.js',
		));
		$__finalCompiled .= '
		';
		$__templater->includeCss('core_pikaday.less');
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	<div class="inputGroup inputGroup--date inputGroup--joined inputDate">
		<input type="text" class="input input--date ' . $__templater->escape($__vars['class']) . '" autocomplete="off" data-xf-init="date-input ' . $__templater->escape($__vars['xfInit']) . '"
			data-week-start="' . $__templater->escape($__vars['weekStart']) . '"
			' . ($__vars['readOnly'] ? 'readonly' : '') . '
			name="' . $__templater->escape($__vars['name']) . '"
			' . $__templater->filter($__vars['attrsHtml'], array(array('raw', array()),), true) . ' />
		<span class="inputGroup-text inputDate-icon js-dateTrigger"></span>
	</div>
';
	return $__finalCompiled;
}
),
'relative_timestamp_prerequisites' => array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'sv/vendor/moment/moment.js',
		'addon' => 'SV/StandardLib',
		'min' => '1',
	));
	$__finalCompiled .= '
	';
	$__templater->includeJs(array(
		'src' => 'sv/lib/relative_timestamp.js',
		'addon' => 'SV/StandardLib',
		'min' => '1',
	));
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'relative_timestamp' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'class' => '',
		'timeStr' => '!',
		'otherTimestamp' => '!',
		'triggerEvent' => null,
		'triggerEventOnSelector' => null,
		'maximumDateParts' => 0,
		'countUp' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->func('trim', array('
	' . $__templater->callMacro(null, 'relative_timestamp_prerequisites', array(), $__vars) . '
	
	<span ' . (!$__templater->test($__vars['class'], 'empty', array()) ? (('class="' . $__templater->filter($__vars['class'], array(array('for_attr', array()),), true)) . '" ') : '') . 'data-xf-init="sv-standard-lib--relative-timestamp"
		  data-count-up="' . ($__vars['countUp'] ? 'true' : 'false') . '"
		  data-timestamp="' . $__templater->escape($__vars['otherTimestamp']) . '"
		  data-date-format="' . $__templater->escape($__vars['xf']['language']['date_format']) . '"
		  data-time-format="' . $__templater->escape($__vars['xf']['language']['time_format']) . '"
		  data-maximum-date-parts="' . $__templater->escape($__vars['maximumDateParts']) . '"
		  ' . (!$__templater->test($__vars['triggerEvent'], 'empty', array()) ? ((('data-trigger-event="' . $__templater->filter($__vars['triggerEvent'], array(array('for_attr', array()),), true)) . '" ') . (!$__templater->test($__vars['triggerEventOnSelector'], 'empty', array()) ? ((' data-trigger-event-on-selector="' . $__templater->filter($__vars['triggerEventOnSelector'], array(array('for_attr', array()),), true)) . '" ') : '')) : '') . '>' . $__templater->func('trim', array('
		' . $__templater->escape($__vars['timeStr']) . '
	'), false) . '</span>
'), false);
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

';
	return $__finalCompiled;
}
);