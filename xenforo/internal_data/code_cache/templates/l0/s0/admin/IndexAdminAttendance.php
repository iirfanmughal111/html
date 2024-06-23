<?php
// FROM HASH: fc8a1c172aaa87040f4206ad6c51132e
return array(
'macros' => array('attendance_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'attendance' => $__vars['attendance'],
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  ' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Usernmae' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Date' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Office Time' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Office Leaving Time' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Comments' . ' ',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => 'View ',
	))) . '
  ';
	if ($__templater->isTraversable($__vars['attendance'])) {
		foreach ($__vars['attendance'] AS $__vars['attend']) {
			$__finalCompiled .= '
    ' . $__templater->dataRow(array(
			), array(array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['attend']['User']['username']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->func('date', array($__vars['attend']['date'], ), true) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->func('time', array($__vars['attend']['in_time'], ), true) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->func('time', array($__vars['attend']['out_time'], ), true) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => '
        ' . (($__vars['attend']['comment'] != '') ? $__templater->func('snippet', array($__vars['attend']['comment'], 30, array('stripBbCode' => true, ), ), true) : 'No special Comment') . '
      ',
			),
			array(
				'overlay' => 'true',
				'ajax' => 'true',
				'href' => $__templater->func('link', array('attendance/view', $__vars['attend'], ), false),
				'_type' => 'action',
				'html' => 'View ',
			))) . '
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Attendance Management System');
	$__finalCompiled .= '

<div class="block-outer">

  ';
	$__compilerTemp1 = array();
	if ($__templater->isTraversable($__vars['users'])) {
		foreach ($__vars['users'] AS $__vars['user']) {
			$__compilerTemp1[] = array(
				'value' => $__vars['user']['user_id'],
				'label' => $__templater->escape($__vars['user']['username']),
				'_type' => 'option',
			);
		}
	}
	$__finalCompiled .= $__templater->form('

    ' . $__templater->formSelectRow(array(
		'name' => 'employee_user_id',
		'required' => 'required',
	), $__compilerTemp1, array(
		'label' => 'Usernmae',
		'hint' => 'Required',
	)) . '
  

  ' . $__templater->formSubmitRow(array(
		'icon' => 'search',
		'sticky' => 'true',
	), array(
	)) . '
', array(
		'action' => $__templater->func('link', array('attendance', ), false),
		'novalidate' => 'novalidate',
	)) . '
  
</div>

</div>

<div class="block-container">
  <div class="block-body">
    <div class="block-footer">
      <div class="block-body block-row">
        <dl class="pairs pairs--justified">
          <dt>
            <span class="block-footer-counter"
              >' . $__templater->func('display_totals', array($__vars['attendance'], $__vars['total'], ), true) . '</span
            >
          </dt>
          <dd>
            ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'attendance',
		'data' => $__vars['attendance'],
		'params' => $__vars['attendance'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
          </dd>
        </dl>
      </div>
    </div>
  </div>
    ';
	$__compilerTemp2 = '';
	if ($__vars['attendance'] != null) {
		$__compilerTemp2 .= '
          <div class="block-body">
            ' . $__templater->dataList('
              ' . $__templater->callMacro(null, 'attendance_table_list', array(
			'attendance' => $__vars['attendance'],
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
		'class' => ' ',
		'ajax' => 'true',
	)) . '
  </div>
  <!-- params="76" -->

  <div class="block-footer">
    <div class="block-body block-row">
      <dl class="pairs pairs--justified">
        <dt>
          <span class="block-footer-counter"
            >' . $__templater->func('display_totals', array($__vars['attendance'], $__vars['total'], ), true) . '</span
          >
        </dt>
        <dd>
          ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'attendance',
		'data' => $__vars['attendance'],
		'params' => $__vars['attendance'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
        </dd>
      </dl>
    </div>
  </div>
</div>

<!-- placingProperTableCellTemplate -->

' . '
';
	return $__finalCompiled;
}
);