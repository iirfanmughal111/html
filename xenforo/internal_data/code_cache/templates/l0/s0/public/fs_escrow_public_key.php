<?php
// FROM HASH: c428ea4f1e24109fdb613b94222ff619
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Public Key required');
	$__finalCompiled .= '

' . $__templater->form('
    <div class="block-container">
		<div class="block-body">
				<input type="hidden" name="withdraw_amount" value="' . $__templater->escape($__vars['data']['withdraw_amount']) . '" />
				<input type="hidden" name="destination_address" value="' . $__templater->escape($__vars['data']['destination_address']) . '" />
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
				' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
	), array(
	)) . '
	
		</div>
	</div>		
', array(
		'action' => $__templater->func('link', array('escrow/withdraw-verify', ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);