<?php
// FROM HASH: 65174e7c4bd854cfcb6a47ce9314b4a6
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
	    <input type="hidden" name="passphrase_3" value="' . $__templater->escape($__vars['passphrase_3']) . '" />
		
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('register/pgp-verify-last', ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);