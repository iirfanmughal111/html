<?php
// FROM HASH: d9678b59135283fae4810096ec3488f7
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

' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'options[display_avatar]',
		'checked' => $__vars['options']['display_avatar'],
		'label' => 'Display avatar',
		'hint' => 'When you have a lot of members displaying, it\'s esthetic to hidden the avatar.',
		'_type' => 'option',
	)), array(
	));
	return $__finalCompiled;
}
);