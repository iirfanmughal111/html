<?php
// FROM HASH: efc97b7e025c60018c7f3a4db9148785
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('

	<div class="inputGroup inputGroup--numbers">
		' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[width]',
		'min' => '50',
		'value' => $__vars['option']['option_value']['width'],
		'placeholder' => 'Width',
	)) . '
		<span class="inputGroup-text">x</span>
		' . $__templater->formNumberBox(array(
		'name' => $__vars['inputName'] . '[height]',
		'min' => '50',
		'value' => $__vars['option']['option_value']['height'],
		'placeholder' => 'Height',
	)) . '
		<span class="inputGroup-text">' . 'Pixels' . '</span>
	</div>
', array(
		'rowtype' => 'input',
		'label' => $__templater->escape($__vars['option']['title']),
		'hint' => $__templater->escape($__vars['hintHtml']),
		'explain' => $__templater->escape($__vars['explainHtml']),
		'html' => $__templater->escape($__vars['listedHtml']),
	));
	return $__finalCompiled;
}
);