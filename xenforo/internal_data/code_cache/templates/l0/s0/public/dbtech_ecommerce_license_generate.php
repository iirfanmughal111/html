<?php
// FROM HASH: f6a4f591a6e47c6db0acd3f98a293800
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Generate license');
	$__finalCompiled .= '

';
	$__vars['productRepo'] = $__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', ));
	$__compilerTemp1 = array(array(
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->method($__vars['productRepo'], 'getFlattenedProductTree', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			if ($__vars['distributor']['available_products'][$__vars['treeEntry']['record']['product_id']] AND ($__vars['distributor']['available_products'][$__vars['treeEntry']['record']['product_id']]['available_licenses'] != 0)) {
				$__compilerTemp1[] = array(
					'value' => $__vars['treeEntry']['record']['product_id'],
					'label' => $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . '
						' . $__templater->escape($__vars['treeEntry']['record']['full_title']) . '
						- ' . (($__vars['distributor']['available_products'][$__vars['treeEntry']['record']['product_id']]['available_licenses'] == -1) ? 'Unlimited remaining' : '' . $__templater->filter($__vars['distributor']['available_products'][$__vars['treeEntry']['record']['product_id']]['available_licenses'], array(array('number', array()),), true) . ' remaining') . '
					',
					'_type' => 'option',
				);
			}
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">

			' . $__templater->formTextBoxRow(array(
		'name' => 'recipient',
		'ac' => 'single',
	), array(
		'label' => 'Recipient',
	)) . '

			' . '' . '
			' . $__templater->formSelectRow(array(
		'name' => 'product_id',
	), $__compilerTemp1, array(
		'label' => 'Product',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'length_amount',
		'value' => $__vars['distributor']['license_length_amount'],
		'min' => '1',
		'max' => '255',
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formSelect(array(
		'name' => 'length_unit',
		'value' => $__vars['distributor']['license_length_unit'],
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
			', array(
		'label' => 'Length',
		'explain' => 'Maximum length: ' . $__templater->escape($__vars['distributor']['length']) . '',
	)) . '

		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/licenses/generate', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);