<?php
// FROM HASH: 8c3c03bf48cf63e5d9e7f0642e64aff0
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
		'html' => ' ' . 'Tx ID' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'sender' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'User' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Date Time' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Status' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Amount' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'USD' . ' ',
	))) . '
	
<set var="$sum" value="0"></set>
	';
	if ($__templater->isTraversable($__vars['logs'])) {
		foreach ($__vars['logs'] AS $__vars['log']) {
			$__finalCompiled .= '
		';
			$__compilerTemp1 = '';
			if ($__vars['log']['Status'] != 2) {
				$__compilerTemp1 .= '
					' . $__templater->escape($__vars['log']['FailReason']) . '
					';
			} else {
				$__compilerTemp1 .= '
					' . 'Received' . '
				';
			}
			$__finalCompiled .= $__templater->dataRow(array(
			), array(array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->func('snippet', array($__vars['log']['TxId'], 30, array('stripBbCode' => true, ), ), true) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' 
				' . $__templater->callMacro(null, 'sender_address', array(
				'addresses' => $__vars['log']['SenderAddresses'],
			), $__vars) . '
			',
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->escape($__vars['log']['ExternalId']),
			),
			array(
				'_type' => 'cell',
				'html' => $__templater->escape($__vars['log']['Date']),
			),
			array(
				'_type' => 'cell',
				'html' => '
				' . $__compilerTemp1 . '
			',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . '$' . $__templater->escape($__vars['log']['Amount']) . ' ',
			),
			array(
				'_type' => 'cell',
				'html' => ' ' . '$' . $__templater->escape($__vars['log']['AmountUSD']) . ' ',
			))) . '
		
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'sender_address' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'addresses' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	';
	if ($__templater->isTraversable($__vars['addresses'])) {
		foreach ($__vars['addresses'] AS $__vars['address']) {
			$__finalCompiled .= '
		<p>
			' . $__templater->escape($__vars['address']) . '
		</p>
		
		
	';
		}
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
        data-href="' . $__templater->func('link', array('escrow/refine-live', null, $__vars['conditions'], ), true) . '"
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Live Logs');
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
		'link' => 'escrow/live',
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



';
	return $__finalCompiled;
}
);