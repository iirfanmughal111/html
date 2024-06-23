<?php
// FROM HASH: b9d8e685113f35813888ab6797a0ff2f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('

	' . $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[do_cleanup]',
		'value' => '1',
		'selected' => $__vars['option']['option_value']['do_cleanup'],
		'label' => $__templater->escape($__vars['option']['title']),
		'_type' => 'option',
	))) . '
	<div class="formRow-explain">' . $__templater->escape($__vars['explainHtml']) . '</div>

	<div class="u-inputSpacer">
		<div class="inputGroup">
			' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[inactive_length_amount]',
		'value' => $__vars['option']['option_value']['inactive_length_amount'],
		'min' => '1',
		'max' => '255',
	)) . '
			<span class="inputGroup-splitter"></span>
			' . $__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[inactive_length_unit]',
		'value' => $__vars['option']['option_value']['inactive_length_unit'],
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