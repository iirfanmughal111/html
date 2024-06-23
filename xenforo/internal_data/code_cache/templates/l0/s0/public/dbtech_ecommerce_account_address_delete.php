<?php
// FROM HASH: 88f469a13d5a45ab69a4d2b4196b34eb
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Confirm action');
	$__finalCompiled .= '

' . $__templater->form('

	<div class="block-container">
		<div class="block-body">
			' . $__templater->formInfoRow('
				' . 'Please confirm that you want to delete the following' . $__vars['xf']['language']['label_separator'] . '
				<strong><a href="' . $__templater->func('link', array('dbtech-ecommerce/account/address-book/edit', $__vars['address'], ), true) . '">' . $__templater->escape($__vars['address']['title']) . '</a></strong>
			', array(
		'rowtype' => 'confirm',
	)) . '

		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'delete',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>

	' . $__templater->func('redirect_input', array(null, null, true)) . '

', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/account/address-book/delete', $__vars['address'], ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);