<?php
// FROM HASH: 8c124e05227d61f57c5962cfdbed5559
return array(
'macros' => array('ratings_status' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'content' => '!',
		'wrapperClass' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	if (!$__vars['content']['ratings_open']) {
		$__compilerTemp1 .= '
						<dd class="blockStatus-message blockStatus-message--locked">
							' . 'Not open for further reviews' . '
						</dd>
					';
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="' . $__templater->escape($__vars['wrapperClass']) . '">
			<dl class="blockStatus">
				<dt>' . 'Status' . '</dt>
				' . $__compilerTemp1 . '
			</dl>
		</div>
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->func('prefix', array('sc_item', $__vars['item'], 'escaped', ), true) . ($__vars['item']['meta_title'] ? $__templater->escape($__vars['item']['meta_title']) : $__templater->escape($__vars['item']['title'])) . ' - ' . 'Reviews');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:showcase/reviews', $__vars['item'], array('page' => (($__vars['page'] > 1) ? $__vars['page'] : null), ), ), false),
	), $__vars) . '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = 'reviews';
	$__templater->wrapTemplate('xa_sc_item_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

' . $__templater->callMacro('lightbox_macros', 'setup', array(
		'canViewAttachments' => $__templater->method($__vars['item'], 'canViewReviewImages', array()),
	), $__vars) . '

';
	if ($__vars['canInlineModReviews']) {
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

';
	$__vars['sortOrders'] = array('rating_date' => 'Date', 'vote_score' => 'Most helpful', 'rating' => 'Rating', );
	$__finalCompiled .= '

<div class="block block--messages"
	data-xf-init="' . ($__vars['canInlineModReviews'] ? 'inline-mod' : '') . '"
	data-type="sc_rating"
	data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">

	' . $__templater->callMacro(null, 'ratings_status', array(
		'content' => $__vars['item'],
		'wrapperClass' => 'block-outer',
	), $__vars) . '
	
	';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
					' . $__templater->callMacro('xa_sc_item_wrapper_macros', 'action_buttons', array(
		'item' => $__vars['item'],
		'showRateButton' => 'true',
		'canInlineMod' => $__vars['canInlineModReviews'],
	), $__vars) . '
				';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer">
			<div class="block-outer-opposite">
				' . $__compilerTemp2 . '
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	<div class="block-container"
		data-xf-init="lightbox"
		data-lb-id="item-' . $__templater->escape($__vars['item']['item_id']) . '"
		data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

		<div class="block-tabHeader tabs">
			<div class="hScroller" data-xf-init="h-scroller">
				<span class="hScroller-scroll">
					' . '
					<a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], $__vars['reviewTabs']['latest']['filters'], ), true) . '" class="tabs-tab ' . ($__vars['reviewTabs']['latest']['selected'] ? 'is-active' : '') . '">' . 'Latest' . '</a>
					';
	if ($__vars['item']['Category']['review_voting']) {
		$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], $__vars['reviewTabs']['helpful']['filters'], ), true) . '" class="tabs-tab ' . ($__vars['reviewTabs']['helpful']['selected'] ? 'is-active' : '') . '">' . 'Most helpful' . '</a>
					';
	}
	$__finalCompiled .= '
					<a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], $__vars['reviewTabs']['rating']['filters'], ), true) . '" class="tabs-tab ' . ($__vars['reviewTabs']['rating']['selected'] ? 'is-active' : '') . '">' . 'Rating' . '</a>
					' . '
				</span>
			</div>

			<div class="tabs-extra tabs-extra--minor">
				<a class="menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
				<div class="menu menu--wide" data-menu="menu" aria-hidden="true"
					data-href="' . $__templater->func('link', array('showcase/reviews/filters', $__vars['item'], $__vars['filters'], ), true) . '"
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

		';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
						';
	if ($__vars['filters']['rating']) {
		$__compilerTemp3 .= '
							<li><a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], $__templater->filter($__vars['filters'], array(array('replace', array(array('rating' => null, ), )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Rating' . $__vars['xf']['language']['label_separator'] . '</span>
								' . '' . $__templater->escape($__vars['filters']['rating']) . ' star(s)' . '
							</a></li>
						';
	}
	$__compilerTemp3 .= '
						
						';
	if ($__vars['filters']['term']) {
		$__compilerTemp3 .= '
							<li><a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], $__templater->filter($__vars['filters'], array(array('replace', array('term', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Mentions' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['filters']['term']) . '</a></li>
						';
	}
	$__compilerTemp3 .= '						
						
						';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp3 .= '
							';
		if ((!$__vars['reviewTabs']['latest']['selected']) AND ((!$__vars['reviewTabs']['helpful']['selected']) AND (!$__vars['reviewTabs']['rating']['selected']))) {
			$__compilerTemp3 .= '
								<li><a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
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
	}
	$__compilerTemp3 .= '
					';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__finalCompiled .= '
			<div class="block-filterBar">
				<div class="filterBar">
					<ul class="filterBar-filters">
					' . $__compilerTemp3 . '
					</ul>
				</div>
			</div>
		';
	}
	$__finalCompiled .= '

		<div class="block-body">
			';
	$__compilerTemp4 = true;
	if ($__templater->isTraversable($__vars['reviews'])) {
		foreach ($__vars['reviews'] AS $__vars['review']) {
			$__compilerTemp4 = false;
			$__finalCompiled .= '
				' . $__templater->callMacro('xa_sc_review_macros', 'review', array(
				'review' => $__vars['review'],
				'item' => $__vars['item'],
			), $__vars) . '
			';
		}
	}
	if ($__compilerTemp4) {
		$__finalCompiled .= '
				';
		if ($__vars['filters']) {
			$__finalCompiled .= '
					<div class="block-row">
						' . 'There are no reviews matching your filters. ' . '
					</div>
				';
		}
		$__finalCompiled .= '
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
	';
	$__compilerTemp5 = '';
	$__compilerTemp5 .= '
				' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/reviews',
		'data' => $__vars['item'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '
				' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
			';
	if (strlen(trim($__compilerTemp5)) > 0) {
		$__finalCompiled .= '
		<div class="block-outer block-outer--after">
			' . $__compilerTemp5 . '
		</div>
	';
	}
	$__finalCompiled .= '
	
	' . $__templater->callMacro(null, 'ratings_status', array(
		'content' => $__vars['item'],
		'wrapperClass' => 'block-outer block-outer--after',
	), $__vars) . '	
</div>

';
	return $__finalCompiled;
}
);