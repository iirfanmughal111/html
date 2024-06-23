<?php
// FROM HASH: 87410efef02deff8deaebae8f7792969
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Address book'), $__templater->func('link', array('dbtech-ecommerce/account/address-book', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['address']['title']));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			';
	if ($__vars['address']['address_state'] == 'moderated') {
		$__compilerTemp1 .= '
				<dd class="blockStatus-message blockStatus-message--moderated">
					' . 'Your VAT ID is awaiting approval before the tax status can be applied.' . '
				</dd>
			';
	}
	$__compilerTemp1 .= '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	<dl class="blockStatus blockStatus--standalone">
		<dt>' . 'Status' . '</dt>
		' . $__compilerTemp1 . '
	</dl>
';
	}
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow($__templater->escape($__vars['address']['title']), array(
		'label' => 'Title',
	)) . '

			' . $__templater->formRow($__templater->escape($__vars['address']['business_title']), array(
		'label' => 'Business name',
	)) . '

			';
	if ($__vars['address']['business_co']) {
		$__finalCompiled .= '
				' . $__templater->formRow($__templater->escape($__vars['address']['business_co']), array(
			'label' => 'Business c/o',
		)) . '
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['address']['address1']) {
		$__finalCompiled .= '
				' . $__templater->formRow($__templater->escape($__vars['address']['address1']), array(
			'label' => 'Address line 1',
		)) . '
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['address']['address2']) {
		$__finalCompiled .= '
				' . $__templater->formRow($__templater->escape($__vars['address']['address2']), array(
			'label' => 'Address line 2',
		)) . '
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['address']['address3']) {
		$__finalCompiled .= '
				' . $__templater->formRow($__templater->escape($__vars['address']['address3']), array(
			'label' => 'Address line 3',
		)) . '
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['address']['address4']) {
		$__finalCompiled .= '
				' . $__templater->formRow($__templater->escape($__vars['address']['address4']), array(
			'label' => 'Address line 4',
		)) . '
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['address']['sales_tax_id']) {
		$__finalCompiled .= '
				' . $__templater->formRow($__templater->escape($__vars['address']['sales_tax_id']), array(
			'label' => 'VAT registration number',
		)) . '
			';
	}
	$__finalCompiled .= '

			';
	if ($__vars['address']['country_code']) {
		$__finalCompiled .= '
				' . $__templater->formRow($__templater->escape($__vars['address']['Country']['native_name']), array(
			'label' => 'Country',
		)) . '
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);