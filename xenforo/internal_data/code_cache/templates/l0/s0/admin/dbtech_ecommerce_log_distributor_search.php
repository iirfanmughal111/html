<?php
// FROM HASH: 05a28c1be5d79f417db0c953de9d019c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search distributor logs');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[Distributor][username]',
		'ac' => 'single',
		'value' => $__vars['criteria']['Distributor']['username'],
	), array(
		'label' => 'Distributor',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[Recipient][username]',
		'ac' => 'single',
		'value' => $__vars['criteria']['Recipient']['username'],
	), array(
		'label' => 'Recipient',
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
		'action' => $__templater->func('link', array('dbtech-ecommerce/logs/distributors', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);