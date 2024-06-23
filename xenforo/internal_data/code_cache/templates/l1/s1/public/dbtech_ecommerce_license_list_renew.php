<?php
// FROM HASH: 8bddb1c0b4e1b598b54f14c521929c17
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->breadcrumb($__templater->preEscaped('Your account'), $__templater->func('link', array('dbtech-ecommerce/account', ), false), array(
	));
	$__finalCompiled .= '
';
	$__templater->breadcrumb($__templater->preEscaped('Your licenses'), $__templater->func('link', array('dbtech-ecommerce/licenses', ), false), array(
	));
	$__finalCompiled .= '

';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Renew licenses');
	$__finalCompiled .= '

';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('dbtech_ecommerce.less');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__templater->isTraversable($__vars['licenses'])) {
		foreach ($__vars['licenses'] AS $__vars['license']) {
			$__compilerTemp1 .= '
					' . $__templater->callMacro('dbtech_ecommerce_license_list_macros', 'license_list_entry', array(
				'license' => $__vars['license'],
				'children' => array(),
				'chooseName' => 'renewals',
				'allowInlineMod' => false,
			), $__vars) . '
				';
		}
	}
	$__finalCompiled .= $__templater->form('
	<div class="block-container">
		<h2 class="block-header">' . $__templater->formCheckBox(array(
		'standalone' => 'true',
	), array(array(
		'check-all' => '#js-renewItems',
		'_type' => 'option',
	))) . '</h2>
		<div class="block-body">
			<div class="structItemContainer" id="js-renewItems">
				' . $__compilerTemp1 . '
			</div>
		</div>

		' . $__templater->formSubmitRow(array(
		'icon' => 'refresh',
		'submit' => 'Renew',
		'sticky' => 'true',
	), array(
	)) . '
	</div>
', array(
		'action' => $__templater->func('link', array('dbtech-ecommerce/licenses/renew', ), false),
		'class' => 'block',
		'ajax' => 'true',
	));
	return $__finalCompiled;
}
);