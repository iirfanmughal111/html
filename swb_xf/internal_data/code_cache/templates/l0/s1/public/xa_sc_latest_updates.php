<?php
// FROM HASH: 6ede38630045dd9f6eb77219b85c2b1b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Latest updates');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), ));
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

';
	if ($__vars['canInlineModUpdates']) {
		$__finalCompiled .= '
	';
		$__templater->includeJs(array(
			'src' => 'xf/inline_mod.js',
			'min' => '1',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

<div class="block block--messages"
	data-xf-init="' . ($__vars['canInlineModUpdates'] ? 'inline-mod' : '') . '"
	data-type="sc_update"
	data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">

	<div class="block-outer">';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['canInlineModUpdates']) {
		$__compilerTemp2 .= '
							' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
						';
	}
	$__compilerTemp2 .= '
					';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
			<div class="block-outer-opposite">
				<div class="buttonGroup">
					' . $__compilerTemp2 . '
				</div>
			</div>
		';
	}
	$__finalCompiled .= $__templater->func('trim', array('
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/latest-updates',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp1 . '
	'), false) . '</div>

	<div class="block-container"
		data-xf-init="lightbox"
		data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

		';
	$__vars['dateLimits'] = array('-1' => 'Any time', '7' => '' . '7' . ' days', '14' => '' . '14' . ' days', '30' => '' . '30' . ' days', '60' => '' . '2' . ' months', '90' => '' . '3' . ' months', '182' => '' . '6' . ' months', '365' => '1 year', );
	$__finalCompiled .= '
		';
	$__vars['sortOrders'] = array('update_date' => 'xa_sc_update_date', 'reaction_score' => 'Reaction score', );
	$__finalCompiled .= '

		<div class="block-filterBar">
			<div class="filterBar">
				';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
							';
	if ($__vars['filters']['term']) {
		$__compilerTemp3 .= '
								<li><a href="' . $__templater->func('link', array('showcase/latest-updates', null, $__templater->filter($__vars['filters'], array(array('replace', array('term', null, )),), false), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Mentions' . $__vars['xf']['language']['label_separator'] . '</span>
									' . $__templater->escape($__vars['filters']['term']) . '</a></li>
							';
	}
	$__compilerTemp3 .= '							

							';
	if ($__vars['filters']['item_owner_id'] AND $__vars['itemOwnerFilter']) {
		$__compilerTemp3 .= '
								<li><a href="' . $__templater->func('link', array('showcase/latest-updates', null, $__templater->filter($__vars['filters'], array(array('replace', array('item_owner_id', null, )),), false), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Item owned by' . $__vars['xf']['language']['label_separator'] . '</span>
									' . $__templater->escape($__vars['itemOwnerFilter']['username']) . '</a></li>
							';
	}
	$__compilerTemp3 .= '							
							
							';
	if ($__vars['filters']['last_days'] AND $__vars['dateLimits'][$__vars['filters']['last_days']]) {
		$__compilerTemp3 .= '
								<li><a href="' . $__templater->func('link', array('showcase/latest-updates', null, $__templater->filter($__vars['filters'], array(array('replace', array('last_days', null, )),), false), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'xa_sc_update_posted:' . '</span>
									' . $__templater->escape($__vars['dateLimits'][$__vars['filters']['last_days']]) . '</a></li>
							';
	}
	$__compilerTemp3 .= '
							
							';
	if ($__vars['filters']['state']) {
		$__compilerTemp3 .= '
								<li><a href="' . $__templater->func('link', array('showcase/latest-updates', null, $__templater->filter($__vars['filters'], array(array('replace', array('state', null, )),), false), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'State' . $__vars['xf']['language']['label_separator'] . '</span>
									';
		if ($__vars['filters']['state'] == 'visible') {
			$__compilerTemp3 .= '
										' . 'Visible' . '
									';
		} else if ($__vars['filters']['state'] == 'moderated') {
			$__compilerTemp3 .= '
										' . 'Moderated' . '
									';
		} else if ($__vars['filters']['state'] == 'deleted') {
			$__compilerTemp3 .= '
										' . 'Deleted' . '
									';
		}
		$__compilerTemp3 .= '
								</a></li>
							';
	}
	$__compilerTemp3 .= '							
							
							';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp3 .= '
								<li><a href="' . $__templater->func('link', array('showcase/latest-updates', null, $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
									class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
									<span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
									' . $__templater->escape($__vars['sortOrders'][$__vars['filters']['order']]) . '
									' . $__templater->fontAwesome((($__vars['filters']['direction'] == 'asc') ? 'fa-angle-up' : 'fa-angle-down'), array(
		)) . '
									<span class="u-srOnly">';
		if ($__vars['filters']['direction'] == 'asc') {
			$__compilerTemp3 .= 'Ascending';
		} else {
			$__compilerTemp3 .= 'Descending';
		}
		$__compilerTemp3 .= '</span>
								</a></li>
							';
	}
	$__compilerTemp3 .= '
						';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
					<ul class="filterBar-filters">
						' . $__compilerTemp3 . '
					</ul>
				';
	}
	$__finalCompiled .= '

				<a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
				<div class="menu menu--wide" data-menu="menu" aria-hidden="true"
					data-href="' . $__templater->func('link', array('showcase/latest-updates-filters', null, $__vars['filters'], ), true) . '"
					data-load-target=".js-filterMenuBody">
					<div class="menu-content">
						<h4 class="menu-header">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</h4>
						<div class="js-filterMenuBody">
							<div class="menu-row">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="block-body">
			';
	if (!$__templater->test($__vars['updates'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['updates'])) {
			foreach ($__vars['updates'] AS $__vars['update']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_update_macros', 'update', array(
					'update' => $__vars['update'],
					'item' => $__vars['update']['Item'],
					'showItem' => true,
					'showAttachments' => true,
				), $__vars) . '
					';
			}
		}
		$__finalCompiled .= '
				</div>
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . 'There are no updates matching your filters.' . '</div>				
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No updates have been posted recently.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/latest-updates',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);