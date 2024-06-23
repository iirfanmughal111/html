<?php
// FROM HASH: 74aab37a4f90bb75ac1b68b90d6fe34b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Require Encrypt Message');
	$__finalCompiled .= '
' . $__templater->form('
		  
    <div class="block-container">
		<div class="block-body">
			

	' . $__templater->formRow($__templater->escape($__vars['user']['username']), array(
		'label' => 'Hi',
	)) . '

	' . $__templater->formRow('<pre style="font-size:10px;">' . $__templater->escape($__vars['user']['encrypt_message']) . '</pre>', array(
		'label' => 'fs_encrypt_message',
	)) . '

      	' . $__templater->formTextBoxRow(array(
		'name' => 'message',
		'required' => 'true',
	), array(
		'label' => 'fs_encrypt_message',
	)) . '
	<input type="hidden" name="user_id" value="' . $__templater->escape($__vars['user']['user_id']) . '" />
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('register/verify-message', $__vars['user'], ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);