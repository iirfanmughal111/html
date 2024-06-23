<?php
// FROM HASH: 272574ecc81e9a6e79e89f6e1b2049b1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['license'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add license');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit license' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['license']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['license'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/licenses/delete', $__vars['license'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['license'], 'isUpdate', array()) AND $__templater->method($__vars['license'], 'canSendModeratorActionAlert', array())) {
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
	if ($__templater->method($__vars['license'], 'isInsert', array())) {
		$__compilerTemp2 .= '
				' . $__templater->formTextBoxRow(array(
			'name' => 'username',
			'ac' => 'single',
		), array(
			'label' => 'License owner',
		)) . '
			';
	} else {
		$__compilerTemp2 .= '
				' . $__templater->formRow('
					' . $__templater->escape($__vars['license']['User']['username']) . ' <a href="' . $__templater->func('link', array('dbtech-ecommerce/licenses/reassign', $__vars['license'], ), true) . '" data-xf-click="overlay">' . 'Reassign' . '</a>
				', array(
			'label' => 'License owner',
		)) . '
				
				' . $__templater->formRow('
					' . $__templater->escape($__vars['license']['license_key']) . '
				', array(
			'label' => 'License key',
		)) . '				
			';
	}
	$__compilerTemp3 = '';
	if ($__templater->method($__vars['license'], 'isInsert', array())) {
		$__compilerTemp3 .= '
				' . $__templater->formRow('
					' . $__templater->escape($__vars['license']['Product']['title']) . '
				', array(
			'label' => 'Product',
		)) . '
				' . $__templater->formHiddenVal('product_id', $__vars['license']['product_id'], array(
		)) . '
			';
	} else {
		$__compilerTemp3 .= '
				' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_edit', array(
			'productId' => $__vars['license']['product_id'],
		), $__vars) . '
			';
	}
	$__compilerTemp4 = '';
	if ($__templater->method($__vars['license'], 'isInsert', array())) {
		$__compilerTemp4 .= '
				' . $__templater->formRadioRow(array(
			'name' => 'length_type',
		), array(array(
			'value' => 'permanent',
			'selected' => true,
			'label' => 'Permanent',
			'_type' => 'option',
		),
		array(
			'value' => 'timed',
			'label' => 'For length' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array('
							<div class="inputGroup">
								' . $__templater->formNumberBox(array(
			'name' => 'length_amount',
			'value' => '1',
			'min' => '1',
			'max' => '255',
		)) . '
								<span class="inputGroup-splitter"></span>
								' . $__templater->formSelect(array(
			'name' => 'length_unit',
			'value' => 'month',
			'class' => 'input--inline',
		), array(array(
			'value' => 'day',
			'label' => 'Days',
			'_type' => 'option',
		),
		array(
			'value' => 'month',
			'label' => 'Months',
			'_type' => 'option',
		),
		array(
			'value' => 'year',
			'label' => 'Years',
			'_type' => 'option',
		))) . '
							</div>
						'),
			'_type' => 'option',
		)), array(
			'label' => 'Length',
		)) . '
			';
	} else {
		$__compilerTemp4 .= '
				' . $__templater->formRadioRow(array(
			'name' => 'length_type',
		), array(array(
			'value' => 'permanent',
			'selected' => $__templater->method($__vars['license'], 'isLifetime', array()),
			'label' => 'Never',
			'_type' => 'option',
		),
		array(
			'value' => 'timed',
			'selected' => (!$__templater->method($__vars['license'], 'isLifetime', array())),
			'label' => 'Date' . $__vars['xf']['language']['label_separator'],
			'_dependent' => array('
							<div class="inputGroup">
								' . $__templater->formDateInput(array(
			'name' => 'expiry_date',
			'value' => ($__vars['license']['expiry_date'] ? $__templater->func('date', array($__vars['license']['expiry_date'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
		)) . '
								<span class="inputGroup-splitter"></span>
								' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'expiry_time',
			'value' => ($__vars['license']['expiry_date'] ? $__templater->func('date', array($__vars['license']['expiry_date'], 'H:i', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'H:i', ), false)),
		)) . '
							</div>
						'),
			'_type' => 'option',
		)), array(
			'label' => 'Expiry date',
		)) . '
			';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			
			' . $__compilerTemp1 . '			
			
			' . $__compilerTemp2 . '
			
			' . $__compilerTemp3 . '			

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'purchase_date',
		'value' => ($__vars['license']['purchase_date'] ? $__templater->func('date', array($__vars['license']['purchase_date'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formTextBox(array(
		'type' => 'time',
		'name' => 'purchase_time',
		'value' => ($__vars['license']['purchase_date'] ? $__templater->func('date', array($__vars['license']['purchase_date'], 'H:i', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'H:i', ), false)),
	)) . '
				</div>
			', array(
		'label' => 'Purchase date',
		'rowtype' => 'input',
	)) . '

			' . $__compilerTemp4 . '

			<hr class="formRowSep" />

			' . $__templater->callMacro('public:custom_fields_macros', 'custom_fields_edit', array(
		'type' => 'dbtechEcommerceLicenses',
		'set' => $__vars['license']['license_fields'],
		'editMode' => 'admin',
		'namePrefix' => 'license_fields',
	), $__vars) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/licenses/save', $__vars['license'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);