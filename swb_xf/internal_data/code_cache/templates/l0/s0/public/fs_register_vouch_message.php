<?php
// FROM HASH: bc9c9fe37d86bb3dbc153370d62b822e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm Action');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Are you sure?' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
				' . $__templater->formHiddenVal('user_id', $__vars['user_id'], array(
	)) . '
			' . $__templater->formHiddenVal('vouch', $__vars['vouch'], array(
	)) . '

		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
		' . '
		
	</div>
', array(
		'action' => $__templater->func('link', array('members/savevouch', $__vars['user'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);