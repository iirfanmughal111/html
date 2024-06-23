<?php
// FROM HASH: 43da2293a70cee4609d1ad1b7539d775
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search coupon logs');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['coupons']);
	$__compilerTemp2 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[User][username]',
		'ac' => 'single',
		'value' => $__vars['criteria']['User']['username'],
	), array(
		'label' => 'User',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[ip]',
		'value' => $__vars['criteria']['ip'],
	), array(
		'label' => 'IP address',
		'explain' => 'Enter an IP address to see a list of all coupon usages logged as having originated from that IP. You may enter a partial IP address such as 192.168.*, 192.168.0.0/16, or 2001:db8::/32.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[log_date][start]',
		'value' => $__vars['criteria']['log_date']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[log_date][end]',
		'value' => $__vars['criteria']['log_date']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Coupon used between',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[coupon_discounts][start]',
		'value' => $__vars['criteria']['coupon_discounts']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[coupon_discounts][end]',
		'value' => $__vars['criteria']['coupon_discounts']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Coupon discount is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'criteria[coupon_id]',
		'value' => $__vars['criteria']['coupon_id'],
	), $__compilerTemp1, array(
		'label' => 'Coupon',
	)) . '

			' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_edit', array(
		'inputName' => 'criteria[product_id]',
		'productId' => '',
		'includeBlank' => false,
		'includeAny' => true,
	), $__vars) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('

				<div class="inputPair">
					' . $__templater->formSelect(array(
		'name' => 'order',
	), $__compilerTemp2) . '
					' . $__templater->formSelect(array(
		'name' => 'direction',
		'value' => 'desc',
	), array(array(
		'value' => 'asc',
		'label' => 'Ascending',
		'_type' => 'option',
	),
	array(
		'value' => 'desc',
		'label' => 'Descending',
		'_type' => 'option',
	))) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Sort',
	)) . '
		</div>
		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'search',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/logs/coupons', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);