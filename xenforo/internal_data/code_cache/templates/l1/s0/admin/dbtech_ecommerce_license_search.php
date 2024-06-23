<?php
// FROM HASH: 14a52a6265f431e577ddf468fca00e63
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('License search');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
					';
	$__compilerTemp3 = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:LicenseField', )), 'getDisplayGroups', array());
	if ($__templater->isTraversable($__compilerTemp3)) {
		foreach ($__compilerTemp3 AS $__vars['fieldGroup'] => $__vars['phrase']) {
			$__compilerTemp2 .= '
						';
			$__vars['customFields'] = $__templater->method($__vars['xf']['app'], 'getCustomFields', array('dbtechEcommerceLicenses', $__vars['fieldGroup'], ));
			$__compilerTemp2 .= '
						';
			$__compilerTemp4 = '';
			$__compilerTemp4 .= '
								';
			if ($__templater->isTraversable($__vars['customFields'])) {
				foreach ($__vars['customFields'] AS $__vars['fieldId'] => $__vars['fieldDefinition']) {
					$__compilerTemp4 .= '
									';
					$__vars['choices'] = $__vars['fieldDefinition']['field_choices'];
					$__compilerTemp4 .= '
									';
					$__vars['fieldName'] = 'criteria[license_fields]' . (($__vars['choices'] AND ($__vars['fieldDefinition']['type_group'] != 'multiple')) ? '[exact]' : '') . '[' . $__vars['fieldId'] . ']';
					$__compilerTemp4 .= '
									';
					$__compilerTemp5 = '';
					if (!$__vars['choices']) {
						$__compilerTemp5 .= '
											' . $__templater->formTextBox(array(
							'name' => $__vars['fieldName'],
							'value' => $__vars['criteria'][$__vars['fieldName']]['text'],
							'readonly' => $__vars['readOnly'],
						)) . '
										';
					} else {
						$__compilerTemp5 .= '
											';
						$__compilerTemp6 = array();
						if ($__templater->isTraversable($__vars['choices'])) {
							foreach ($__vars['choices'] AS $__vars['val'] => $__vars['choice']) {
								$__compilerTemp6[] = array(
									'value' => (($__vars['fieldDefinition']['type_group'] == 'multiple') ? (((('s:' . $__templater->func('strlen', array($__vars['val'], ), false)) . ':"') . $__vars['val']) . '"') : $__vars['val']),
									'label' => $__templater->escape($__vars['choice']),
									'_type' => 'option',
								);
							}
						}
						$__compilerTemp5 .= $__templater->formCheckBox(array(
							'name' => $__vars['fieldName'],
							'value' => $__vars['criteria']['custom'][$__vars['fieldId']],
							'listclass' => 'listColumns',
							'readonly' => $__vars['readOnly'],
						), $__compilerTemp6) . '
										';
					}
					$__compilerTemp4 .= $__templater->formRow('

										' . $__compilerTemp5 . '

									', array(
						'rowtype' => ($__vars['choices'] ? '' : 'input'),
						'label' => $__templater->escape($__vars['fieldDefinition']['title']),
					)) . '
								';
				}
			}
			$__compilerTemp4 .= '
							';
			if (strlen(trim($__compilerTemp4)) > 0) {
				$__compilerTemp2 .= '
							' . $__compilerTemp4 . '
						';
			}
			$__compilerTemp2 .= '
					';
		}
	}
	$__compilerTemp2 .= '
				';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
				<hr class="formRowSep" />
				' . $__compilerTemp2 . '
			';
	}
	$__compilerTemp7 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[User][username]',
		'ac' => 'single',
	), array(
		'label' => 'User',
	)) . '
			
			<hr class="formRowSep" />

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[purchase_date][start]',
		'value' => $__vars['criteria']['purchase_date']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[purchase_date][end]',
		'value' => $__vars['criteria']['purchase_date']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Purchased between',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[expiry_date][start]',
		'value' => $__vars['criteria']['expiry_date']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[expiry_date][end]',
		'value' => $__vars['criteria']['expiry_date']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Expires between',
	)) . '
			
			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[license_state]',
	), array(array(
		'value' => 'visible',
		'selected' => $__templater->func('in_array', array('visible', $__vars['criteria']['license_state'], ), false),
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'awaiting_payment',
		'selected' => $__templater->func('in_array', array('awaiting_payment', $__vars['criteria']['license_state'], ), false),
		'label' => 'Awaiting payment',
		'_type' => 'option',
	),
	array(
		'value' => 'deleted',
		'selected' => $__templater->func('in_array', array('deleted', $__vars['criteria']['license_state'], ), false),
		'label' => 'Deleted',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'selected' => $__templater->func('in_array', array('moderated', $__vars['criteria']['license_state'], ), false),
		'label' => 'Moderated',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	)) . '

			' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_edit', array(
		'inputName' => 'criteria[product_id]',
		'productId' => '',
		'includeBlank' => false,
		'includeAny' => true,
		'licensesOnly' => true,
	), $__vars) . '

			' . $__compilerTemp1 . '

			<hr class="formRowSep" />

			' . $__templater->formRow('

				<div class="inputPair">
					' . $__templater->formSelect(array(
		'name' => 'order',
	), $__compilerTemp7) . '
					' . $__templater->formSelect(array(
		'name' => 'direction',
		'value' => 'desc',
	), array(array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	),
	array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	))) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Sort',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'search',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/licenses', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);