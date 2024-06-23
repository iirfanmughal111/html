<?php
// FROM HASH: 5c83474b22e08dc308493bc655180294
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], 'escaped', ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Extra information');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->escape($__vars['product']['title']) . ' - ' . 'Extra information');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'field_' . $__vars['fieldId'];
	$__templater->wrapTemplate('dbtech_ecommerce_product_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="block-body block-row">
	' . $__templater->callMacro('custom_fields_macros', 'custom_field_value', array(
		'definition' => $__vars['fieldDefinition'],
		'value' => $__vars['fieldValue'],
	), $__vars) . '
</div>';
	return $__finalCompiled;
}
);