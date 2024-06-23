<?php
// FROM HASH: ea8b09c4f88b28971bbe079798d46f44
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['shippingMethod'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add shipping method');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit shipping method' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['shippingMethod']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['shippingMethod'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/shipping-methods/delete', $__vars['shippingMethod'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

' . $__templater->form('
	<div class="block-container">
		' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['shippingMethod']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['shippingMethod'], 'title', ), false),
	), array(
		'label' => 'Title',
	)) . '

		' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'value' => '1',
		'selected' => $__vars['shippingMethod']['active'],
		'label' => 'Enabled',
		'_type' => 'option',
	)), array(
	)) . '

		' . $__templater->callMacro('display_order_macros', 'row', array(
		'value' => $__vars['shippingMethod']['display_order'],
	), $__vars) . '

		<hr class="formRowSep" />

		' . $__templater->formTextBoxRow(array(
		'name' => 'cost_formula',
		'value' => $__vars['shippingMethod']['cost_formula'],
	), array(
		'label' => 'Cost formula',
		'explain' => 'You can either enter a flat amount (in ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . ', without the currency symbol) to be charged per item shipped using this shipping method, or you can use a formula.<br />
You can tie separate shipping methods to separate shipping zones.<br />
<br />
Replacement variables:
<ul>
	<li><code>q</code> - the total number of items being shipped using this shipping method for any given order</li>
	<li><code>c</code> - total cost of all items being shipped using this shipping method (in ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . ', without the currency symbol) for any given order</li>
	<li><code>w</code> - total weight (in ' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceShippingWeightUnit']) . ') of all items being shipped using this shipping method for any given order</li>
</ul>',
	)) . '

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/shipping-methods/save', $__vars['shippingMethod'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);