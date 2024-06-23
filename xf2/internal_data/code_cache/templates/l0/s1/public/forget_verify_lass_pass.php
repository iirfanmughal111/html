<?php
// FROM HASH: 1ed605fa852901db9c774c237ada89b2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Require Encrypt Message');
	$__finalCompiled .= '
' . $__templater->form('
		  
    <div class="block-container">
		<div class="block-body">
			

			' . $__templater->formRow('Passphrase3 is a like a password to protect Account.', array(
		'label' => 'Message',
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
		'action' => $__templater->func('link', array('lost-password/pass', $__vars['user'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);