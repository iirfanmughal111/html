<?php
// FROM HASH: 942586d51169e28cb16697ad52a1fd4c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<h3 class="block-formSectionHeader">' . 'Source database configuration' . '</h3>
';
	if (!$__vars['baseConfig']['db']['host']) {
		$__finalCompiled .= '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][host]',
			'value' => $__vars['db']['host'],
			'placeholder' => '$host',
		), array(
			'label' => 'MySQL server',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][port]',
			'value' => $__vars['db']['port'],
			'placeholder' => '$sqlport',
		), array(
			'label' => 'MySQL port',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][username]',
			'value' => $__vars['db']['username'],
			'placeholder' => '$mysql_user',
		), array(
			'label' => 'MySQL username',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][password]',
			'value' => $__vars['db']['password'],
			'autocomplete' => 'off',
			'placeholder' => '$mysql_password',
		), array(
			'label' => 'MySQL password',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][dbname]',
			'value' => $__vars['db']['dbname'],
			'placeholder' => '$database',
		), array(
			'label' => 'MySQL database name',
		)) . '
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][tablePrefix]',
			'value' => $__vars['db']['tablePrefix'],
			'placeholder' => '$pp_db_prefix',
		), array(
			'label' => 'MySQL table prefix',
		)) . '
	<hr class="formRowSep" />
	' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][charset]',
			'value' => $__vars['db']['charset'],
			'placeholder' => '$ppcharset',
		), array(
			'label' => 'Force character set',
			'explain' => 'If you specify a character set in the config for the system you are importing, you should specify the same character set here.',
		)) . '
';
	} else {
		$__finalCompiled .= '
	' . $__templater->formRow($__templater->escape($__vars['fullConfig']['db']['host']) . ':' . $__templater->escape($__vars['fullConfig']['db']['dbname']), array(
			'label' => 'MySQL server',
		)) . '
';
	}
	$__finalCompiled .= '

';
	if (!$__vars['baseConfig']['integration']) {
		$__finalCompiled .= '
	<hr class="formRowSep" />

	' . $__templater->formRadioRow(array(
			'name' => 'config[integration]',
		), array(array(
			'value' => 'internal',
			'label' => 'Internal',
			'data-hide' => 'true',
			'_dependent' => array($__templater->formCheckBox(array(
		), array(array(
			'value' => 'attempt_match',
			'selected' => true,
			'label' => 'Attempt to match Photopost username and email address to an existing user in this forum.',
			'_type' => 'option',
		)))),
			'afterhint' => 'Photopost was not integrated and user details were stored in Photopost itself. If there is no attempt to match to an existing user, or a user cannot be found, content will be attributed to a guest user.',
			'_type' => 'option',
		),
		array(
			'value' => 'forum',
			'label' => 'Forum integration',
			'data-hide' => 'true',
			'_dependent' => array($__templater->formTextBox(array(
			'name' => 'config[forum_import_log]',
			'required' => 'true',
			'placeholder' => 'Forum import log',
		))),
			'afterhint' => 'Photopost was integrated with other forum software (not XenForo) which has since been imported into this forum. A forum import log is required.',
			'_type' => 'option',
		),
		array(
			'value' => 'xenforo',
			'label' => 'XenForo integration',
			'data-hide' => 'true',
			'_dependent' => array($__templater->formTextBox(array(
			'name' => 'config[forum_import_log]',
			'placeholder' => 'Forum import log',
		))),
			'afterhint' => 'Photopost was integrated with a XenForo installation. If no forum import log is provided, it is assumed that the user IDs in Photopost match those in this forum.',
			'_type' => 'option',
		)), array(
			'label' => 'Photopost integration source',
		)) . '
';
	} else {
		$__finalCompiled .= '
	';
		$__compilerTemp1 = '';
		if ($__vars['baseConfig']['integration'] == 'internal') {
			$__compilerTemp1 .= '
			' . 'Internal' . '
		';
		} else if ($__vars['baseConfig']['integration'] == 'forum') {
			$__compilerTemp1 .= '
			' . 'Forum' . '
		';
		} else if ($__vars['baseConfig']['integration'] == 'xenforo') {
			$__compilerTemp1 .= '
			XenForo
		';
		}
		$__finalCompiled .= $__templater->formRow('
		' . $__compilerTemp1 . '
	', array(
			'label' => 'Photopost integration source',
		)) . '
';
	}
	return $__finalCompiled;
}
);