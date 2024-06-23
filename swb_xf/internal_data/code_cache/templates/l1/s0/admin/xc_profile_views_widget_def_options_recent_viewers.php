<?php
// FROM HASH: 21a9633fe78ac28423430a4319c2cbc4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<hr class="formRowSep" />

' . $__templater->formNumberBoxRow(array(
		'name' => 'options[limit]',
		'value' => $__vars['options']['limit'],
		'min' => '1',
	), array(
		'label' => 'Maximum entries',
	)) . '

' . $__templater->formRadioRow(array(
		'name' => 'options[display_user]',
		'value' => $__vars['options']['display_user'],
	), array(array(
		'value' => 'username',
		'label' => 'Username only',
		'_type' => 'option',
	),
	array(
		'value' => 'avatar',
		'label' => 'Avatar only',
		'_type' => 'option',
	),
	array(
		'value' => 'avataruser',
		'label' => 'Avatar and username',
		'_type' => 'option',
	)), array(
		'label' => 'How to display the users',
		'explain' => 'Display users name or avatar for users.',
	)) . '

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[display_number]',
		'checked' => $__vars['options']['display_number'],
		'label' => 'Display the number of profile views',
		'hint' => 'Allow displays the number of profile views.',
		'_type' => 'option',
	)), array(
	));
	return $__finalCompiled;
}
);