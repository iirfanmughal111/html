<?php
// FROM HASH: 9beeabb8f9b887fd162a31bf3a6fd6a0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->method($__vars['sale'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add sale');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit sale' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['sale']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['sale'], 'isUpdate', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/sales/delete', $__vars['sale'], ), false),
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
	if ($__templater->method($__vars['sale'], 'isInsert', array())) {
		$__compilerTemp1 .= '
				' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'email_notify',
			'selected' => $__vars['sale']['email_notify'],
			'label' => 'Send an email when the sale starts',
			'_dependent' => array('
							' . $__templater->formCheckBox(array(
		), array(array(
			'name' => 'email_notify_immediately',
			'selected' => false,
			'label' => 'Also send an email immediately after saving',
			'_type' => 'option',
		))) . '
						'),
			'_type' => 'option',
		)), array(
			'explain' => 'If the option to immediately send an alert is selected, an email will be queued directly after saving this sale, informing users when the sale will start and the details of the sale as chosen below.<br />
Please note that this option will not be available when editing a sale, to avoid the possibility of accidentally spamming members. If you delete the sale or turn off the above option during edit, this alert will be cancelled and you will not be able to send another immediate alert.',
		)) . '
			';
	} else {
		$__compilerTemp1 .= '
				' . $__templater->formCheckBoxRow(array(
		), array(array(
			'name' => 'email_notify',
			'selected' => $__vars['sale']['email_notify'],
			'label' => 'Send an email when the sale starts',
			'_type' => 'option',
		)), array(
		)) . '				
			';
	}
	$__compilerTemp2 = '';
	if ($__templater->method($__vars['sale'], 'isInsert', array())) {
		$__compilerTemp2 .= '
				' . $__templater->formRow('

					<div class="inputGroup">
						' . $__templater->formNumberBox(array(
			'name' => 'length_amount',
			'value' => '7',
			'min' => '1',
			'max' => '255',
		)) . '
						<span class="inputGroup-splitter"></span>
						' . $__templater->formSelect(array(
			'name' => 'length_unit',
			'value' => 'day',
			'class' => 'input--inline',
		), array(array(
			'value' => 'day',
			'label' => 'Days',
			'_type' => 'option',
		),
		array(
			'value' => 'month',
			'label' => 'Months',
			'_type' => 'option',
		),
		array(
			'value' => 'year',
			'label' => 'Years',
			'_type' => 'option',
		))) . '
					</div>
				', array(
			'rowtype' => 'input',
			'label' => 'Run sale for',
		)) . '
			';
	} else {
		$__compilerTemp2 .= '
				' . $__templater->formRow('

					<div class="inputGroup">
						' . $__templater->formDateInput(array(
			'name' => 'end_date',
			'value' => ($__vars['sale']['end_date'] ? $__templater->func('date', array($__vars['sale']['end_date'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
		)) . '
						<span class="inputGroup-splitter"></span>
						' . $__templater->formTextBox(array(
			'type' => 'time',
			'name' => 'end_time',
			'value' => ($__vars['sale']['end_date'] ? $__templater->func('date', array($__vars['sale']['end_date'], 'H:i', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'H:i', ), false)),
		)) . '
					</div>
				', array(
			'rowtype' => 'input',
			'label' => 'End date',
		)) . '
			';
	}
	$__compilerTemp3 = '';
	if ($__templater->isTraversable($__vars['sale']['other_dates'])) {
		foreach ($__vars['sale']['other_dates'] AS $__vars['counter'] => $__vars['dateInfo']) {
			$__compilerTemp3 .= '
						<li class="inputPair">
							<div class="inputGroup">
								<div class="inputGroup">
									' . $__templater->formDateInput(array(
				'name' => 'other_dates[' . $__vars['counter'] . '][start]',
				'value' => $__templater->func('date', array($__vars['dateInfo']['start'], 'picker', ), false),
				'size' => '15',
			)) . '
									<span class="inputGroup-text">-</span>
									' . $__templater->formDateInput(array(
				'name' => 'other_dates[' . $__vars['counter'] . '][end]',
				'value' => $__templater->func('date', array($__vars['dateInfo']['end'], 'picker', ), false),
				'size' => '15',
			)) . '
								</div>
							</div>
						</li>
					';
		}
	}
	$__compilerTemp4 = '';
	if ($__templater->isTraversable($__vars['sale']['product_discounts'])) {
		foreach ($__vars['sale']['product_discounts'] AS $__vars['counter'] => $__vars['discountInfo']) {
			$__compilerTemp4 .= '
						<li class="inputPair">
							<div class="inputGroup">
								' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
				'inputName' => 'product_discounts[' . $__vars['counter'] . '][product_id]',
				'productsByCategory' => $__vars['productsByCategory'],
				'productId' => $__vars['discountInfo']['product_id'],
				'row' => false,
				'class' => 'filterBlock-input',
				'includeBlank' => false,
				'includeNone' => true,
			), $__vars) . '

								<span class="inputGroup-splitter"></span>

								' . $__templater->formNumberBox(array(
				'name' => 'product_discounts[' . $__vars['counter'] . '][product_value]',
				'min' => '0',
				'value' => $__vars['discountInfo']['product_value'],
				'step' => 'any',
				'required' => false,
			)) . '
							</div>
						</li>
					';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['sale']['title'],
	), array(
		'label' => 'Title',
	)) . '

		' . $__templater->formTextAreaRow(array(
		'name' => 'description',
		'value' => ($__templater->method($__vars['sale'], 'exists', array()) ? $__vars['sale']['MasterDescription']['phrase_text'] : ''),
		'rows' => '2',
		'autosize' => 'true',
		'class' => 'input--fitHeight--short',
	), array(
		'label' => 'Description',
		'explain' => 'A short description that will be included in the email notifications regarding this sale.<br />
It is not needed to include start/end information, as this will be automatically added to the email.',
	)) . '

			<hr class="formRowSep" />
			
			' . $__compilerTemp1 . '

			' . $__templater->formRadioRow(array(
		'name' => 'sale_type',
		'value' => $__vars['sale']['sale_type'],
	), array(array(
		'value' => 'percent',
		'label' => 'Percent',
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => 'sale_percent',
		'value' => $__vars['sale']['sale_percent'],
		'min' => '1',
		'max' => '100',
		'step' => 'any',
	)) . '
							<span class="inputGroup-text">%</span>
						</div>
					'),
		'_type' => 'option',
	),
	array(
		'value' => 'value',
		'label' => 'Flat value',
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => 'sale_value',
		'value' => $__vars['sale']['sale_value'],
		'min' => '1',
		'step' => 'any',
	)) . '
							<span class="inputGroup-text">' . $__templater->escape($__vars['xf']['options']['dbtechEcommerceCurrency']) . '</span>
						</div>
					'),
		'_type' => 'option',
	)), array(
		'label' => 'Sale type',
		'explain' => 'Determines what sort of discount is applied to the customer\'s cart; a percent off the product(s) on sale, or a set discount.',
	)) . '

			' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'discount_excluded',
		'value' => '1',
		'selected' => $__vars['sale']['discount_excluded'],
		'label' => 'Discount excluded from maximum',
		'hint' => 'If enabled, the discount provided by this sale will not count towards the user\'s maximum discount.',
		'_type' => 'option',
	),
	array(
		'name' => 'allow_auto_discount',
		'value' => '1',
		'selected' => $__vars['sale']['allow_auto_discount'],
		'label' => 'Allow automatic discount to apply',
		'hint' => 'If not enabled, products where this sale is applied is not susceptible to automatic discounts.',
		'_type' => 'option',
	),
	array(
		'name' => 'feature_products',
		'value' => '1',
		'selected' => $__vars['sale']['feature_products'],
		'label' => 'Feature products during the sale',
		'hint' => 'This will bump the product(s) to the top of the list during this sale.<br />
Note that this overrides any manual "Featured" toggle in the product(s), they will be automatically un-featured at the end of this sale.',
		'_type' => 'option',
	),
	array(
		'name' => 'is_recurring',
		'value' => '1',
		'selected' => $__vars['sale']['is_recurring'],
		'label' => 'Recurring sale',
		'hint' => 'This will automatically reset the sale at the specified interval.',
		'_dependent' => array('
						<div class="inputGroup">
							' . $__templater->formNumberBox(array(
		'name' => 'recurring_length_amount',
		'value' => $__vars['sale']['recurring_length_amount'],
		'min' => '1',
		'max' => '255',
	)) . '
							<span class="inputGroup-splitter"></span>
							' . $__templater->formSelect(array(
		'name' => 'recurring_length_unit',
		'value' => $__vars['sale']['recurring_length_unit'],
		'class' => 'input--inline',
	), array(array(
		'value' => 'day',
		'label' => 'Days',
		'_type' => 'option',
	),
	array(
		'value' => 'month',
		'label' => 'Months',
		'_type' => 'option',
	),
	array(
		'value' => 'year',
		'label' => 'Years',
		'_type' => 'option',
	))) . '
						</div>
					'),
		'_type' => 'option',
	)), array(
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formRow('
				<div class="inputGroup">
					' . $__templater->formDateInput(array(
		'name' => 'start_date',
		'value' => ($__vars['sale']['start_date'] ? $__templater->func('date', array($__vars['sale']['start_date'], 'picker', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'picker', ), false)),
	)) . '
					<span class="inputGroup-splitter"></span>
					' . $__templater->formTextBox(array(
		'type' => 'time',
		'name' => 'start_time',
		'value' => ($__vars['sale']['start_date'] ? $__templater->func('date', array($__vars['sale']['start_date'], 'H:i', ), false) : $__templater->func('date', array($__vars['xf']['time'], 'H:i', ), false)),
	)) . '
				</div>
			', array(
		'label' => 'Start date',
		'rowtype' => 'input',
	)) . '

			' . $__compilerTemp2 . '

			' . $__templater->formRow('

				<ul class="listPlain inputPair-container">
					' . $__compilerTemp3 . '
					<li class="inputPair" data-xf-init="field-adder" data-increment-format="other_dates[{counter}]">
						<div class="inputGroup">
							' . $__templater->formDateInput(array(
		'name' => 'other_dates[' . $__vars['nextDateCounter'] . '][start]',
		'size' => '15',
	)) . '
							<span class="inputGroup-text">-</span>
							' . $__templater->formDateInput(array(
		'name' => 'other_dates[' . $__vars['nextDateCounter'] . '][end]',
		'size' => '15',
	)) . '
						</div>
					</li>
				</ul>
			', array(
		'rowtype' => 'input',
		'label' => 'Other dates',
		'explain' => 'If your sale cannot be set to recur at regular intervals, you can set your own custom dates here.<br />
Any invalid date ranges (e.g. end date before start date) will be silently discarded.<br />
<br />
<strong>Note:</strong> Adding any dates here will disable the "recurring" feature, if enabled.',
	)) . '

			' . $__templater->formRow('

				<ul class="listPlain inputPair-container">
					' . $__compilerTemp4 . '
					<li class="inputPair" data-xf-init="field-adder" data-increment-format="product_discounts[{counter}]">
						<div class="inputGroup">
							' . $__templater->callMacro('public:dbtech_ecommerce_product_macros', 'product_select', array(
		'inputName' => 'product_discounts[' . $__vars['nextDiscountCounter'] . '][product_id]',
		'productsByCategory' => $__vars['productsByCategory'],
		'productId' => '',
		'row' => false,
		'class' => 'filterBlock-input',
		'includeBlank' => false,
		'includeNone' => true,
	), $__vars) . '

							<span class="inputGroup-splitter"></span>

							' . $__templater->formNumberBox(array(
		'name' => 'product_discounts[' . $__vars['nextDiscountCounter'] . '][product_value]',
		'min' => '0',
		'step' => 'any',
		'required' => false,
	)) . '
						</div>
					</li>
				</ul>
			', array(
		'rowtype' => 'input',
		'label' => 'Applicable products',
		'explain' => 'Leaving the value field blank will use the "Sale type" value chosen above.<br />
Set product to "None" to remove a row upon saving.<br />
If no products are chosen, this sale applies to <b>all</b> products.',
	)) . '
		</div>

		' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/sales/save', $__vars['sale'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);