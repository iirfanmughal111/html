<?php
// FROM HASH: 07dc36d4b8d51005460de3682bc12f38
return array(
'macros' => array('dropdown_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'dropdownReplys' => $__vars['dropdownReplys'],
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  ' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Thread Title' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Status' . ' ',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => '',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => '',
	))) . '

  ' . $__templater->dataRow(array(
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . $__templater->escape($__vars['dropdownReplys']['title']) . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => '
      ' . (($__vars['dropdownReplys']['is_dropdown_active'] == 0) ? 'Inactive' : 'Active') . '
    ',
	),
	array(
		'href' => $__templater->func('link', array('opt-reply/edit', $__vars['dropdownReplys'], ), false),
		'_type' => 'action',
		'html' => 'Edit',
	),
	array(
		'href' => $__templater->func('link', array('opt-reply/delete', $__vars['dropdownReplys'], ), false),
		'overlay' => 'true',
		'_type' => 'delete',
		'html' => '',
	))) . '
';
	return $__finalCompiled;
}
),
'dropdownOptions_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'dropdownOptionData' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  ' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Option List' . ' ',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => '',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => '',
	))) . '
  ';
	if ($__templater->isTraversable($__vars['dropdownOptionData']['dropdwon_options'])) {
		foreach ($__vars['dropdownOptionData']['dropdwon_options'] AS $__vars['index'] => $__vars['option']) {
			$__finalCompiled .= '
    ';
			if ($__vars['option']) {
				$__finalCompiled .= '
      ' . $__templater->dataRow(array(
				), array(array(
					'_type' => 'cell',
					'html' => ' ' . $__templater->func('snippet', array($__vars['option'], 10, array('stripBbCode' => true, ), ), true) . ' ',
				),
				array(
					'href' => $__templater->func('link', array('opt-reply/edit-single', $__vars['dropdownOptionData'], array('id' => $__vars['index'], ), ), false),
					'_type' => 'action',
					'html' => 'Edit',
				),
				array(
					'href' => $__templater->func('link', array('opt-reply/delete-single', $__vars['dropdownOptionData'], array('id' => $__vars['index'], ), ), false),
					'_type' => 'delete',
					'html' => '',
				))) . '
    ';
			}
			$__finalCompiled .= '
  ';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Dropdown List');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['dropdownReplys'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['dropdownReplys']['dropdwon_options'] != null) {
		$__compilerTemp1 .= '
    ' . $__templater->button('Delete Dropdown', array(
			'href' => $__templater->func('link', array('opt-reply/delete', $__vars['dropdownReplys'], ), false),
			'icon' => 'delete',
			'overlay' => 'true',
			'ajax' => 'true',
		), '', array(
		)) . '
    ';
	} else {
		$__compilerTemp1 .= '

    ' . $__templater->button('Dropdown Reply', array(
			'href' => $__templater->func('link', array('opt-reply/add', $__vars['dropdownReplys'], ), false),
			'icon' => 'add',
		), '', array(
		)) . '
  ';
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
  ' . $__compilerTemp1 . '
');
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	if ($__vars['dropdownReplys']['dropdwon_options'] != null) {
		$__compilerTemp2 .= '
      <div class="block-body">
        ' . $__templater->dataList('
          ' . $__templater->callMacro(null, 'dropdown_table_list', array(
			'usergroupData' => $__vars['dropdownReplys'],
		), $__vars) . '
        ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
      </div>

      ';
	} else {
		$__compilerTemp2 .= '
      <div class="block-body block-row">
        ' . 'No items have been created yet.' . '
      </div>
    ';
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-container">
    ' . $__compilerTemp2 . '
  </div>
', array(
		'action' => $__templater->func('link', array($__vars['prefix'] . '/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
' . '

<!--Options-->

';
	$__compilerTemp3 = '';
	if ($__vars['dropdownReplys']['dropdwon_options'] != null) {
		$__compilerTemp3 .= '
      <div class="block-body">
        ' . $__templater->dataList('
          ' . $__templater->callMacro(null, 'dropdownOptions_table_list', array(
			'dropdownOptionData' => $__vars['dropdownReplys'],
		), $__vars) . '
        ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
      </div>

    ';
	}
	$__finalCompiled .= $__templater->form('
  <div class="block-container">
    ' . $__compilerTemp3 . '
  </div>
', array(
		'action' => $__templater->func('link', array($__vars['prefix'] . '/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '

' . '
';
	return $__finalCompiled;
}
);