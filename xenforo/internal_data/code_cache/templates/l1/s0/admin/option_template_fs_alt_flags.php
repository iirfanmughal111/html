<?php
// FROM HASH: b722109e149cfcc5e09ab420cb0eeefc
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('
	
' . $__templater->formRadio(array(
		'name' => $__vars['inputName'] . '[en]',
		'value' => $__vars['option']['option_value']['en'],
	), array(array(
		'value' => '',
		'label' => 'Default flag (English)',
		'_type' => 'option',
	),
	array(
		'value' => 'usa',
		'label' => 'USA flag (English)',
		'_type' => 'option',
	),
	array(
		'value' => 'canada',
		'label' => 'Canada flag (English)',
		'_type' => 'option',
	))) . '
		

	
	
' . $__templater->formRadio(array(
		'name' => $__vars['inputName'] . '[es]',
		'value' => $__vars['option']['option_value']['es'],
	), array(array(
		'value' => '',
		'label' => 'Default flag (Spanish)',
		'_type' => 'option',
	),
	array(
		'value' => 'mexico',
		'label' => 'Mexico flag (Spanish)',
		'_type' => 'option',
	),
	array(
		'value' => 'argentina',
		'label' => 'Argentina flag (Spanish)',
		'_type' => 'option',
	),
	array(
		'value' => 'colombia',
		'label' => 'Colombia flag (Spanish)',
		'_type' => 'option',
	))) . '
	
', array(
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
		'rowclass' => $__vars['rowClass'],
	)) . '

	<hr class="formRowSep" />';
	return $__finalCompiled;
}
);