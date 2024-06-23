<?php
// FROM HASH: a908ca1b0d9667f92b6243a352a9a522
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

';
	$__templater->breadcrumb($__templater->preEscaped($__templater->escape($__vars['title'])), $__vars['editLink'], array(
	));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['entity'][$__vars['stateKey']] == 'deleted') {
		$__compilerTemp1 .= '
				';
		$__compilerTemp2 = array(array(
			'value' => '0',
			'label' => 'Do nothing',
			'_type' => 'option',
		));
		if ($__vars['canHardDelete']) {
			$__compilerTemp2[] = array(
				'value' => '1',
				'label' => 'Permanently delete',
				'hint' => 'Selecting this option will permanently and irreversibly delete the item.',
				'_type' => 'option',
			);
		}
		$__compilerTemp2[] = array(
			'value' => '2',
			'label' => 'Undelete',
			'_type' => 'option',
		);
		$__compilerTemp1 .= $__templater->formRadioRow(array(
			'name' => 'hard_delete',
			'value' => '0',
		), $__compilerTemp2, array(
			'label' => 'Deletion type',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->callMacro(null, 'public:helper_action::delete_type', array(
			'canHardDelete' => $__vars['canHardDelete'],
		), $__vars) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__vars['includeAuthorAlert']) {
		$__compilerTemp3 .= '
				' . $__templater->callMacro(null, 'public:helper_action::author_alert', array(), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__compilerTemp1 . '
			
			' . $__compilerTemp3 . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__vars['deleteLink'],
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);