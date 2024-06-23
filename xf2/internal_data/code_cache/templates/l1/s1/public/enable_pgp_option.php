<?php
// FROM HASH: 51968399950b1fff3846a75f7355e2c6
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Require Encrypt Message');
	$__finalCompiled .= '
' . $__templater->form('
		  
    <div class="block-container">
		<div class="block-body">
			

	' . $__templater->formRow($__templater->escape($__vars['xf']['visitor']['username']), array(
		'label' => 'Hi',
	)) . '

	' . $__templater->formRow('<pre style="font-size:10px;">' . $__templater->escape($__vars['xf']['visitor']['encrypt_message']) . '</pre>', array(
		'label' => 'fs_encrypt_message',
	)) . '

      	' . $__templater->formTextBoxRow(array(
		'name' => 'message',
		'required' => 'true',
	), array(
		'label' => 'fs_encrypt_message',
	)) . '
	<input type="hidden" name="pgp_option" value="' . $__templater->escape($__vars['pgp_option']) . '" />
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('account/change-pgp', $__vars['null'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);