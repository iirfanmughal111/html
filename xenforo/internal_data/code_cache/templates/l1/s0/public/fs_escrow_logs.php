<?php
// FROM HASH: 34da248171d62d9cea4f26a4c8008842
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="block-container">
  <div class="block-body">
    ';
	$__compilerTemp1 = '';
	if (!$__templater->test($__vars['logs'], 'empty', array())) {
		$__compilerTemp1 .= '
			<div class="block-body js-escrowLogTarget">
            ' . $__templater->dataList('
		
              ' . $__templater->callMacro('fs_escrow_list_macro', 'logs_table_list', array(
			'logs' => $__vars['logs'],
			'beforeId' => $__vars['beforeId'],
		), $__vars) . '
            ', array(
			'data-xf-init' => 'responsive-data-list',
		)) . '
				</div>
				<div class="block-footer js-logLoadMore">
					<span class="block-footer-controls">' . $__templater->button('
							' . 'Show older items' . '
						', array(
			'href' => $__templater->func('link', array('members/logs', $__vars['user'], array('before_id' => $__vars['oldestItemId'], ), ), false),
			'rel' => 'nofollow',
			'data-xf-click' => 'inserter',
			'data-append' => '.js-escrowLogTarget',
			'data-replace' => '.js-logLoadMore',
		), '', array(
		)) . '</span>
				</div>
				
			';
	} else if ($__vars['beforeId']) {
		$__compilerTemp1 .= '
				<div class="block-body js-logLoadMore">
					<div class="block-row block-row--separated">' . 'There are no more items to show.' . '</div>
				</div>
			';
	} else {
		$__compilerTemp1 .= '
				<div class="block-body js-logLoadMore ">
					<div class="block-row">' . 'No reviews Found' . '</div>
				</div>
			';
	}
	$__finalCompiled .= $__templater->form('
		
      <div class="block-container">
        <!--start-->
		' . $__compilerTemp1 . '
		<!--end-->
      </div>
    ', array(
		'action' => $__templater->func('link', array($__vars['prefix'] . '/toggle', ), false),
		'class' => 'block',
		'ajax' => 'true',
	)) . '
  </div>';
	return $__finalCompiled;
}
);