<?php
// FROM HASH: f516d29909f2cb13a0475a5f284acdf1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Choose product type' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

';
	$__compilerTemp1 = array();
	$__compilerTemp2 = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductTypeHandlers', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['productType'] => $__vars['handler']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['productType'],
				'label' => $__templater->escape($__templater->method($__vars['handler'], 'getProductTypePhrase', array())),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . $__templater->escape($__vars['category']['title']) . '
			', array(
		'label' => 'Category',
	)) . '

			' . $__templater->formRadioRow(array(
		'name' => 'product_type',
	), $__compilerTemp1, array(
		'label' => 'Product type',
	)) . '
		</div>
		
		' . $__templater->formHiddenVal('category_id', $__vars['category']['category_id'], array(
	)) . '
		' . $__templater->formSubmitRow(array(
		'icon' => 'add',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/products/add', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);