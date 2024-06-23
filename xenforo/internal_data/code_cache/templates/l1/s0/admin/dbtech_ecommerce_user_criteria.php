<?php
// FROM HASH: 836d9b36efaefa3929937bbc8d3aacc4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_criteria[dbtech_ecommerce_has_pending_payment][rule]',
		'value' => 'dbtech_ecommerce_has_pending_payment',
		'selected' => $__vars['criteria']['dbtech_ecommerce_has_pending_payment'],
		'label' => 'User has one or more pending payments',
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[dbtech_ecommerce_product_count][rule]',
		'value' => 'dbtech_ecommerce_product_count',
		'selected' => $__vars['criteria']['dbtech_ecommerce_product_count'],
		'label' => 'User has created at least X products' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[dbtech_ecommerce_product_count][data][products]',
		'value' => $__vars['criteria']['dbtech_ecommerce_product_count']['products'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[not_dbtech_ecommerce_product_count][rule]',
		'value' => 'not_dbtech_ecommerce_product_count',
		'selected' => $__vars['criteria']['not_dbtech_ecommerce_product_count'],
		'label' => 'User has created fewer than X products' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[not_dbtech_ecommerce_product_count][data][products]',
		'value' => $__vars['criteria']['not_dbtech_ecommerce_product_count']['products'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[dbtech_ecommerce_license_count][rule]',
		'value' => 'dbtech_ecommerce_license_count',
		'selected' => $__vars['criteria']['dbtech_ecommerce_license_count'],
		'label' => 'User has purchased at least X licenses' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[dbtech_ecommerce_license_count][data][licenses]',
		'value' => $__vars['criteria']['dbtech_ecommerce_license_count']['licenses'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[not_dbtech_ecommerce_license_count][rule]',
		'value' => 'not_dbtech_ecommerce_license_count',
		'selected' => $__vars['criteria']['not_dbtech_ecommerce_license_count'],
		'label' => 'User has purchased fewer than X licenses' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[not_dbtech_ecommerce_license_count][data][licenses]',
		'value' => $__vars['criteria']['not_dbtech_ecommerce_license_count']['licenses'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[dbtech_ecommerce_amount_spent][rule]',
		'value' => 'dbtech_ecommerce_amount_spent',
		'selected' => $__vars['criteria']['dbtech_ecommerce_amount_spent'],
		'label' => 'User has spent at least X ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[dbtech_ecommerce_amount_spent][data][amount]',
		'value' => $__vars['criteria']['dbtech_ecommerce_amount_spent']['amount'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[not_dbtech_ecommerce_amount_spent][rule]',
		'value' => 'not_dbtech_ecommerce_amount_spent',
		'selected' => $__vars['criteria']['not_dbtech_ecommerce_amount_spent'],
		'label' => 'User has spent less than X ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formNumberBox(array(
		'name' => 'user_criteria[not_dbtech_ecommerce_amount_spent][data][amount]',
		'value' => $__vars['criteria']['not_dbtech_ecommerce_amount_spent']['amount'],
		'size' => '5',
		'min' => '0',
		'step' => '1',
	))),
		'_type' => 'option',
	)), array(
		'label' => 'DragonByte eCommerce',
	)) . '

';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['data']['dbtechEcommerceProducts']['products'])) {
		foreach ($__vars['data']['dbtechEcommerceProducts']['products'] AS $__vars['categoryId'] => $__vars['products']) {
			$__compilerTemp1[] = array(
				'label' => $__vars['data']['dbtechEcommerceProducts']['categories'][$__vars['categoryId']]['record']['title'],
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
	$__compilerTemp3 = array();
	if ($__templater->isTraversable($__vars['data']['dbtechEcommerceProducts']['products'])) {
		foreach ($__vars['data']['dbtechEcommerceProducts']['products'] AS $__vars['categoryId'] => $__vars['products']) {
			$__compilerTemp3[] = array(
				'label' => $__vars['data']['dbtechEcommerceProducts']['categories'][$__vars['categoryId']]['record']['title'],
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
	$__finalCompiled .= $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'user_criteria[dbtech_ecommerce_products][rule]',
		'value' => 'dbtech_ecommerce_products',
		'selected' => $__vars['criteria']['dbtech_ecommerce_products'],
		'label' => 'User has purchased any of the selected products' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'user_criteria[dbtech_ecommerce_products][data][product_ids]',
		'size' => '4',
		'multiple' => 'true',
		'value' => $__vars['criteria']['dbtech_ecommerce_products']['product_ids'],
	), $__compilerTemp1)),
		'_type' => 'option',
	),
	array(
		'name' => 'user_criteria[not_dbtech_ecommerce_products][rule]',
		'value' => 'not_dbtech_ecommerce_products',
		'selected' => $__vars['criteria']['not_dbtech_ecommerce_products'],
		'label' => 'User has NOT purchased any of the selected products' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'user_criteria[not_dbtech_ecommerce_products][data][product_ids]',
		'size' => '4',
		'multiple' => 'true',
		'value' => $__vars['criteria']['not_dbtech_ecommerce_products']['product_ids'],
	), $__compilerTemp3)),
		'_type' => 'option',
	)), array(
		'label' => 'eCommerce products',
	)) . '

<hr class="formRowSep" />';
	return $__finalCompiled;
}
);