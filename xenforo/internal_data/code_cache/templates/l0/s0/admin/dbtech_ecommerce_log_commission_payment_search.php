<?php
// FROM HASH: d29f5666c4ea18bc44f89275d2daebf9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<!--suppress ALL -->
';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search commission payments');
	$__finalCompiled .= '

';
	$__compilerTemp1 = array(array(
		'label' => $__vars['xf']['language']['parenthesis_open'] . 'None' . $__vars['xf']['language']['parenthesis_close'],
		'_type' => 'option',
	));
	$__compilerTemp1 = $__templater->mergeChoiceOptions($__compilerTemp1, $__vars['commissions']);
	$__compilerTemp2 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formSelectRow(array(
		'name' => 'criteria[commission_id]',
		'value' => $__vars['criteria']['commission_id'],
	), $__compilerTemp1, array(
		'label' => 'Name',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[User][username]',
		'ac' => 'single',
		'value' => $__vars['criteria']['User']['username'],
	), array(
		'label' => 'Recorded by',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'criteria[payment_date][start]',
		'value' => $__vars['criteria']['payment_date']['start'],
		'size' => '15',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formDateInput(array(
		'name' => 'criteria[payment_date][end]',
		'value' => $__vars['criteria']['payment_date']['end'],
		'size' => '15',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Payment made between',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[payment_amount][start]',
		'value' => $__vars['criteria']['payment_amount']['start'],
		'size' => '5',
		'min' => '0',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[payment_amount][end]',
		'value' => $__vars['criteria']['payment_amount']['end'],
		'size' => '5',
		'min' => '-1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Payment amount is between',
		'explain' => 'Use -1 to specify no maximum.',
	)) . '

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
		'action' => $__templater->func('link', array('dbtech-ecommerce/logs/commission-payments', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);