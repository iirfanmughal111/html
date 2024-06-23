<?php
// FROM HASH: 5e38530b3a80f57278a0adbb4cba6417
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Move product');
	$__finalCompiled .= '

';
	$__vars['productRepo'] = $__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', ));
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	$__compilerTemp2 = $__templater->method($__vars['productRepo'], 'getFlattenedProductTree', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['treeEntry']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['treeEntry']['record']['product_id'],
				'disabled' => (!$__templater->method($__vars['treeEntry']['record'], 'isValidAddOnTarget', array())),
				'label' => $__templater->func('repeat', array('--', $__vars['treeEntry']['depth'], ), true) . '
						' . $__templater->escape($__vars['treeEntry']['record']['title']) . '
					',
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formSelectRow(array(
		'name' => 'target_product_id',
		'value' => $__vars['product']['parent_product_id'],
	), $__compilerTemp1, array(
		'label' => 'Target product',
	)) . '
			
			' . $__templater->formPrefixInputRow($__vars['prefixes'], array(
		'type' => 'dbtechEcommerceProduct',
		'prefix-value' => $__vars['product']['prefix_id'],
		'textbox-value' => $__vars['product']['title'],
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->callMacro('public:helper_action', 'author_alert', array(
		'selected' => true,
	), $__vars) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/products/add-on/move', $__vars['product'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);