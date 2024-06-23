<?php
// FROM HASH: bdd72a605fdbbc521346420f6b24496b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array(array(
		'value' => 'visitor',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Using visitor time zone' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	)
,array(
		'value' => 'system',
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Using system time zone' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__templater->method($__vars['xf']['app'], 'data', array('XF:TimeZone', )), 'getTimeZoneOptions', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['_id'] => $__vars['_label']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['_id'],
				'label' => $__templater->escape($__vars['_label']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => $__vars['inputName'],
		'value' => $__vars['option']['option_value'],
	), $__compilerTemp1, array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);