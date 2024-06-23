<?php
// FROM HASH: ef0b0dbf0531dfabf054acf2138c0b1c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('

	' . $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[send_reminder]',
		'value' => '1',
		'selected' => $__vars['option']['option_value']['send_reminder'],
		'label' => $__templater->escape($__vars['option']['title']),
		'_type' => 'option',
	))) . '
	<div class="formRow-explain">' . $__templater->escape($__vars['explainHtml']) . '</div>

	<div class="u-inputSpacer">
		<div class="inputGroup">
			' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[expiry_length_amount]',
		'value' => $__vars['option']['option_value']['expiry_length_amount'],
		'min' => '1',
		'max' => '255',
	)) . '
			<span class="inputGroup-splitter"></span>
			' . $__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[expiry_length_unit]',
		'value' => $__vars['option']['option_value']['expiry_length_unit'],
		'class' => 'input--inline',
	), array(array(
		'value' => 'day',
		'label' => 'Days',
		'_type' => 'option',
	),
	array(
		'value' => 'month',
		'label' => 'Months',
		'_type' => 'option',
	),
	array(
		'value' => 'year',
		'label' => 'Years',
		'_type' => 'option',
	))) . '
		</div>
	</div>
', array(
		'hint' => $__templater->escape($__vars['hintHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);