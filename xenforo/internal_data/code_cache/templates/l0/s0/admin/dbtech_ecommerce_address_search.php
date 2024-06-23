<?php
// FROM HASH: f473862b80878a5150aaad3a292700a2
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Search addresses');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Country', )), 'getCountrySelectData', array()));
	$__compilerTemp2 = $__templater->mergeChoiceOptions(array(), $__vars['sortOrders']);
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[User][username]',
		'ac' => 'single',
	), array(
		'label' => 'User',
	)) . '

			<hr class="formRowSep" />

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[title]',
		'value' => $__vars['criteria']['title'],
	), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[business_title]',
		'value' => $__vars['criteria']['business_title'],
	), array(
		'label' => 'Business name',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[business_co]',
		'value' => $__vars['criteria']['business_co'],
	), array(
		'label' => 'Business c/o',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[address1]',
		'value' => $__vars['criteria']['address1'],
	), array(
		'label' => 'Address line 1',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[address2]',
		'value' => $__vars['criteria']['address2'],
	), array(
		'label' => 'Address line 2',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[address3]',
		'value' => $__vars['criteria']['address3'],
	), array(
		'label' => 'Address line 3',
	)) . '

			' . $__templater->formTextBoxRow(array(
		'name' => 'criteria[address4]',
		'value' => $__vars['criteria']['address4'],
	), array(
		'label' => 'Address line 4',
	)) . '

			' . $__templater->formSelectRow(array(
		'name' => 'criteria[country_code]',
		'value' => $__vars['criteria']['country_code'],
	), $__compilerTemp1, array(
		'label' => 'Country',
	)) . '


			<hr class="formRowSep" />

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[has_sales_tax]',
	), array(array(
		'value' => '1',
		'selected' => $__templater->func('in_array', array(1, $__vars['criteria']['has_sales_tax'], ), false),
		'label' => 'Yes',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'selected' => $__templater->func('in_array', array(0, $__vars['criteria']['has_sales_tax'], ), false),
		'label' => 'No',
		'_type' => 'option',
	)), array(
		'label' => 'Has sales tax ID',
	)) . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[has_orders]',
	), array(array(
		'value' => '1',
		'selected' => $__templater->func('in_array', array(1, $__vars['criteria']['has_orders'], ), false),
		'label' => 'Yes',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'selected' => $__templater->func('in_array', array(0, $__vars['criteria']['has_orders'], ), false),
		'label' => 'No',
		'_type' => 'option',
	)), array(
		'label' => 'Used in orders',
	)) . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[is_guest]',
	), array(array(
		'value' => '1',
		'selected' => $__templater->func('in_array', array(1, $__vars['criteria']['is_guest'], ), false),
		'label' => 'Yes',
		'_type' => 'option',
	),
	array(
		'value' => '0',
		'selected' => $__templater->func('in_array', array(0, $__vars['criteria']['is_guest'], ), false),
		'label' => 'No',
		'_type' => 'option',
	)), array(
		'label' => 'Is guest address',
	)) . '

			' . $__templater->formCheckBoxRow(array(
		'name' => 'criteria[address_state]',
	), array(array(
		'value' => 'visible',
		'selected' => $__templater->func('in_array', array('visible', $__vars['criteria']['address_state'], ), false),
		'label' => 'Visible',
		'_type' => 'option',
	),
	array(
		'value' => 'verified',
		'selected' => $__templater->func('in_array', array('verified', $__vars['criteria']['address_state'], ), false),
		'label' => 'VAT ID verified',
		'_type' => 'option',
	),
	array(
		'value' => 'moderated',
		'selected' => $__templater->func('in_array', array('moderated', $__vars['criteria']['address_state'], ), false),
		'label' => 'Awaiting approval',
		'_type' => 'option',
	)), array(
		'label' => 'State',
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
		'action' => $__templater->func('link', array('dbtech-ecommerce/addresses', ), false),
		'class' => 'block',
	));
	return $__finalCompiled;
}
);