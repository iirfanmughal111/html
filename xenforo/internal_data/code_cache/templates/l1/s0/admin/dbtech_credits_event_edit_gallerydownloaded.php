<?php
// FROM HASH: 7e94b31075562a967b9ad848df009704
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'settings[extension_include]',
		'value' => $__vars['event']['settings']['extension_include'],
	), array(
		'label' => 'Included extensions',
		'explain' => 'If you wish for this event to only apply to certain file extensions, enter a comma-separated list.<br />
<b>Example:</b> <code>jpg,jpeg,png</code>',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'settings[extension_exclude]',
		'value' => $__vars['event']['settings']['extension_exclude'],
	), array(
		'label' => 'Excluded extensions',
		'explain' => 'If you wish for this event to <b>NOT</b> apply to certain file extensions, enter a comma-separated list.<br />
<b>Example:</b> <code>jpg,jpeg,png</code>',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'settings[apply_guest]',
		'value' => '1',
		'selected' => $__vars['event']['settings']['apply_guest'],
		'label' => 'Include guest event triggers',
		'_type' => 'option',
	)), array(
	));
	return $__finalCompiled;
}
);