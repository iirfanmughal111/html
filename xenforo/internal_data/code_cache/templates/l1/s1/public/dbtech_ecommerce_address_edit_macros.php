<?php
// FROM HASH: 07e30541d1e1349d03bbda3616770a97
return array(
'macros' => array('edit_form' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'context' => 'public',
		'linkPrefix' => '!',
		'address' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	' . $__templater->form('
		<div class="block-container">
			' . $__templater->callMacro(null, 'form_contents', array(
		'address' => $__vars['address'],
	), $__vars) . '

			' . $__templater->formSubmitRow(array(
		'sticky' => 'true',
		'icon' => 'save',
	), array(
	)) . '
		</div>
	', array(
		'action' => $__templater->func('link_type', array($__vars['context'], $__vars['linkPrefix'] . '/save', $__vars['address'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
';
	return $__finalCompiled;
}
),
'form_contents' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'address' => null,
		'required' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block-body">

		' . $__templater->formTextBoxRow(array(
		'name' => 'title',
		'value' => $__vars['address']['title'],
		'required' => ($__vars['required'] ? 'true' : ''),
		'maxlength' => $__templater->func('max_length', array($__vars['address'], 'title', ), false),
	), array(
		'label' => 'Title',
		'hint' => 'Required',
		'explain' => 'A friendly name used to identify the address on the checkout screen. For example, "Home" or "Work".',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'business_title',
		'value' => $__vars['address']['business_title'],
		'required' => ($__vars['required'] ? 'true' : ''),
		'maxlength' => $__templater->func('max_length', array($__vars['address'], 'business_title', ), false),
	), array(
		'label' => 'Business name',
		'hint' => 'Required',
		'explain' => 'The name of your business, or your own name if you do not have a registered business. This will appear on your invoice.',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'business_co',
		'value' => $__vars['address']['business_co'],
		'maxlength' => $__templater->func('max_length', array($__vars['address'], 'business_co', ), false),
	), array(
		'label' => 'Business c/o',
		'explain' => 'If your business address has a c/o ("care of") name, you can add it here.',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'address1',
		'value' => $__vars['address']['address1'],
		'maxlength' => $__templater->func('max_length', array($__vars['address'], 'address1', ), false),
	), array(
		'label' => 'Address line 1',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'address2',
		'value' => $__vars['address']['address2'],
		'maxlength' => $__templater->func('max_length', array($__vars['address'], 'address2', ), false),
	), array(
		'label' => 'Address line 2',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'address3',
		'value' => $__vars['address']['address3'],
		'maxlength' => $__templater->func('max_length', array($__vars['address'], 'address3', ), false),
	), array(
		'label' => 'Address line 3',
	)) . '

		' . $__templater->formTextBoxRow(array(
		'name' => 'address4',
		'value' => $__vars['address']['address4'],
		'maxlength' => $__templater->func('max_length', array($__vars['address'], 'address4', ), false),
	), array(
		'label' => 'Address line 4',
	)) . '

		';
	$__compilerTemp1 = $__templater->mergeChoiceOptions(array(), $__templater->method($__templater->method($__vars['xf']['app']['em'], 'getRepository', array('DBTech\\eCommerce:Country', )), 'getCountrySelectData', array()));
	$__finalCompiled .= $__templater->formSelectRow(array(
		'name' => 'country_code',
		'value' => ($__vars['address']['country_code'] ?: $__vars['xf']['options']['dbtechEcommerceDefaultAddressCountry']),
	), $__compilerTemp1, array(
		'label' => 'Country',
		'explain' => 'You or your business\' resident country. The native name of the country will be printed on the invoice.',
	)) . '

		';
	if ($__vars['xf']['options']['dbtechEcommerceSalesTax']['enabled']) {
		$__finalCompiled .= '
			';
		$__vars['country'] = $__templater->method($__vars['xf']['app']['em'], 'find', array('DBTech\\eCommerce:Country', $__vars['xf']['options']['dbtechEcommerceAddressCountry'], ));
		$__finalCompiled .= '

			' . $__templater->formTextBoxRow(array(
			'name' => 'sales_tax_id',
			'value' => $__vars['address']['sales_tax_id'],
			'maxlength' => $__templater->func('max_length', array($__vars['address'], 'sales_tax_id', ), false),
		), array(
			'label' => ($__vars['xf']['options']['dbtechEcommerceSalesTax']['enableVat'] ? 'VAT registration number' : 'Sales tax ID'),
			'explain' => ($__vars['xf']['options']['dbtechEcommerceSalesTax']['enableVat'] ? 'If you provide a VAT ID and your business is registered outside of ' . $__templater->escape($__vars['country']['name']) . ', we don\'t need to charge VAT on your order.<br />
Please enter your VAT ID without spaces.' : 'If you have a sales tax ID and would like it to appear on your invoices, you can enter it here.<br />
We are not registered for EU VAT, as we are located in ' . $__templater->escape($__vars['country']['name']) . '.'),
		)) . '
		';
	}
	$__finalCompiled .= '

		' . $__templater->formCheckBoxRow(array(
	), array(array(
		'name' => 'is_default',
		'value' => '1',
		'selected' => $__vars['address']['is_default'],
		'label' => 'Set as default address',
		'_type' => 'option',
	)), array(
	)) . '

		';
	if ((!$__vars['address']) AND (!$__vars['xf']['visitor']['user_id'])) {
		$__finalCompiled .= '
			' . $__templater->formTextBoxRow(array(
			'name' => 'email',
			'value' => $__vars['address']['email'],
			'maxlength' => $__templater->func('max_length', array($__vars['address'], 'email', ), false),
		), array(
			'label' => 'Email',
		)) . '
		';
	}
	$__finalCompiled .= '
	</div>

	' . '
';
	return $__finalCompiled;
}
),
'display_pairs' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'address' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<dl class="pairs pairs--columns pairs--fixedSmall">
		<dt>' . 'Business name' . '</dt>
		<dd>' . $__templater->escape($__vars['address']['business_title']) . '</dd>
	</dl>

	';
	if ($__vars['address']['business_co']) {
		$__finalCompiled .= '
		<dl class="pairs pairs--columns pairs--fixedSmall">
			<dt>' . 'Business c/o' . '</dt>
			<dd>' . $__templater->escape($__vars['address']['business_co']) . '</dd>
		</dl>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['address1']) {
		$__finalCompiled .= '
		<dl class="pairs pairs--columns pairs--fixedSmall">
			<dt>' . 'Address line 1' . '</dt>
			<dd>' . $__templater->escape($__vars['address']['address1']) . '</dd>
		</dl>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['address2']) {
		$__finalCompiled .= '
		<dl class="pairs pairs--columns pairs--fixedSmall">
			<dt>' . 'Address line 2' . '</dt>
			<dd>' . $__templater->escape($__vars['address']['address2']) . '</dd>
		</dl>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['address3']) {
		$__finalCompiled .= '
		<dl class="pairs pairs--columns pairs--fixedSmall">
			<dt>' . 'Address line 3' . '</dt>
			<dd>' . $__templater->escape($__vars['address']['address3']) . '</dd>
		</dl>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['address4']) {
		$__finalCompiled .= '
		<dl class="pairs pairs--columns pairs--fixedSmall">
			<dt>' . 'Address line 4' . '</dt>
			<dd>' . $__templater->escape($__vars['address']['address4']) . '</dd>
		</dl>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['sales_tax_id']) {
		$__finalCompiled .= '
		<dl class="pairs pairs--columns pairs--fixedSmall">
			<dt>' . 'VAT registration number' . '</dt>
			<dd>' . $__templater->escape($__vars['address']['sales_tax_id']) . '</dd>
		</dl>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['address']['country_code']) {
		$__finalCompiled .= '
		<dl class="pairs pairs--columns pairs--fixedSmall">
			<dt>' . 'Country' . '</dt>
			<dd>' . $__templater->escape($__vars['address']['Country']['native_name']) . '</dd>
		</dl>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '

';
	return $__finalCompiled;
}
);