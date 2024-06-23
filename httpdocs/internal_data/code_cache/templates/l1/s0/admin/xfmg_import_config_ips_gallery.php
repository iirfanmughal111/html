<?php
// FROM HASH: f2577e6e35a6070b23d98f39d9ceb988
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<h3 class="block-formSectionHeader">
	<span class="block-formSectionHeader-aligner">
		' . 'Source database configuration' . '
	</span>
</h3>

' . $__templater->callMacro('import_config_macros', 'db_host', array(
		'value' => $__vars['db']['host'],
		'placeholder' => '$INFO[\'sql_host\']',
	), $__vars) . '

' . $__templater->callMacro('import_config_macros', 'db_dbname', array(
		'value' => $__vars['db']['dbname'],
		'placeholder' => '$INFO[\'sql_database\']',
	), $__vars) . '

' . $__templater->callMacro('import_config_macros', 'db_username', array(
		'value' => $__vars['db']['username'],
		'placeholder' => '$INFO[\'sql_user\']',
	), $__vars) . '

' . $__templater->callMacro('import_config_macros', 'db_password', array(
		'value' => $__vars['db']['password'],
		'placeholder' => '$INFO[\'sql_pass\']',
	), $__vars) . '

' . $__templater->callMacro('import_config_macros', 'db_port', array(
		'value' => $__vars['db']['port'],
		'placeholder' => '$INFO[\'sql_port\']',
	), $__vars) . '

' . $__templater->callMacro('import_config_macros', 'db_tablePrefix', array(
		'value' => $__vars['db']['tablePrefix'],
		'placeholder' => '$INFO[\'sql_tbl_prefix\']',
	), $__vars) . '

<hr class="formRowSep" />

' . $__templater->formTextBoxRow(array(
		'name' => 'config[ips_path]',
		'required' => 'required',
	), array(
		'label' => 'Path to Invision Community root',
		'explain' => 'Provide the path to your Invision Community root directory, or the directory which contains your <code>uploads</code> directory.',
	)) . '

' . $__templater->formTextBoxRow(array(
		'name' => 'config[forum_import_log]',
		'required' => 'required',
	), array(
		'label' => 'Forum import log',
		'explain' => '
		' . 'You must provide the name of the import log that was generated when the forum was imported.' . '
	',
	));
	return $__finalCompiled;
}
);