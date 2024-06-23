<?php
// FROM HASH: 25954de3294123fba26557c2291c6366
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['address'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add address');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit address' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['address']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['address'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/addresses/delete', $__vars['address'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['address'], 'isUpdate', array()) AND $__templater->method($__vars['address'], 'canSendModeratorActionAlert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formRow('
					' . $__templater->callMacro('public:helper_action', 'author_alert', array(
			'row' => false,
		), $__vars) . '
				', array(
			'label' => 'Edit notice',
		)) . '
				
				<hr class="formRowSep" />
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['address'], 'isInsert', array())) {
		$__compilerTemp2 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'ac' => 'single',
		), array(
			'label' => 'Address owner',
		)) . '
			';
	} else {
		$__compilerTemp2 .= '
				' . $__templater->formRow('
					' . $__templater->escape($__vars['address']['User']['username']) . '
				', array(
			'label' => 'Address owner',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			
			' . $__compilerTemp1 . '			
			
			' . $__compilerTemp2 . '

			' . $__templater->callMacro('public:dbtech_ecommerce_address_edit_macros', 'form_contents', array(
		'address' => $__vars['address'],
	), $__vars) . '

			' . $__templater->formSelectRow(array(
		'name' => 'address_state',
		'value' => $__vars['address']['address_state'],
	), array(array(
		'value' => 'visible',
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'verified',
		'label' => 'VAT ID verified',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'label' => 'Awaiting approval',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/addresses/save', $__vars['address'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);