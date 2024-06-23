<?php
// FROM HASH: 2fa4f3d672310d84adade8f78867c7de
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Viewing license' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['license']['title']));
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
				<a href="' . $__templater->func('link', array('users/edit', $__vars['license']['User'], ), true) . '">' . $__templater->escape($__vars['license']['User']['username']) . '</a>
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
	} else if ($__vars['license']['license_state'] == 'awaiting_payment') {
		$__compilerTemp2 .= '
					' . 'Awaiting payment' . '
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
					' . $__templater->callMacro('public:custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceLicenses',
		'group' => null,
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