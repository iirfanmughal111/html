<?php
// FROM HASH: 60236705b972ea6d2fb5e68fa28219a9
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['license']['Product'], 'escaped', ), true) . $__templater->escape($__vars['license']['Product']['title'])) . ' (' . $__templater->escape($__vars['license']['license_key']) . ')');
	$__finalCompiled .= '
';
	$__templater->pageParams['pageH1'] = $__templater->preEscaped('' . ($__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['license']['Product'], ), true) . $__templater->escape($__vars['license']['Product']['title'])) . ' (' . $__templater->escape($__vars['license']['license_key']) . ')');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				' . ($__templater->escape($__vars['license']['title']) ?: 'Unknown product') . '
				<div class="u-muted">' . $__templater->escape($__vars['license']['license_key']) . '</div>
			', array(
		'label' => 'Product',
	)) . '

			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('members', $__vars['license']['User'], ), true) . '">' . $__templater->escape($__vars['license']['User']['username']) . '</a>
			', array(
		'label' => 'License owner',
	)) . '

			' . $__templater->formRow('
				' . $__templater->func('date_dynamic', array($__vars['license']['purchase_date'], array(
		'data-full-date' => 'true',
	))) . '
			', array(
		'label' => 'Purchase date',
	)) . '

			';
	$__compilerTemp1 = '';
	if ($__templater->method($__vars['license'], 'isLifetime', array())) {
		$__compilerTemp1 .= '
					' . 'Never' . '
				';
	} else {
		$__compilerTemp1 .= '
					' . $__templater->func('date_dynamic', array($__vars['license']['expiry_date'], array(
			'data-full-date' => 'true',
		))) . '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp1 . '
			', array(
		'label' => 'Expiry date',
	)) . '

			';
	$__compilerTemp2 = '';
	if ($__vars['license']['license_state'] == 'visible') {
		$__compilerTemp2 .= '
					' . 'In good standing' . '
				';
	} else if ($__vars['license']['license_state'] == 'deleted') {
		$__compilerTemp2 .= '
					' . $__templater->callMacro('public:deletion_macros', 'notice', array(
			'log' => $__vars['license']['DeletionLog'],
		), $__vars) . '
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . $__compilerTemp2 . '
			', array(
		'label' => 'Status',
	)) . '

			';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
					' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceLicenses',
		'group' => ($__templater->method($__vars['xf']['visitor'], 'canViewDbtechEcommerceLicenses', array()) ? null : array('info', 'list', )),
		'set' => $__vars['license']['license_fields'],
		'valueClass' => 'formRow',
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
				<hr class="formRowSep" />
				' . $__compilerTemp3 . '
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);