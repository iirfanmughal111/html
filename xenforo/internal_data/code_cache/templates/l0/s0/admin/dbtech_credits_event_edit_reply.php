<?php
// FROM HASH: 24c62b0b4bd85e38efbfc7465e486604
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formTextBoxRow(array(
		'name' => 'settings[threadid]',
		'value' => $__vars['event']['settings']['threadid'],
	), array(
		'label' => 'Thread ID',
		'explain' => 'If you enter a thread ID here, this event will only trigger when posting in that thread.',
	));
	return $__finalCompiled;
}
);