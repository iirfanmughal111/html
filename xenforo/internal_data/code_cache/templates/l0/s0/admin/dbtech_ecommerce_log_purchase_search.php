<?php
// FROM HASH: f12ee0caeea21e0340c917c9fecfc93a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search purchase logs');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
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
		'explain' => 'Enter an IP address to see a list of all purchases logged as having originated from that IP. You may enter a partial IP address such as 192.168.*, 192.168.0.0/16, or 2001:db8::/32.',
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
		'label' => 'Purchase date between',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[payment_amount][start]',
		'value' => $__vars['criteria']['payment_amount']['start'],
		'size' => '5',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[payment_amount][end]',
		'value' => $__vars['criteria']['payment_amount']['end'],
		'size' => '5',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Purchase amount is between',
		'explain' => 'Use negative values to find reversed / refunded orders.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[log_type]',
	), array(array(
		'value' => 'new',
		'selected' => $__templater->func('in_array', array('new', $__vars['criteria']['log_type'], ), false),
		'label' => 'New sale',
		'_type' => 'option',
	),
	array(
		'value' => 'upgrade',
		'selected' => $__templater->func('in_array', array('upgrade', $__vars['criteria']['log_type'], ), false),
		'label' => 'Upgrade',
		'_type' => 'option',
	),
	array(
		'value' => 'renew',
		'selected' => $__templater->func('in_array', array('renew', $__vars['criteria']['log_type'], ), false),
		'label' => 'Renewal',
		'_type' => 'option',
	),
	array(
		'value' => 'reversal',
		'selected' => $__templater->func('in_array', array('reversal', $__vars['criteria']['log_type'], ), false),
		'label' => 'Transaction reversed',
		'_type' => 'option',
	),
	array(
		'value' => 'refunded',
		'selected' => $__templater->func('in_array', array('refunded', $__vars['criteria']['log_type'], ), false),
		'label' => 'Refunded',
		'_type' => 'option',
	)), array(
		'label' => 'Type',
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
	), $__compilerTemp1) . '
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
		'action' => $__templater->func('link', array('dbtech-ecommerce/logs/purchases', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);