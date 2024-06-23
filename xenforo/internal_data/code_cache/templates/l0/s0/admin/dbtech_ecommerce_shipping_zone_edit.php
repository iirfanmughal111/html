<?php
// FROM HASH: ffe52838eee7689a2be04efffc70db60
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['shippingZone'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add shipping zone');
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit shipping zone' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['shippingZone']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['shippingZone'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/shipping-zones/delete', $__vars['shippingZone'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['shippingMethods']);
	$__compilerTemp2 = $__templater->mergeChoiceOptions(array(), $__vars['countries']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['shippingZone']['title'],
		'maxlength' => $__templater->func('max_length', array($__vars['shippingZone'], 'title', ), false),
	), array(
		'label' => 'Title',
	)) . '

		' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'active',
		'value' => '1',
		'selected' => $__vars['shippingZone']['active'],
		'label' => 'Enabled',
		'_type' => 'option',
	)), array(
	)) . '

		' . $__templater->callMacro('display_order_macros', 'row', array(
		'value' => $__vars['shippingZone']['display_order'],
	), $__vars) . '

		<hr class="formRowSep" />

		' . $__templater->formCheckBoxRow(array(
		'name' => 'shipping_methods',
		'value' => $__vars['shippingZone']['shipping_methods'],
		'listclass' => 'shippingMethods listColumns',
	), $__compilerTemp1, array(
		'label' => 'Included shipping methods',
		'explain' => 'Only the shipping methods selected here will be available for this shipping zone.<br />
Cost is determined by the shipping method, so if you need to charge more for this shipping zone, you can create a new shipping method and select it here.',
		'hint' => '
				' . $__templater->formCheckBox(array(
		'standalone' => 'true',
	), array(array(
		'check-all' => '.shippingMethods.listColumns',
		'label' => 'Select all',
		'_type' => 'option',
	))) . '
			',
	)) . '

		' . $__templater->formCheckBoxRow(array(
		'name' => 'countries',
		'value' => $__vars['shippingZone']['countries'],
		'listclass' => 'countries listColumns',
	), $__compilerTemp2, array(
		'label' => 'Included countries',
		'explain' => 'Select the countries you wish to ship to using this shipping zone.',
		'hint' => '
				' . $__templater->formCheckBox(array(
		'standalone' => 'true',
	), array(array(
		'check-all' => '.countries.listColumns',
		'label' => 'Select all',
		'_type' => 'option',
	))) . '
			',
	)) . '

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/shipping-zones/save', $__vars['shippingZone'], ), false),
		'ajax' => 'true',
		'class' => 'block',
	));
	return $__finalCompiled;
}
);