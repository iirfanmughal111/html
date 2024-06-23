<?php
// FROM HASH: a7b6a901bfa0dee735640d93a6303e40
return array(
'macros' => array('time_period' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'params' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ' . $__templater->formRadioRow(array(
		'name' => 'expire_type',
		'value' => (($__vars['params']['expireDate'] > 0) ? 1 : 0),
	), array(array(
		'value' => '0',
		'label' => 'No limitation',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'label' => 'Expires after' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formDateInput(array(
		'name' => 'expire_date',
		'value' => ($__vars['params']['expireDate'] ? $__templater->func('date', array($__vars['params']['expireDate'], 'picker', ), false) : ''),
	))),
		'_type' => 'option',
	)), array(
		'label' => 'Time period',
		'explain' => 'If a special date provided, the content automatically be removed after that date.',
	)) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['formTitle']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['formType'] == 'timePeriod') {
		$__compilerTemp1 .= '
                ' . $__templater->callMacro(null, 'time_period', array(
			'params' => $__vars['params'],
		), $__vars) . '
            ';
	}
	$__compilerTemp2 = '';
	if ($__templater->isTraversable($__vars['hiddenInputs'])) {
		foreach ($__vars['hiddenInputs'] AS $__vars['inputName'] => $__vars['inputValue']) {
			$__compilerTemp2 .= '
            ' . $__templater->formHiddenVal($__vars['inputName'], $__vars['inputValue'], array(
			)) . '
        ';
		}
	}
	$__finalCompiled .= $__templater->form('
    <div class="block-container">
        <div class="block-body">
            ' . $__compilerTemp1 . '
        </div>

        ' . $__compilerTemp2 . '

        ' . $__templater->formSubmitRow(array(
		'submit' => $__templater->escape($__vars['submitTitle']),
	), array(
		'rowtype' => 'simple',
	)) . '
    </div>
', array(
		'action' => $__vars['formAction'],
		'class' => 'block',
		'ajax' => 'true',
	)) . '

';
	return $__finalCompiled;
}
);