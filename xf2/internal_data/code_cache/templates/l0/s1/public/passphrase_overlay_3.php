<?php
// FROM HASH: d7c96714247e1ac094354c7f2ea178a8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="overlay-container is-active" id="overlayer" style="overflow-y: scroll !important;">
   <div class="overlay" tabindex="-1" role="dialog" aria-hidden="false">
<div class="overlay-title">
	' . 'Required Passphrase' . '
 </div>
' . $__templater->form('
    <div class="block-container">
		<div class="block-body">
			
			' . $__templater->formRow('Passphrase3 is a like a password to protect Account.', array(
		'label' => 'Message',
	)) . '
			 	' . $__templater->formTextAreaRow(array(
		'name' => 'public_key',
		'rows' => '5',
		'autosize' => 'true',
		'required' => 'required',
		'style' => 'font-size:10px;',
	), array(
		'label' => 'Public Key',
		'hint' => 'Required',
	)) . '
					' . $__templater->formTextBoxRow(array(
		'name' => 'passphrase_3',
		'autosize' => 'true',
	), array(
		'label' => 'PassPhrase3',
		'explain' => 'Passphrase3 is a like a password to protect Account.',
	)) . '
				' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
						</div>
			</div>		
', array(
		'action' => $__templater->func('link', array('register/login-pass-last', ), false),
		'ajax' => 'true',
		'class' => 'block',
	)) . '
           </div>
</div>';
	return $__finalCompiled;
}
);