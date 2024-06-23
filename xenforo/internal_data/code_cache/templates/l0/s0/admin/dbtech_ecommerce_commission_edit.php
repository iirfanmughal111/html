<?php
// FROM HASH: e21b4f1e0864da7ed39d9ac72c19e249
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['commission'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add commission');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit commissions' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['commission']['name']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['commission'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/commissions/delete', $__vars['commission'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
	$__finalCompiled .= '

';
	$__vars['productsByCategory'] = $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Product', )), 'getProductsByCategory', array());
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__vars['i'] = 0;
	if ($__templater->isTraversable($__vars['commission']['Products'])) {
		foreach ($__vars['commission']['Products'] AS $__vars['map']) {
			$__vars['i']++;
			$__compilerTemp1 .= '
						<li class="inputPair">
							<div class="inputGroup">
								' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
				'inputName' => 'product_commissions[' . $__vars['i'] . '][product_id]',
				'productsByCategory' => $__vars['productsByCategory'],
				'productId' => $__vars['map']['product_id'],
				'row' => false,
				'class' => 'filterBlock-input',
				'includeBlank' => false,
				'includeNone' => true,
			), $__vars) . '

								<span class="inputGroup-splitter"></span>

								' . $__templater->formNumberBox(array(
				'name' => 'product_commissions[' . $__vars['i'] . '][commission_value]',
				'min' => '0',
				'value' => $__vars['map']['commission_value'],
				'step' => 'any',
				'required' => false,
			)) . '

								<span class="inputGroup-splitter"></span>

								' . $__templater->formSelect(array(
				'name' => 'product_commissions[' . $__vars['i'] . '][commission_type]',
				'value' => $__vars['map']['commission_type'],
				'class' => 'filterBlock-input',
			), array(array(
				'value' => 'percent',
				'label' => 'Percent',
				'_type' => 'option',
			),
			array(
				'value' => 'value',
				'label' => $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']),
				'_type' => 'option',
			))) . '
							</div>
						</li>
					';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'name',
		'value' => $__vars['commission']['name'],
		'maxlength' => $__templater->func('max_length', array($__vars['commission'], 'name', ), false),
	), array(
		'label' => 'Name',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'username',
		'ac' => 'single',
		'value' => $__vars['commission']['User']['username'],
	), array(
		'label' => 'Username',
		'explain' => 'If the recipient has a forum account, you can enter their user name here.',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'email',
		'value' => $__vars['commission']['email'],
		'maxlength' => $__templater->func('max_length', array($__vars['commission'], 'email', ), false),
	), array(
		'label' => 'Email',
	)) . '

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'date',
		'value' => ($__templater->method($__vars['commission'], 'isUpdate', array()) ? $__templater->func('date', array($__vars['commission']['last_paid_date'], 'picker', ), false) : ''),
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formTextBox(array(
		'type' => 'time',
		'name' => 'time',
		'value' => ($__templater->method($__vars['commission'], 'isUpdate', array()) ? $__templater->func('date', array($__vars['commission']['last_paid_date'], 'H:i', ), false) : ''),
	)) . '
				</div>
			', array(
		'label' => 'Last paid date',
		'explain' => 'This is set automatically when you record a payment. This field should not be altered unless you are sure you need to, as it may cause commissions to be granted twice for the same purchase.',
		'rowtype' => 'input',
	)) . '

			' . $__templater->formRow('

				<ul class="listPlain inputPair-container">
					' . $__compilerTemp1 . '
					<li class="inputPair" data-xf-init="field-adder" data-increment-format="product_commissions[{counter}]">
						<div class="inputGroup">
							' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
		'inputName' => 'product_commissions[' . $__vars['nextCounter'] . '][product_id]',
		'productsByCategory' => $__vars['productsByCategory'],
		'productId' => '',
		'row' => false,
		'class' => 'filterBlock-input',
		'includeBlank' => false,
		'includeNone' => true,
	), $__vars) . '

							<span class="inputGroup-splitter"></span>

							' . $__templater->formNumberBox(array(
		'name' => 'product_commissions[' . $__vars['nextCounter'] . '][commission_value]',
		'min' => '0',
		'step' => 'any',
		'required' => false,
	)) . '

							<span class="inputGroup-splitter"></span>

							' . $__templater->formSelect(array(
		'name' => 'product_commissions[' . $__vars['nextCounter'] . '][commission_type]',
		'class' => 'filterBlock-input',
	), array(array(
		'value' => 'percent',
		'label' => 'Percent',
		'_type' => 'option',
	),
	array(
		'value' => 'value',
		'label' => $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']),
		'_type' => 'option',
	))) . '
						</div>
					</li>
				</ul>
			', array(
		'rowtype' => 'input',
		'label' => 'Applicable products',
		'explain' => 'Choose the product and commission for that product, either as a percentage or a flat value.<br />
Set product to "None" to remove a row upon saving.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/commissions/save', $__vars['commission'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);