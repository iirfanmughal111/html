<?php
// FROM HASH: fcd7d516e299027a74225fcd845fa6b4
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
		'html' => 'Edit',
	),
	array(
		'class' => 'dataList-cell--min',
		'_type' => 'cell',
		'html' => 'Delete',
	))) . '
  ';
	if ($__templater->isTraversable($__vars['attendance'])) {
		foreach ($__vars['attendance'] AS $__vars['attend']) {
			$__finalCompiled .= '
    ' . $__templater->dataRow(array(
			), array(array(
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
				'href' => $__templater->func('link', array('attendance/edit', $__vars['attend'], ), false),
				'_type' => 'action',
				'html' => 'Edit',
			),
			array(
				'href' => $__templater->func('link', array('attendance/delete', $__vars['attend'], ), false),
				'overlay' => 'true',
				'_type' => 'delete',
				'html' => '',
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
';
	if ($__vars['xf']['visitor']['is_admin']) {
		$__finalCompiled .= '
  ';
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
    ' . $__templater->button('New Attendance', array(
			'icon' => 'add',
			'overlay' => 'true',
			'ajax' => 'true',
			'href' => $__templater->func('link', array('attendance/Add', ), false),
			'title' => 'New Attendance',
			'data-xf-init' => '_tooltip',
		), '', array(
		)) . '
  ');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block-container">
  <div class="block-body">
    ' . $__templater->dataList('
      ' . $__templater->dataRow(array(
		'rowclass' => ((($__vars['xf']['visittor']['user_state'] == 'valid') OR $__vars['xf']['visitor']['is_banned']) ? 'dataList-row--deleted' : ''),
	), array(array(
		'class' => 'dataList-cell--min dataList-cell--image dataList-cell--imageSmall',
		'_type' => 'cell',
		'html' => '
          ' . $__templater->func('avatar', array($__vars['xf']['visitor'], 's', false, array(
		'href' => '',
	))) . '
        ',
	),
	array(
		'label' => '
            ' . $__templater->func('username_link', array($__vars['xf']['visitor'], true, array(
		'notooltip' => 'true',
		'href' => '',
	))) . '
          ',
		'hint' => $__templater->escape($__vars['xf']['visitor']['email']),
		'_type' => 'main',
		'html' => '',
	))) . '
    ', array(
	)) . '
  </div>
</div>

<br />

<div class="block-container">
  <div class="block-body">
    ';
	$__compilerTemp1 = '';
	if ($__vars['attendance'] != null) {
		$__compilerTemp1 .= '
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
		$__compilerTemp1 .= '
          <div class="block-body block-row">
            ' . 'No items have been created yet.' . '
          </div>
        ';
	}
	$__finalCompiled .= $__templater->form('
      <div class="block-container">
        ' . $__compilerTemp1 . '
      </div>
    ', array(
		'action' => $__templater->func('link', array($__vars['prefix'] . '/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
  </div>
  <!-- params="76" -->

  <div class="block-footer">
  
    <div class="block-body block-row">
      <dl class="pairs pairs--justified">
        <dt><span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['attendance'], $__vars['total'], ), true) . '</span></dt>
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

';
	return $__finalCompiled;
}
);