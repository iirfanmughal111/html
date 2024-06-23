<?php
// FROM HASH: 83e35bffa2c70227c175d0210ef25c9f
return array(
'macros' => array('description' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'description' => '',
		'attachmentData' => array(),
		'previewUrl' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div data-xf-init="attachment-manager">
		' . $__templater->formEditorRow(array(
		'name' => 'description',
		'value' => $__vars['description'],
		'data-min-height' => '200',
		'attachments' => $__vars['attachmentData']['attachments'],
		'data-preview-url' => $__vars['previewUrl'],
	), array(
		'label' => 'Description',
	)) . '

		';
	$__compilerTemp1 = '';
	if ($__vars['attachmentData']) {
		$__compilerTemp1 .= '
				' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
			'attachmentData' => $__vars['attachmentData'],
		), $__vars) . '
			';
	}
	$__finalCompiled .= $__templater->formRow('
			' . $__compilerTemp1 . '
		', array(
	)) . '
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Start Escrow');
	$__finalCompiled .= '
' . $__templater->form('
	
	<div class="block-container">
		<div class="block-body">
	<!--		' . $__templater->formRow('
				<div class="inputChoices">
					' . $__templater->formRadio(array(
		'name' => 'currency_type',
	), array(array(
		'label' => 'currency_type_xmr',
		'value' => 'xmr',
		'_type' => 'option',
	),
	array(
		'label' => 'currency_type_btc',
		'value' => 'btc',
		'_type' => 'option',
	))) . '
				</div>
		', array(
		'label' => 'escrow_currency_type',
	)) . ' -->
			' . $__templater->formTextBoxRow(array(
		'name' => 'escrow_amount',
		'value' => '',
	), array(
		'label' => 'Escrow Title',
	)) . '
				
			
			' . $__templater->callMacro(null, 'description', array(
		'description' => $__vars['category']['draft_resource']['message'],
		'attachmentData' => $__vars['attachmentData'],
		'previewUrl' => $__templater->func('link', array('resources/categories/preview', $__vars['category'], ), false),
	), $__vars) . '
			' . $__templater->formRow('
					' . $__templater->callMacro('helper_attach_upload', 'upload_block', array(
		'attachmentData' => $__vars['attachmentData'],
	), $__vars) . '
				
			', array(
	)) . '
			
			' . $__templater->formNumberBoxRow(array(
		'name' => 'escrow_amount',
		'value' => '',
		'min' => '0',
	), array(
		'explain' => 'Total Amount:',
		'label' => 'Escrow Amount',
	)) . '
			
			' . $__templater->formTextBoxRow(array(
		'name' => 'starter',
		'value' => ($__vars['starterFilter'] ? $__vars['starterFilter']['username'] : ''),
		'ac' => 'single',
		'maxlength' => $__templater->func('max_length', array($__vars['xf']['visitor'], 'username', ), false),
		'id' => 'ctrl_started_by',
	), array(
		'label' => 'User',
	)) . '
			
	
	' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
		</div>
	</div>
', array(
		'action' => $__templater->func('link', array('auction/categories/bidding', $__vars['auction'], ), false),
		'ajax' => 'true',
		'class' => 'block',
		'data-xf-init' => 'attachment-manager',
	)) . '


';
	return $__finalCompiled;
}
);