<?php
// FROM HASH: a1e60f9963a8baaed75a2befef4ad150
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
		'label' => 'Latest comments limit',
		'explain' => 'Specify the maximum number of gallery comments that should be shown in this widget.',
	));
	return $__finalCompiled;
}
);