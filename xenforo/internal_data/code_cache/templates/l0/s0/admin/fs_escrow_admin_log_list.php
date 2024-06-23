<?php
// FROM HASH: 2776b144feb03cb4849d7fa260c2b9b5
return array(
'macros' => array('logs_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'logs' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
		' . $__templater->dataRow(array(
		'rowtype' => 'header',
	), array(array(
		'_type' => 'cell',
		'html' => ' ' . 'Type' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Date Time' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'username' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Amount' . ' ',
	))) . '
	
<set var="$sum" value="0"></set>
	';
	if ($__templater->isTraversable($__vars['logs'])) {
		foreach ($__vars['logs'] AS $__vars['log']) {
			$__finalCompiled .= '
		' . $__templater->dataRow(array(
			), array(array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['log']['transaction_type']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->func('date_dynamic', array($__vars['log']['created_at'], array(
			))),
			),
			array(
				'_type' => 'cell',
				'html' => '<a  href="' . $__templater->func('link_type', array('public', 'members/', $__vars['log']['User'], ), true) . '" >' . $__templater->escape($__vars['log']['User']['username']) . '</a>',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . '$' . $__templater->escape($__templater->method($__vars['log'], 'getOrignolAmount', array())) . ' ',
			))) . '
		
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
        data-href="' . $__templater->func('link', array('escrow/refine-transaction', null, $__vars['conditions'], ), true) . '"
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Logs');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '
<div class="block-container">
	' . $__templater->callMacro(null, 'search_menu', array(
		'conditions' => $__vars['conditions'],
	), $__vars) . '
        ';
	if ($__templater->func('count', array($__vars['logs'], ), false) > 0) {
		$__finalCompiled .= '
          <div class="block-body">
            ' . $__templater->dataList('
              ' . $__templater->callMacro(null, 'logs_table_list', array(
			'logs' => $__vars['logs'],
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
        <dt><span class="block-footer-counter">' . $__templater->func('display_totals', array($__vars['logs'], $__vars['total'], ), true) . '</span></dt>
        <dd>
       ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'escrow/log',
		'data' => $__vars['logs'],
		'params' => $__vars['logs'],
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