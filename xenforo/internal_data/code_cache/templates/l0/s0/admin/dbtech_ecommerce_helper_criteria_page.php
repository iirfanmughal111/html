<?php
// FROM HASH: 319cbafa285778c6378cf071fc3ce9b3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['data']['dbtechEcommerceCategories'])) {
		foreach ($__vars['data']['dbtechEcommerceCategories'] AS $__vars['option']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['option']['value'],
				'label' => $__templater->escape($__vars['option']['label']),
				'_type' => 'option',
			);
		}
	}
	$__compilerTemp2 = array();
	if ($__templater->isTraversable($__vars['data']['dbtechEcommerceProducts']['products'])) {
		foreach ($__vars['data']['dbtechEcommerceProducts']['products'] AS $__vars['categoryId'] => $__vars['products']) {
			$__compilerTemp2[] = array(
				'label' => $__vars['data']['dbtechEcommerceProducts']['categories'][$__vars['categoryId']]['record']['title'],
				'_type' => 'optgroup',
				'options' => array(),
			);
			end($__compilerTemp2); $__compilerTemp3 = key($__compilerTemp2);
			if ($__templater->isTraversable($__vars['products'])) {
				foreach ($__vars['products'] AS $__vars['productTreeEntry']) {
					$__compilerTemp2[$__compilerTemp3]['options'][] = array(
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
		'name' => 'page_criteria[dbtech_ecommerce_categories][rule]',
		'value' => 'dbtech_ecommerce_categories',
		'selected' => $__vars['criteria']['dbtech_ecommerce_categories'],
		'label' => 'Page is within product categories' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'page_criteria[dbtech_ecommerce_categories][data][category_ids]',
		'multiple' => 'true',
		'value' => $__vars['criteria']['dbtech_ecommerce_categories']['category_ids'],
	), $__compilerTemp1), $__templater->formCheckBox(array(
	), array(array(
		'name' => 'page_criteria[dbtech_ecommerce_categories][data][category_only]',
		'value' => '1',
		'selected' => $__vars['criteria']['dbtech_ecommerce_categories']['category_only'],
		'label' => 'Only display within selected categories (rather than including child categories)',
		'_type' => 'option',
	)))),
		'_type' => 'option',
	),
	array(
		'name' => 'page_criteria[dbtech_ecommerce_products][rule]',
		'value' => 'dbtech_ecommerce_products',
		'selected' => $__vars['criteria']['dbtech_ecommerce_products'],
		'label' => 'Page is for product' . $__vars['xf']['language']['label_separator'],
		'_dependent' => array($__templater->formSelect(array(
		'name' => 'page_criteria[dbtech_ecommerce_products][data][product_ids]',
		'multiple' => 'true',
		'value' => $__vars['criteria']['dbtech_ecommerce_products']['product_ids'],
	), $__compilerTemp2)),
		'_type' => 'option',
	),
	array(
		'label' => 'Product is part of "All-Access Pass"',
		'name' => 'page_criteria[dbtech_ecommerce_all_access][rule]',
		'value' => 'dbtech_ecommerce_all_access',
		'selected' => $__vars['criteria']['dbtech_ecommerce_all_access'],
		'_type' => 'option',
	)), array(
		'label' => 'DragonByte eCommerce',
	));
	return $__finalCompiled;
}
);