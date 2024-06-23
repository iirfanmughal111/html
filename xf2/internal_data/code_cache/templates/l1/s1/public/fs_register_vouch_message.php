<?php
// FROM HASH: 7885461e49e0686cbbc88344f05f0abf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('fs_register_vouch_message');
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'fs_register_r_u_sure' . '
			', array(
		'rowtype' => 'confirm',
	)) . '
					' . $__templater->formHiddenVal('user_id', $__vars['user_id'], array(
	)) . '

		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
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