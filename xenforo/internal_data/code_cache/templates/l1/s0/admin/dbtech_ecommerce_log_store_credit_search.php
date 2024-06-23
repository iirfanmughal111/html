<?php
// FROM HASH: b60794c67af83c110f42e16f46bd5d01
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search store credit logs');
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
		'explain' => 'Enter an IP address to see a list of all store credit usages logged as having originated from that IP. You may enter a partial IP address such as 192.168.*, 192.168.0.0/16, or 2001:db8::/32.',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('

				<div class="inputGroup">
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[store_credit_amount][start]',
		'value' => $__vars['criteria']['store_credit_amount']['start'],
		'step' => '1',
	)) . '
					<span class="inputGroup-text">-</span>
					' . $__templater->formNumberBox(array(
		'name' => 'criteria[store_credit_amount][end]',
		'value' => $__vars['criteria']['store_credit_amount']['end'],
		'step' => '1',
	)) . '
				</div>
			', array(
		'rowtype' => 'input',
		'label' => 'Store credit amount is between',
		'explain' => 'Use negative values for store credit used, or positive values for store credit earned.',
	)) . '

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
		'action' => $__templater->func('link', array('dbtech-ecommerce/logs/store-credit', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);