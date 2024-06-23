<?php
// FROM HASH: 2cea3cc5f882d6b7b72aa812f9cb7d10
return array(
'macros' => array('delete_type' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'stateKey' => '!',
		'canHardDelete' => false,
		'typeName' => 'hard_delete',
		'reasonName' => 'reason',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['content'][$__vars['stateKey']] == 'deleted') {
		$__finalCompiled .= '
		
		';
		$__compilerTemp1 = array(array(
			'value' => '0',
			'label' => 'Keep deleted',
			'_type' => 'option',
		));
		if ($__vars['canHardDelete']) {
			$__compilerTemp1[] = array(
				'value' => '1',
				'label' => 'Permanently delete',
				'hint' => 'Selecting this option will permanently and irreversibly delete the item.',
				'_type' => 'option',
			);
		}
		$__compilerTemp1[] = array(
			'value' => '2',
			'label' => 'Undelete',
			'_type' => 'option',
		);
		$__finalCompiled .= $__templater->formRadioRow(array(
			'name' => $__vars['typeName'],
			'value' => '0',
		), $__compilerTemp1, array(
			'label' => 'Deletion type',
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->callMacro('public:helper_action', 'delete_type', array(
			'canHardDelete' => $__vars['canHardDelete'],
		), $__vars) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'customer_alert' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'selected' => false,
		'alertName' => 'customer_alert',
		'reasonName' => 'customer_alert_reason',
		'row' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['checkbox'] = $__templater->preEscaped('
		' . $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['alertName'],
		'selected' => $__vars['selected'],
		'label' => 'Notify customer of this action.' . ' ' . 'Reason' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formTextBox(array(
		'name' => $__vars['reasonName'],
		'placeholder' => 'Optional',
	))),
		'_type' => 'option',
	))) . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		' . $__templater->formRow('
			' . $__templater->filter($__vars['checkbox'], array(array('raw', array()),), true) . '
		', array(
		)) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['checkbox'], array(array('raw', array()),), true) . '
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