<?php
// FROM HASH: fcd9fb149ff6695a3848768e6c25aa2e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Withdraw Money' . ' ');
	$__finalCompiled .= '
';
	$__templater->wrapTemplate('account_wrapper', $__vars);
	$__finalCompiled .= '

' . $__templater->form('
  <div class="block-container">
    <div class="block-body">
		' . $__templater->formTextBoxRow(array(
		'value' => $__vars['source_address'],
		'readonly' => 'true',
	), array(
		'label' => 'User Address',
	)) . '
	    ' . $__templater->formTextBoxRow(array(
		'name' => 'destination_address',
	), array(
		'label' => 'Destination Address',
	)) . '
		' . $__templater->formNumberBoxRow(array(
		'min' => '0',
		'name' => 'withdraw_amount',
	), array(
		'explain' => 'Current Balance:' . ' ' . '$' . ($__templater->method($__vars['xf']['visitor'], 'getOrignolAmount', array()) ? $__templater->escape($__templater->method($__vars['xf']['visitor'], 'getOrignolAmount', array())) : 0),
		'label' => 'Amount',
	)) . '
    ' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
  </div>
	  </div>
	  
', array(
		'action' => $__templater->func('link', array('escrow/withdraw-request', ), false),
		'class' => 'block',
		'ajax' => 'false',
	));
	return $__finalCompiled;
}
);