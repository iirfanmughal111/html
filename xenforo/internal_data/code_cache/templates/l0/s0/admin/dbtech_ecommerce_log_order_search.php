<?php
// FROM HASH: 761483f532dc063cdc807dedf00c440d
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<!--suppress ALL -->
';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search orders');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['coupons']);
	$__compilerTemp2 = array(array(
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp2 = $__templater->mergeChoiceOptions($__compilerTemp2, $__vars['countries']);
	$__compilerTemp3 = array(array(
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'Any' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp3 = $__templater->mergeChoiceOptions($__compilerTemp3, $__vars['countries']);
	$__compilerTemp4 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[order_id]',
		'value' => $__vars['criteria']['order_id'],
		'pattern' => '\\d*',
	), array(
		'label' => 'Order ID',
	)) . '

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
		'explain' => 'Enter an IP address to see a list of all orders logged as having originated from that IP. You may enter a partial IP address such as 192.168.*, 192.168.0.0/16, or 2001:db8::/32.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[order_date][start]',
		'value' => $__vars['criteria']['order_date']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[order_date][end]',
		'value' => $__vars['criteria']['order_date']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Order date is between',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[completed_date][start]',
		'value' => $__vars['criteria']['completed_date']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[completed_date][end]',
		'value' => $__vars['criteria']['completed_date']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Completed date is between',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[reversed_date][start]',
		'value' => $__vars['criteria']['reversed_date']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[reversed_date][end]',
		'value' => $__vars['criteria']['reversed_date']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Reversed date is between',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[sub_total][start]',
		'value' => $__vars['criteria']['sub_total']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[sub_total][end]',
		'value' => $__vars['criteria']['sub_total']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Sub-total is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[sale_discounts][start]',
		'value' => $__vars['criteria']['sale_discounts']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[sale_discounts][end]',
		'value' => $__vars['criteria']['sale_discounts']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Sale discount is between',
		'explain' => 'Use -1 to specify no maximum.',
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

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[automatic_discounts][start]',
		'value' => $__vars['criteria']['automatic_discounts']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[automatic_discounts][end]',
		'value' => $__vars['criteria']['automatic_discounts']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Automatic discount is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[store_credit_amount][start]',
		'value' => $__vars['criteria']['store_credit_amount']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[store_credit_amount][end]',
		'value' => $__vars['criteria']['store_credit_amount']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Store credit amount is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[sales_tax][start]',
		'value' => $__vars['criteria']['sales_tax']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[sales_tax][end]',
		'value' => $__vars['criteria']['sales_tax']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Sales tax is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[cost_amount][start]',
		'value' => $__vars['criteria']['cost_amount']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[cost_amount][end]',
		'value' => $__vars['criteria']['cost_amount']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Order total is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[order_state]',
	), array(array(
		'value' => 'pending',
		'selected' => $__templater->func('in_array', array('pending', $__vars['criteria']['order_state'], ), false),
		'label' => 'Pending',
		'_type' => 'option',
	),
	array(
		'value' => 'awaiting_payment',
		'selected' => $__templater->func('in_array', array('awaiting_payment', $__vars['criteria']['order_state'], ), false),
		'label' => 'Awaiting payment',
		'_type' => 'option',
	),
	array(
		'value' => 'completed',
		'selected' => $__templater->func('in_array', array('completed', $__vars['criteria']['order_state'], ), false),
		'label' => 'Completed',
		'_type' => 'option',
	),
	array(
		'value' => 'shipped',
		'selected' => $__templater->func('in_array', array('shipped', $__vars['criteria']['order_state'], ), false),
		'label' => 'Shipped',
		'_type' => 'option',
	),
	array(
		'value' => 'reversed',
		'selected' => $__templater->func('in_array', array('reversed', $__vars['criteria']['order_state'], ), false),
		'label' => 'Reversed / refunded',
		'_type' => 'option',
	)), array(
		'label' => 'State',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'criteria[coupon_id]',
		'value' => $__vars['criteria']['coupon_id'],
	), $__compilerTemp1, array(
		'label' => 'Coupon',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'criteria[Address][country_code]',
		'value' => $__vars['criteria']['Address']['country_code'],
	), $__compilerTemp2, array(
		'label' => 'Country',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'criteria[ShippingAddress][country_code]',
		'value' => $__vars['criteria']['ShoppingAddress']['country_code'],
	), $__compilerTemp3, array(
		'label' => 'Shipping address country',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('

				<div class="inputPair">
					' . $__templater->formSelect(array(
		'name' => 'order',
	), $__compilerTemp4) . '
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
		'action' => $__templater->func('link', array('dbtech-ecommerce/logs/orders', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);