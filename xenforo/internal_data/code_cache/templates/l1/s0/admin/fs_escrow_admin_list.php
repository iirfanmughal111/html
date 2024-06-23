<?php
// FROM HASH: 6fa0df02719260f7db4c32eedc8604c1
return array(
'macros' => array('escrows_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'escrows' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

  ' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Title' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Starter' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Mentioned' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Amount' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Status' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Created at' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Approve/Cancel/Complete Date' . ' ',
	))) . '
  ';
	if ($__templater->isTraversable($__vars['escrows'])) {
		foreach ($__vars['escrows'] AS $__vars['escrow']) {
			$__finalCompiled .= '
    ';
			$__compilerTemp1 = array();
			if ($__vars['escrow']['Thread']) {
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => ' <a  href="' . $__templater->func('link_type', array('public', 'threads/', $__vars['escrow']['Thread'], ), true) . '" >' . $__templater->func('snippet', array($__vars['escrow']['Thread']['title'], 35, array('stripBbCode' => true, ), ), true) . '</a>  ',
				);
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => ' <a  href="' . $__templater->func('link_type', array('public', 'members/', $__vars['escrow']['Thread']['User'], ), true) . '" >' . $__templater->escape($__vars['escrow']['Thread']['User']['username']) . ' </a>',
				);
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => ' <a  href="' . $__templater->func('link_type', array('public', 'members/', $__vars['escrow']['User'], ), true) . '" > ' . $__templater->escape($__vars['escrow']['User']['username']) . ' </a>',
				);
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => '$' . $__templater->escape($__templater->method($__vars['escrow'], 'getOrignolAmount', array())),
				);
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => '
		  ' . $__templater->callMacro(null, 'status', array(
					'status' => $__vars['escrow']['escrow_status'],
				), $__vars) . '
		',
				);
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => $__templater->func('date_dynamic', array($__vars['escrow']['Thread']['post_date'], array(
				))),
				);
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => $__templater->func('date_dynamic', array($__vars['escrow']['last_update'], array(
				))),
				);
			}
			$__finalCompiled .= $__templater->dataRow(array(
			), $__compilerTemp1) . '
  ';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'status' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'status' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
		';
	if ($__vars['status'] == 0) {
		$__finalCompiled .= '
			' . 'Waiting for approvel' . '
		';
	} else if ($__vars['status'] == 1) {
		$__finalCompiled .= '
			' . 'Aproved. Processing' . '
		';
	} else if ($__vars['status'] == 2) {
		$__finalCompiled .= '
			' . 'Cancelled by mentioned User' . '
		';
	} else if ($__vars['status'] == 3) {
		$__finalCompiled .= '
			' . 'Cancelled by Creator' . '
		';
	} else if ($__vars['status'] == 4) {
		$__finalCompiled .= '
			' . 'Completed' . '
		';
	} else {
		$__finalCompiled .= '
			' . 'fs_escrow_status_undefined' . '
		';
	}
	$__finalCompiled .= '
	';
	return $__finalCompiled;
}
),
'search_menu' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'conditions' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
  <div class="block-filterBar">
    <div class="filterBar">
      <a
        class="filterBar-menuTrigger"
        data-xf-click="menu"
        role="button"
        tabindex="0"
        aria-expanded="false"
        aria-haspopup="true"
        >' . 'Filters' . '</a
      >
      <div
        class="menu menu--wide"
        data-menu="menu"
        aria-hidden="true"
        data-href="' . $__templater->func('link', array('escrow/refine-search', null, $__vars['conditions'], ), true) . '"
        data-load-target=".js-filterMenuBody"
      >
        <div class="menu-content">
          <h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
          <div class="js-filterMenuBody">
            <div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
          </div>
        </div>
      </div>
    </div>
  </div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Escrows');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '
<div class="block-container">
	' . $__templater->callMacro(null, 'search_menu', array(
		'conditions' => $__vars['conditions'],
	), $__vars) . '
        ';
	if ($__templater->func('count', array($__vars['escrows'], ), false) > 0) {
		$__finalCompiled .= '
          <div class="block-body">
            ' . $__templater->dataList('
              ' . $__templater->callMacro(null, 'escrows_table_list', array(
			'escrows' => $__vars['escrows'],
		), $__vars) . '
            ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
          </div>

          ';
	} else {
		$__finalCompiled .= '
          <div class="block-body block-row">
            ' . 'No reviews Found' . '
          </div>
        ';
	}
	$__finalCompiled .= '
	<div class="block-footer">
  
    <div class="block-body block-row">
      <dl class="pairs pairs--justified">
        <dt><span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['escrows'], $__vars['total'], ), true) . '</span></dt>
        <dd>
       ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'escrow',
		'data' => $__vars['escrows'],
		'params' => $__vars['escrows'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
      </dd>
      </dl>
      </div>
  </div>
      </div>

' . '

' . '

<!-- Filter Bar Macro Start -->

';
	return $__finalCompiled;
}
);