<?php
// FROM HASH: 79e92f295bfadd4366122e71382c8867
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
	));
	return $__finalCompiled;
}
);