<?php
// FROM HASH: 904489790f895759872f3a6875aadfc9
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
			'value' => 'localhost',
			'placeholder' => '$config[\'db\'][\'host\']',
		), array(
			'label' => 'MySQL server',
		)) . '
    ' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][port]',
			'value' => '3306',
			'placeholder' => '$config[\'db\'][\'port\']',
		), array(
			'label' => 'MySQL port',
		)) . '
    ' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][username]',
			'placeholder' => '$config[\'db\'][\'username\']',
		), array(
			'label' => 'MySQL username',
		)) . '
    ' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][password]',
			'autocomplete' => 'off',
			'placeholder' => '$config[\'db\'][\'password\']',
		), array(
			'label' => 'MySQL password',
		)) . '
    ' . $__templater->formTextBoxRow(array(
			'name' => 'config[db][dbname]',
			'placeholder' => '$config[\'db\'][\'dbname\']',
		), array(
			'label' => 'MySQL database name',
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
	if (!$__vars['baseConfig']['data_dir']) {
		$__finalCompiled .= '
    <hr class="formRowSep" />

    ' . $__templater->formTextBoxRow(array(
			'name' => 'config[data_dir]',
			'value' => $__vars['baseConfig']['suggestion_data_dir'],
			'placeholder' => '$config[\'externalDataPath\']',
		), array(
			'label' => 'Data directory',
		)) . '
    ';
	} else {
		$__finalCompiled .= '
    ' . $__templater->formRow($__templater->escape($__vars['fullConfig']['data_dir']), array(
			'label' => 'Data directory',
		)) . '
';
	}
	$__finalCompiled .= '
';
	if (!$__vars['baseConfig']['internal_data_dir']) {
		$__finalCompiled .= '
    ' . $__templater->formTextBoxRow(array(
			'name' => 'config[internal_data_dir]',
			'value' => $__vars['baseConfig']['suggestion_internal_data_dir'],
			'placeholder' => '$config[\'internalDataPath\']',
		), array(
			'label' => 'Internal data directory',
		)) . '
    ';
	} else {
		$__finalCompiled .= '
    ' . $__templater->formRow($__templater->escape($__vars['fullConfig']['internal_data_dir']), array(
			'label' => 'Internal data directory',
		)) . '
';
	}
	$__finalCompiled .= '
';
	if (!$__vars['baseConfig']['old_version_id']) {
		$__finalCompiled .= '
    ' . $__templater->formRadioRow(array(
			'name' => 'config[old_version_id]',
			'value' => '2.9.1a',
		), array(array(
			'value' => '2.5.5',
			'label' => '2.5.5',
			'_type' => 'option',
		),
		array(
			'value' => '2.9.1a',
			'label' => '2.9.1a',
			'_type' => 'option',
		)), array(
			'label' => 'Your current add-on version in XenForo 1.x',
		)) . '
';
	} else {
		$__finalCompiled .= '
    ' . $__templater->formRow($__templater->escape($__vars['fullConfig']['old_version_id']), array(
			'label' => 'Your current add-on version in XenForo 1.x',
		)) . '
';
	}
	return $__finalCompiled;
}
);