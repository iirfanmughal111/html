<?php
// FROM HASH: e095d365749eb55c5bb49e77e787dbd4
return array(
'macros' => array('general_custom_fields_edit' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'type' => '!',
		'group' => null,
		'set' => '!',
		'editMode' => 'user',
		'onlyInclude' => null,
		'additionalFilters' => array(),
		'rowType' => '',
		'rowClass' => '',
		'namePrefix' => 'custom_fields',
		'requiredOnly' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = $__templater->method($__vars['xf']['app'], 'getCustomFieldsForEdit', array($__vars['type'], $__vars['set'], $__vars['editMode'], $__vars['group'], $__vars['onlyInclude'], $__vars['additionalFilters'], ));
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['fieldId'] => $__vars['fieldDefinition']) {
			$__finalCompiled .= '

		';
			if ($__templater->method($__vars['fieldDefinition'], 'getExsitedField', array()) AND $__templater->method($__vars['fieldDefinition'], 'getAccountTypeField', array())) {
				$__finalCompiled .= '
		
		
		';
				if ((!$__vars['requiredOnly']) OR (($__vars['requiredOnly'] AND $__vars['fieldDefinition']['required']))) {
					$__finalCompiled .= '
			';
					$__vars['controlId'] = ($__templater->func('in_array', array($__vars['fieldDefinition']['field_type'], array('textbox', 'textarea', 'select', 'multiselect', 'date', 'stars', 'color', ), ), false) ? $__templater->func('unique_id', array(), false) : '');
					$__finalCompiled .= '
			';
					$__vars['labelId'] = ($__templater->func('in_array', array($__vars['fieldDefinition']['field_type'], array('radio', 'checkbox', ), ), false) ? $__templater->func('unique_id', array(), false) : '');
					$__finalCompiled .= '

			' . $__templater->formRow('

				' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit_' . $__vars['fieldDefinition']['field_type'], array(
						'set' => $__vars['set'],
						'definition' => $__vars['fieldDefinition'],
						'editMode' => $__vars['editMode'],
						'refId' => ($__vars['controlId'] ?: $__vars['labelId']),
						'namePrefix' => $__vars['namePrefix'],
					), $__vars) . '
			', array(
						'label' => $__templater->escape($__vars['fieldDefinition']['title']),
						'explain' => $__templater->escape($__vars['fieldDefinition']['description']),
						'hint' => ($__templater->method($__vars['fieldDefinition'], 'isRequired', array($__vars['editMode'], )) ? 'Required' : ''),
						'rowtype' => $__vars['rowType'] . ' customField ' . ($__templater->func('in_array', array($__vars['fieldDefinition']['field_type'], array('textbox', 'textarea', 'bbcode', 'select', ), ), false) ? 'input' : ''),
						'rowclass' => $__vars['rowClass'],
						'labelid' => $__vars['labelId'],
						'controlid' => $__vars['controlId'],
						'data-field' => $__vars['fieldDefinition']['field_id'],
					)) . '
		';
				}
				$__finalCompiled .= '
		';
			}
			$__finalCompiled .= '
	
			
			
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'custom_account_type_fields' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'type' => '!',
		'group' => null,
		'editMode' => 'user',
		'custom_fields' => '',
		'onlyInclude' => null,
		'additionalFilters' => array(),
		'rowType' => '',
		'rowClass' => '',
		'namePrefix' => 'custom_fields',
		'requiredOnly' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['custom_fields'])) {
		foreach ($__vars['custom_fields'] AS $__vars['fieldId'] => $__vars['fieldDefinition']) {
			$__finalCompiled .= '
		';
			if ((!$__vars['requiredOnly']) OR (($__vars['requiredOnly'] AND $__vars['fieldDefinition']['required']))) {
				$__finalCompiled .= '
			';
				$__vars['controlId'] = ($__templater->func('in_array', array($__vars['fieldDefinition']['field_type'], array('textbox', 'textarea', 'select', 'multiselect', 'date', 'stars', 'color', ), ), false) ? $__templater->func('unique_id', array(), false) : '');
				$__finalCompiled .= '
			';
				$__vars['labelId'] = ($__templater->func('in_array', array($__vars['fieldDefinition']['field_type'], array('radio', 'checkbox', ), ), false) ? $__templater->func('unique_id', array(), false) : '');
				$__finalCompiled .= '

			' . $__templater->formRow('
	
				' . $__templater->callMacro('custom_fields_macros', 'custom_fields_edit_' . $__vars['fieldDefinition']['field_type'], array(
					'set' => $__vars['set'],
					'definition' => $__vars['fieldDefinition'],
					'editMode' => $__vars['editMode'],
					'refId' => ($__vars['controlId'] ?: $__vars['labelId']),
					'namePrefix' => $__vars['namePrefix'],
				), $__vars) . '
			', array(
					'label' => $__templater->escape($__vars['fieldDefinition']['title']),
					'explain' => $__templater->escape($__vars['fieldDefinition']['description']),
					'hint' => ($__vars['fieldDefinition']['required'] ? 'Required' : ''),
					'rowtype' => $__vars['rowType'] . ' customField ' . ($__templater->func('in_array', array($__vars['fieldDefinition']['field_type'], array('textbox', 'textarea', 'bbcode', 'select', ), ), false) ? 'input' : ''),
					'rowclass' => $__vars['rowClass'],
					'labelid' => $__vars['labelId'],
					'controlid' => $__vars['controlId'],
					'data-field' => $__vars['fieldDefinition']['field_id'],
				)) . '
		';
			}
			$__finalCompiled .= '
	';
		}
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