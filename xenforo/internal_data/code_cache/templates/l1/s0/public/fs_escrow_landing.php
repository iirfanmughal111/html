<?php
// FROM HASH: 743c4dff2bc8850d7d848888bcf1885d
return array(
'macros' => array('search_menu' => array(
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Escrow');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '
		' . $__templater->callMacro('notice_macros', 'notice_list', array(
		'type' => 'block',
		'notices' => $__vars['rules'],
	), $__vars) . '

<div class="block-container">
    <div class="block-body">
		';
	$__compilerTemp1 = '';
	if ($__vars['xf']['visitor']['user_id'] != 0) {
		$__compilerTemp1 .= '
				 ' . $__templater->button('Start Escrow', array(
			'href' => $__templater->func('link', array('escrow/add', ), false),
			'class' => 'button--cta',
			'icon' => 'write',
		), '', array(
		)) . '
			';
	}
	$__templater->pageParams['pageAction'] = $__templater->preEscaped('
			' . $__compilerTemp1 . '
		');
	$__finalCompiled .= '
		
		<!--start --->
	
		';
	if ($__vars['xf']['visitor']['user_id'] != 0) {
		$__finalCompiled .= '
    	' . $__templater->callMacro(null, 'search_menu', array(
			'conditions' => $__vars['conditions'],
		), $__vars) . '
				<div class="structItemContainer">
					';
		if (!$__templater->test($__vars['escrows'], 'empty', array())) {
			$__finalCompiled .= '
						';
			if ($__templater->isTraversable($__vars['escrows'])) {
				foreach ($__vars['escrows'] AS $__vars['escrow']) {
					$__finalCompiled .= '
							';
					if ($__vars['escrow']['Thread']) {
						$__finalCompiled .= '
								  ' . $__templater->callMacro('fs_escrow_list_macro', 'listing', array(
							'listing' => $__vars['escrow'],
							'type' => '',
						), $__vars) . '
							';
					}
					$__finalCompiled .= '
						';
				}
			}
			$__finalCompiled .= '
					';
		} else {
			$__finalCompiled .= '
					  <div class="block-row">
						' . 'No reviews Found' . '
					  </div>
					';
		}
		$__finalCompiled .= '
			  </div>
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
			'params' => $__vars['conditions'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
						  </dd>
					  </dl>
					</div>
			  </div>
		
			';
	} else {
		$__finalCompiled .= '
				<div class="blockMessage">' . 'Sorry, you need to login First...!' . '</div>
				
			';
	}
	$__finalCompiled .= '
		';
	$__templater->modifySideNavHtml(null, '
			' . $__templater->callMacro('fs_escrow_list_macro', 'fs_escrow_Count', array(
		'escrowsCount' => $__vars['escrowsCount'],
		'isSelected' => $__vars['isSelected']['type'],
	), $__vars) . '

			' . $__templater->callMacro('fs_escrow_list_macro', 'fs_escrow_stats', array(
		'stats' => $__vars['stats'],
	), $__vars) . '
		', 'replace');
	$__finalCompiled .= '
	
				

		
		
		
		
		
		
		
		
		
		
<!-- Filter Bar Macro Start -->
' . '
		

		<!---end --->
    
    </div>
 </div>';
	return $__finalCompiled;
}
);