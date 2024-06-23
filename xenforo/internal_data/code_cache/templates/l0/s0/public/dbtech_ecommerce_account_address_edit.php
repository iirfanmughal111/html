<?php
// FROM HASH: 81c5b087964d264e826ff3c67f61cadd
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
	if ($__templater->method($__vars['address'], 'isInsert', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add address');
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit address' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['address']['title']));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['address'], 'isUpdate', array()) AND $__templater->method($__vars['address'], 'canDelete', array())) {
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
	' . $__templater->button('', array(
			'href' => $__templater->func('link', array('dbtech-ecommerce/account/address-book/delete', $__vars['address'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
		), '', array(
		)) . '
');
	}
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

' . $__templater->callMacro('dbtech_ecommerce_address_edit_macros', 'edit_form', array(
		'linkPrefix' => 'dbtech-ecommerce/account/address-book',
		'address' => $__vars['address'],
	), $__vars);
	return $__finalCompiled;
}
);