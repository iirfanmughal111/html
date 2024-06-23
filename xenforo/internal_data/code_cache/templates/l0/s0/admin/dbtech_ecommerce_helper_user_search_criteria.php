<?php
// FROM HASH: 23198d1e889ee56995675981a7028b17
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[dbtech_ecommerce_product_count][start]',
		'value' => $__vars['criteria']['dbtech_ecommerce_product_count']['start'],
		'step' => '1',
		'size' => '15',
		'readonly' => $__vars['readOnly'],
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[dbtech_ecommerce_product_count][end]',
		'value' => $__vars['criteria']['dbtech_ecommerce_product_count']['end'],
		'step' => '1',
		'size' => '15',
		'readonly' => $__vars['readOnly'],
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Product count is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[dbtech_ecommerce_license_count][start]',
		'value' => $__vars['criteria']['dbtech_ecommerce_license_count']['start'],
		'step' => '1',
		'size' => '15',
		'readonly' => $__vars['readOnly'],
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[dbtech_ecommerce_license_count][end]',
		'value' => $__vars['criteria']['dbtech_ecommerce_license_count']['end'],
		'step' => '1',
		'size' => '15',
		'readonly' => $__vars['readOnly'],
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'License count is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

' . $__templater->formRow('

	<div class="inputGroup">
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[dbtech_ecommerce_amount_spent][start]',
		'value' => $__vars['criteria']['dbtech_ecommerce_amount_spent']['start'],
		'step' => 'any',
		'size' => '15',
		'readonly' => $__vars['readOnly'],
	)) . '
		<span class="inputGroup-text">-</span>
		' . $__templater->formNumberBox(array(
		'name' => 'criteria[dbtech_ecommerce_amount_spent][end]',
		'value' => $__vars['criteria']['dbtech_ecommerce_amount_spent']['end'],
		'step' => 'any',
		'size' => '15',
		'readonly' => $__vars['readOnly'],
	)) . '
	</div>
', array(
		'rowtype' => 'input',
		'label' => 'Amount spent is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[dbtech_ecommerce_is_distributor]',
		'readonly' => $__vars['readOnly'],
	), array(array(
		'value' => '0',
		'selected' => $__templater->func('in_array', array(0, $__vars['criteria']['dbtech_ecommerce_is_distributor'], ), false),
		'label' => 'Not license distributor',
		'_type' => 'option',
	),
	array(
		'value' => '1',
		'selected' => $__templater->func('in_array', array(1, $__vars['criteria']['dbtech_ecommerce_is_distributor'], ), false),
		'label' => 'License distributor',
		'_type' => 'option',
	)), array(
		'label' => 'Distributor state',
	)) . '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['dbtechEcommerceProducts']['products'])) {
		foreach ($__vars['dbtechEcommerceProducts']['products'] AS $__vars['categoryId'] => $__vars['products']) {
			$__compilerTemp1[] = array(
				'label' => $__vars['dbtechEcommerceProducts']['categories'][$__vars['categoryId']]['record']['title'],
				'_type' => 'optgroup',
				'options' => array(),
			);
			end($__compilerTemp1); $__compilerTemp2 = key($__compilerTemp1);
			if ($__templater->isTraversable($__vars['products'])) {
				foreach ($__vars['products'] AS $__vars['productTreeEntry']) {
					$__compilerTemp1[$__compilerTemp2]['options'][] = array(
						'value' => $__vars['productTreeEntry']['record']['product_id'],
						'label' => $__templater->func('repeat', array('--', $__vars['productTreeEntry']['depth'], ), true) . '
					' . $__templater->escape($__vars['productTreeEntry']['record']['full_title']) . '
				',
						'_type' => 'option',
					);
				}
			}
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'criteria[dbtech_ecommerce_products]',
		'size' => '8',
		'multiple' => 'true',
		'value' => $__vars['criteria']['dbtech_ecommerce_products'],
		'listclass' => 'listColumns',
		'readonly' => $__vars['readOnly'],
	), $__compilerTemp1, array(
		'label' => 'User has purchased any of the selected products',
	)) . '

';
	$__compilerTemp3 = array();
	if ($__templater->isTraversable($__vars['dbtechEcommerceProducts']['products'])) {
		foreach ($__vars['dbtechEcommerceProducts']['products'] AS $__vars['categoryId'] => $__vars['products']) {
			$__compilerTemp3[] = array(
				'label' => $__vars['dbtechEcommerceProducts']['categories'][$__vars['categoryId']]['record']['title'],
				'_type' => 'optgroup',
				'options' => array(),
			);
			end($__compilerTemp3); $__compilerTemp4 = key($__compilerTemp3);
			if ($__templater->isTraversable($__vars['products'])) {
				foreach ($__vars['products'] AS $__vars['productTreeEntry']) {
					$__compilerTemp3[$__compilerTemp4]['options'][] = array(
						'value' => $__vars['productTreeEntry']['record']['product_id'],
						'label' => $__templater->func('repeat', array('--', $__vars['productTreeEntry']['depth'], ), true) . '
					' . $__templater->escape($__vars['productTreeEntry']['record']['full_title']) . '
				',
						'_type' => 'option',
					);
				}
			}
		}
	}
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'criteria[not_dbtech_ecommerce_products]',
		'size' => '8',
		'multiple' => 'true',
		'value' => $__vars['criteria']['not_dbtech_ecommerce_products'],
		'readonly' => $__vars['readOnly'],
	), $__compilerTemp3, array(
		'label' => 'User has NOT purchased any of the selected products',
	)) . '
	
<hr class="formRowSep" />';
	return $__finalCompiled;
}
);