<?php
// FROM HASH: cbedfbe7406f54fc79922905064703a9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Require Encrypt Message');
	$__finalCompiled .= '
' . $__templater->form('
		  
    <div class="block-container">
		<div class="block-body">
		<input type="hidden" name="withdraw_amount" value="' . $__templater->escape($__vars['data']['withdraw_amount']) . '" />
		<input type="hidden" name="destination_address" value="' . $__templater->escape($__vars['data']['destination_address']) . '" />
		<input type="hidden" name="public_key" value="' . $__templater->escape($__vars['public_key']) . '" />
			
	' . $__templater->formRow($__templater->escape($__vars['data']['withdraw_amount']), array(
		'label' => 'Amount to Withdraw',
	)) . '
	' . $__templater->formRow('<pre style="font-size:10px;">' . $__templater->escape($__vars['xf']['visitor']['encrypt_message']) . '</pre>', array(
		'label' => 'Encrypt Message

',
	)) . '
      	' . $__templater->formTextBoxRow(array(
		'name' => 'message',
		'required' => 'true',
	), array(
		'label' => 'Decrypt Message',
	)) . '
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('escrow/withdraw-save', ), false),
		'class' => 'block',
		'ajax' => 'false',
	));
	return $__finalCompiled;
}
);