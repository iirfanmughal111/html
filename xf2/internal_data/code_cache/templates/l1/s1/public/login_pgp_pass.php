<?php
// FROM HASH: 2be13fc4e161b2d7da7edd66ef6008e9
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
	    <input type="hidden" name="publicKey" value="' . $__templater->escape($__vars['publicKey']) . '" />
	    <input type="hidden" name="passphrase_2" value="' . $__templater->escape($__vars['passphrase_2']) . '" />
			 <input type="hidden" name="passphrase_1" value="' . $__templater->escape($__vars['passphrase_1']) . '" />
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('register/pgp-verify', ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);