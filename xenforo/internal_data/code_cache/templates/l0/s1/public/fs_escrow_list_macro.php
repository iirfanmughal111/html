<?php
// FROM HASH: 46cb62b04c25954d121409564587f3f6
return array(
'macros' => array('listing' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'listing' => '!',
		'type' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '
	';
	$__templater->includeCss('fs_escrow_list_view.less');
	$__finalCompiled .= '
	<div class="structItem structItem--listing js-inlineModContainer " id="escrow-' . $__templater->escape($__vars['listing']['escrow_id']) . '" data-author="' . ($__templater->escape($__vars['listing']['Thread']['username']) ?: '') . '">
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconExpanded structItem-cell--iconListingCoverImage">
			<div class="structItem-iconContainer">
				';
	if ($__templater->func('count', array($__vars['listing']['Thread']['FirstPost']['Attachments'], ), false)) {
		$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('threads', $__vars['listing']['Thread'], ), true) . '" class="" data-tp-primary="on">
							<img src ="' . $__templater->func('link', array('full:attachments', $__templater->method($__vars['listing']['Thread']['FirstPost']['Attachments'], 'first', array()), ), true) . '" style="min-height: 92px; max-height: 92px;"></a>
				';
	} else {
		$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('threads', $__vars['listing']['Thread'], ), true) . '" class="" data-tp-primary="on">
							' . $__templater->func('avatar', array($__vars['listing']['Thread']['User'], 'o', false, array(
			'style' => 'width: 92px; height: 92px;',
			'defaultname' => $__vars['listing']['Thread']['User']['username'],
		))) . '</a>
				';
	}
	$__finalCompiled .= '		
			</div>
		</div>
		<div class="structItem-cell structItem-cell--main" data-xf-init="touch-proxy">
			<div class="structItem-title">
				<a href="' . $__templater->func('link', array('threads/', $__vars['listing']['Thread'], ), true) . '" class="" data-tp-primary="on">' . $__templater->escape($__vars['listing']['Thread']['title']) . '</a>
					' . '
						</div>
			<div class="structItem-minor">

					<ul class="structItem-parts">
						<li>' . $__templater->func('username_link', array($__vars['listing']['Thread']['User'], false, array(
		'defaultname' => $__vars['listing']['Thread']['User'],
	))) . '</li>
						<li class="structItem-startDate">
							' . $__templater->func('date_dynamic', array($__vars['listing']['Thread']['post_date'], array(
	))) . ' 
						</li>
				</ul>
			</div>
			
		<div class="auction-category">' . $__templater->func('snippet', array($__vars['listing']['Thread']['FirstPost']['message'], 100, array('stripBbCode' => true, ), ), true) . '</div>
		
		</div>
		<div class="structItem-cell structItem-cell--listingMeta">

			<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--type">
				<dt ><b>' . 'Status' . '</b></dt>
				<dd>
					' . $__templater->callMacro(null, 'status', array(
		'status' => $__vars['listing']['escrow_status'],
	), $__vars) . '
				</dd>
			</dl>
					
			<dl style="margin-top:5px;" class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
				<dt>
					<b>' . 'Amount' . '</b>
					</dt>
				<dd>
					' . '$' . $__templater->escape($__templater->method($__vars['listing'], 'getOrignolAmount', array())) . '
				</dd>
			</dl>
			<dl style="margin-top:5px;" class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
				<dt>
					<b>' . 'Starter' . '</b>
					</dt>
				<dd>
					' . $__templater->escape($__vars['listing']['Thread']['User']['username']) . '
				</dd>
			</dl>
			
			<dl style="margin-top:5px;" class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--expiration">
				<dt>
					<b>' . 'Mentioned' . '</b>
					</dt>
				<dd>
					' . $__templater->escape($__vars['listing']['User']['username']) . '
				</dd>
			</dl>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'escrow' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'escrow' => '!',
		'type' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	';
	if ($__vars['escrow'] AND ($__vars['escrow']['Thread']['escrow_id'] != 0)) {
		$__finalCompiled .= '
		<div class="block-row block-row--separated  js-inlineModContainer" >
			<div class="contentRow ">
				<span class="contentRow-figure">
					' . $__templater->func('avatar', array($__vars['escrow']['Thread']['User'], 's', false, array(
			'defaultname' => $__vars['escrow']['Thread']['User']['username'],
		))) . '
				</span>
				<div class="contentRow-main">
					<h3 class="contentRow-title">
						<a href="' . $__templater->func('link', array('threads', $__vars['escrow']['Thread'], ), true) . '">' . $__templater->escape($__vars['escrow']['Thread']['title']) . '</a>
					</h3>
					<div class="contentRow-minor contentRow-minor--hideLinks">
						<ul class="listInline listInline--bullet">
							<li>
								';
		if ($__vars['type'] == 'my') {
			$__finalCompiled .= '
									' . $__templater->func('username_link', array($__vars['escrow']['User'], false, array(
				'defaultname' => $__vars['escrow']['User']['username'],
			))) . '
									';
		} else if ($__vars['type'] == 'mentioned') {
			$__finalCompiled .= '
									' . $__templater->func('username_link', array($__vars['escrow']['Thread']['User'], false, array(
				'defaultname' => $__vars['escrow']['Thread']['User']['username'],
			))) . '
									
								';
		}
		$__finalCompiled .= '
							</li>
							<li>' . $__templater->func('date_dynamic', array($__vars['escrow']['Thread']['post_date'], array(
		))) . '</li>
							<li>' . 'Amount' . $__vars['xf']['language']['label_separator'] . ' ' . '$' . $__templater->filter($__vars['escrow']['escrow_amount'], array(array('number', array()),), true) . '</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '		
	
';
	return $__finalCompiled;
}
),
'logs_table_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'logs' => '!',
		'beforeId' => '',
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
		'html' => ' ' . 'Amount' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Balance' . ' ',
	),
	array(
		'_type' => 'cell',
		'html' => ' ' . 'Action' . ' ',
	))) . '
	

	';
	if ($__templater->isTraversable($__vars['logs'])) {
		foreach ($__vars['logs'] AS $__vars['log']) {
			$__finalCompiled .= '
		';
			$__compilerTemp1 = array(array(
				'_type' => 'cell',
				'html' => ' ' . $__templater->escape($__vars['log']['transaction_type']) . ' ',
			)
,array(
				'_type' => 'cell',
				'html' => $__templater->func('date_dynamic', array($__vars['log']['created_at'], array(
			))),
			)
,array(
				'_type' => 'cell',
				'html' => ' ' . '$' . $__templater->escape($__templater->method($__vars['log'], 'getOrignolAmount', array())) . ' ',
			)
,array(
				'_type' => 'cell',
				'html' => '$' . $__templater->escape($__templater->method($__vars['log'], 'getOrignolBalance', array())),
			));
			if ($__vars['log']['conversation_id']) {
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => '<a href="' . $__templater->func('link', array('conversations', $__vars['log']['Conversation'], ), true) . '">' . 'Conversation' . '</a>',
				);
			} else {
				$__compilerTemp1[] = array(
					'_type' => 'cell',
					'html' => '<a href="' . $__templater->func('link', array('members/trans-report', $__vars['null'], array('id' => $__vars['log']['transaction_id'], ), ), true) . '">' . 'Report' . ' </a>',
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
'fs_escrow_Count' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'escrowsCount' => '!',
		'isSelected' => 'all',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Overview' . '</h3>
			<div class="block-body">
				';
	if ($__vars['escrowsCount']) {
		$__finalCompiled .= '
							
					<ol class="categoryList toggleTarget is-active">
		';
		$__vars['isSelected'] = $__vars['isSelected'];
		$__finalCompiled .= '
		';
		if ($__templater->isTraversable($__vars['escrowsCount'])) {
			foreach ($__vars['escrowsCount'] AS $__vars['id'] => $__vars['escrow']) {
				$__finalCompiled .= '

			<li class="categoryList-item">
		<div class="categoryList-itemRow">
			<div class="categoryList-link" style="color:#2577b1;">
				<a href="' . $__templater->func('link', array('escrow&type=' . $__vars['escrow']['type'], ), true) . '" class="categoryList-link' . (($__vars['isSelected'] == $__vars['escrow']['type']) ? ' is-selected' : '') . '">
				' . $__templater->escape($__vars['escrow']['title']) . '
			</a>
				
			</div>
			<span class="categoryList-label">
				<span class="label label--subtle label--smallest">' . $__templater->escape($__vars['escrow']['count']) . '</span>
			</span>
		</div>
	</li>
		
		';
			}
		}
		$__finalCompiled .= '
	</ol>
				';
	} else {
		$__finalCompiled .= '
					<div class="block-row">' . 'N/A' . '</div>
				';
	}
	$__finalCompiled .= '
				
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'fs_escrow_stats' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'stats' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Escrow Stats' . '</h3>
			<div class="block-body">
				';
	if ($__vars['stats']) {
		$__finalCompiled .= '
							
					<ol class="categoryList toggleTarget is-active">

		';
		if ($__templater->isTraversable($__vars['stats'])) {
			foreach ($__vars['stats'] AS $__vars['id'] => $__vars['category']) {
				$__finalCompiled .= '
			
			<li class="categoryList-item">
		<div class="categoryList-itemRow">
			<div class="categoryList-link" style="color:#2577b1;">
				' . $__templater->escape($__vars['category']['title']) . '
			</div>
			<span class="categoryList-label">
				<span class="label label--subtle label--smallest">' . $__templater->escape($__vars['category']['count']) . '</span>
			</span>
		</div>
	</li>
		
		';
			}
		}
		$__finalCompiled .= '
	</ol>
				';
	} else {
		$__finalCompiled .= '
					<div class="block-row">' . 'N/A' . '</div>
				';
	}
	$__finalCompiled .= '
				
			</div>
		</div>
	</div>
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
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

' . '	



<!-- placingProperTableCellTemplate -->

' . '

' . '
' . '



';
	return $__finalCompiled;
}
);