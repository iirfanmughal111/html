<?php
// FROM HASH: 46810fe389032e0f403ac307c4af62d1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit order #' . $__templater->escape($__vars['order']['order_id']) . '');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['addresses'], 'empty', array())) {
		$__compilerTemp1 .= '
			<h2 class="block-header">' . 'Choose address' . '</h2>
			<div class="block-body">
				';
		$__compilerTemp2 = array(array(
			'value' => '0',
			'label' => $__templater->filter('New address', array(array('parens', array()),), true),
			'_type' => 'option',
		));
		$__compilerTemp2 = $__templater->mergeChoiceOptions($__compilerTemp2, $__vars['addresses']);
		$__compilerTemp1 .= $__templater->formSelectRow(array(
			'name' => 'address_id',
			'value' => $__vars['order']['address_id'],
		), $__compilerTemp2, array(
			'label' => 'Address',
		)) . '
			</div>
		';
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		' . $__compilerTemp1 . '
		<h2 class="block-header">' . 'Add address' . '</h2>

		' . $__templater->callMacro('dbtech_ecommerce_address_edit_macros', 'form_contents', array(), $__vars) . '

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/account/order/edit', $__vars['order'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);