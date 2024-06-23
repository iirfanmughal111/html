<?php
// FROM HASH: 55ecd7877e084636d2c3c353026bb193
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Deposit amount' . ' ');
	$__finalCompiled .= '
';
	$__templater->wrapTemplate('account_wrapper', $__vars);
	$__finalCompiled .= '

';
	$__templater->includeCss('share_controls.less');
	$__vars['user'] = $__vars['xf']['visitor']->{'user_id'};
	$__finalCompiled .= $__templater->form('
		' . '' . '

  <div class="block-container">
    <div class="block-body">
		' . $__templater->formRow('
			<div class="shareInput" data-xf-init="share-input" data-success-text="' . $__templater->escape($__vars['successText']) . '">
		<div class="inputGroup inputGroup--joined">
			<div class="shareInput-button inputGroup-text js-shareButton is-hidden"
				data-xf-init="tooltip" title="' . $__templater->filter('Copy to clipboard', array(array('for_attr', array()),), true) . '">

				<i aria-hidden="true"></i>
			</div>
			' . $__templater->formTextBox(array(
		'class' => 'shareInput-input js-shareInput',
		'value' => $__vars['address'],
		'readonly' => 'true',
	)) . '
		</div>
	</div>
			' . '' . '
			<img src="' . $__templater->func('base_url', array(('data/qrcode/' . $__vars['user']) . '.png', ), true) . '"/>
		', array(
		'label' => 'Address',
		'rowtype' => 'input',
	)) . '
			
	  </div>
	 
	      ' . $__templater->formNumberBoxRow(array(
		'name' => 'deposit_amount',
		'min' => '0',
	), array(
		'explain' => 'Current Balance:' . ' ' . '$' . ($__templater->method($__vars['xf']['visitor'], 'getOrignolAmount', array()) ? $__templater->escape($__templater->method($__vars['xf']['visitor'], 'getOrignolAmount', array())) : 0),
		'label' => 'Amount',
	)) . '
 ' . '
    ' . $__templater->formSubmitRow(array(
		'submit' => '',
		'icon' => 'save',
	), array(
	)) . '
  </div>
', array(
		'action' => $__templater->func('link', array('escrow/deposit-save', ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-force-flash-message' => 'true',
	));
	return $__finalCompiled;
}
);