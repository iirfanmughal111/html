<?php
// FROM HASH: 7c9156e76afc55e361cbda5e16f2c269
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

	<div class="u-inputSpacer">
		' . $__templater->formCheckBox(array(
	), array(array(
		'name' => $__vars['inputName'] . '[create_coupon]',
		'selected' => $__vars['option']['option_value']['create_coupon'],
		'label' => 'Automatically apply a coupon that lasts' . $__vars['xf']['language']['ellipsis'],
		'_dependent' => array('
					<div class="inputGroup">
						' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[length_amount]',
		'value' => $__vars['option']['option_value']['length_amount'],
		'min' => '1',
		'max' => '255',
	)) . '
						<span class="inputGroup-splitter"></span>
						' . $__templater->formSelect(array(
		'name' => $__vars['inputName'] . '[length_unit]',
		'value' => $__vars['option']['option_value']['length_unit'],
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

					<div class="u-inputSpacer">
						<span class="inputGroup-text">' . 'and provides a discount of' . $__vars['xf']['language']['ellipsis'] . '</span>
					</div>

					<div class="u-inputSpacer">
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[coupon_percent]',
		'value' => $__vars['option']['option_value']['coupon_percent'],
		'min' => '1',
		'max' => '100',
		'step' => 'any',
	)) . '
							<span class="inputGroup-text">%</span>
						</div>
					</div>
				'),
		'_type' => 'option',
	))) . '
	</div>
', array(
		'hint' => $__templater->escape($__vars['hintHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);