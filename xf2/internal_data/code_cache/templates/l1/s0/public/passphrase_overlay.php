<?php
// FROM HASH: 3e7696f162ac143e8db6804909fdec29
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
			
			' . $__templater->formRow('Passphrase is a like a password to protect Account.
', array(
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
		'name' => 'passphrase_1',
		'autosize' => 'true',
	), array(
		'label' => 'PassPhrase1',
		'explain' => 'Passphrase is a like a password to protect Account.',
	)) . '
					' . $__templater->formTextBoxRow(array(
		'name' => 'passphrase_2',
		'autosize' => 'true',
	), array(
		'label' => 'PassPhrase2',
		'explain' => 'Passphrase2 is a like a password to protect Account.
',
	)) . '
				' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
						</div>
			</div>		
', array(
		'action' => $__templater->func('link', array('register/login-pass', ), false),
		'ajax' => 'true',
		'class' => 'block',
	)) . '
           </div>
</div>';
	return $__finalCompiled;
}
);