<?php
// FROM HASH: bf7d97f6028843d076d9bef3873ee4ce
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add add-on product to' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

';
	$__vars['productRepo'] = $__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', ));
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_edit', array(
		'inputName' => 'parent_product_id',
		'productId' => '',
		'addonOnly' => true,
	), $__vars) . '
		</div>
		
		' . $__templater->formSubmitRow(array(
		'icon' => 'add',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/products/add-on/add', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);