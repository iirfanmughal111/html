<?php
// FROM HASH: 017dee66f1cd22bec586bcaacec3c4f4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Download log entry');
	$__finalCompiled .= '

<div class="block">
	<div class="block-container">
		<div class="block-body">
			' . $__templater->formRow('
				<a href="' . $__templater->func('link', array('users/edit', $__vars['entry']['User'], ), true) . '">' . $__templater->escape($__vars['entry']['User']['username']) . '</a>
			', array(
		'label' => 'User',
	)) . '
			';
	if ($__vars['entry']['Ip']) {
		$__finalCompiled .= '
				' . $__templater->formRow('
					<a href="' . $__templater->func('link_type', array('public', 'misc/ip-info', null, array('ip' => $__templater->filter($__vars['entry']['Ip']['ip'], array(array('ip', array()),), false), ), ), true) . '" target="_blank" class="u-ltr">' . $__templater->filter($__vars['entry']['Ip']['ip'], array(array('ip', array()),), true) . '</a>
				', array(
			'label' => 'IP address',
		)) . '
			';
	}
	$__finalCompiled .= '
			' . $__templater->formRow('
				' . $__templater->func('date_dynamic', array($__vars['entry']['log_date'], array(
		'data-full-date' => 'true',
	))) . '
			', array(
		'label' => 'Date',
	)) . '
			';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['entry']['Product'], 'empty', array()) AND ($__templater->func('count', array($__vars['entry']['Product']['product_versions'], ), false) > 1)) {
		$__compilerTemp1 .= '
					(' . $__templater->escape($__templater->method($__vars['entry']['Product'], 'getVersionLabel', array($__vars['entry']['product_version'], ))) . ')
				';
	}
	$__finalCompiled .= $__templater->formRow('
				' . ($__templater->escape($__vars['entry']['Download']['title']) ?: 'Unknown product') . '
				' . $__compilerTemp1 . '
				<div class="u-muted">' . $__templater->escape($__vars['license']['license_key']) . '</div>
			', array(
		'label' => 'Download',
	)) . '

			';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
					' . $__templater->callMacro('public:custom_fields_macros', 'custom_fields_view', array(
		'type' => 'dbtechEcommerceLicenses',
		'group' => null,
		'set' => ($__vars['entry']['license_fields'] ?: $__vars['license']['license_fields']),
		'valueClass' => 'formRow',
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
				<hr class="formRowSep" />
				' . $__compilerTemp2 . '
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);