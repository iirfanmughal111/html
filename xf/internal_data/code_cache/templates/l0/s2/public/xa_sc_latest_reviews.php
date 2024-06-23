<?php
// FROM HASH: a6d512df5e568c5ad5700005b458668b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Latest reviews');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), ));
	$__finalCompiled .= '

';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

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
	$__vars['sortOrders'] = array('review_date' => 'Date', 'vote_score' => 'Most helpful', 'rating' => 'Rating', );
	$__finalCompiled .= '

<div class="block block--messages"
	data-xf-init="' . ($__vars['canInlineModReviews'] ? 'inline-mod' : '') . '"
	data-type="sc_rating"
	data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">

	<div class="block-outer">';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['canInlineModReviews']) {
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
		'link' => 'showcase/latest-reviews',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp1 . '
	'), false) . '</div>

	<div class="block-container"
		data-xf-init="lightbox"
		data-lb-universal="' . $__templater->escape($__vars['xf']['options']['lightBoxUniversal']) . '">

		<div class="block-tabHeader tabs">
			<div class="hScroller" data-xf-init="h-scroller">
				<span class="hScroller-scroll">
					' . '
					<a href="' . $__templater->func('link', array('showcase/latest-reviews', null, $__vars['reviewTabs']['latest']['filters'], ), true) . '" class="tabs-tab ' . ($__vars['reviewTabs']['latest']['selected'] ? 'is-active' : '') . '">' . 'Latest' . '</a>
					<a href="' . $__templater->func('link', array('showcase/latest-reviews', null, $__vars['reviewTabs']['helpful']['filters'], ), true) . '" class="tabs-tab ' . ($__vars['reviewTabs']['helpful']['selected'] ? 'is-active' : '') . '">' . 'Most helpful' . '</a>
					<a href="' . $__templater->func('link', array('showcase/latest-reviews', null, $__vars['reviewTabs']['rating']['filters'], ), true) . '" class="tabs-tab ' . ($__vars['reviewTabs']['rating']['selected'] ? 'is-active' : '') . '">' . 'Rating' . '</a>
					' . '
				</span>
			</div>

			<div class="tabs-extra tabs-extra--minor">
				<a class="menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
				<div class="menu menu--wide" data-menu="menu" aria-hidden="true"
					data-href="' . $__templater->func('link', array('showcase/latest-reviews/filters', null, $__vars['filters'], ), true) . '"
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
							<li><a href="' . $__templater->func('link', array('showcase/latest-reviews', null, $__templater->filter($__vars['filters'], array(array('replace', array(array('rating' => null, ), )),), false), ), true) . '"
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
							<li><a href="' . $__templater->func('link', array('showcase/latest-reviews', null, $__templater->filter($__vars['filters'], array(array('replace', array('term', null, )),), false), ), true) . '"
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
								<li><a href="' . $__templater->func('link', array('showcase/latest-reviews', null, $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
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
	if (!$__templater->test($__vars['reviews'], 'empty', array())) {
		$__finalCompiled .= '
				<div class="structItemContainer">
					';
		if ($__templater->isTraversable($__vars['reviews'])) {
			foreach ($__vars['reviews'] AS $__vars['review']) {
				$__finalCompiled .= '
						' . $__templater->callMacro('xa_sc_review_macros', 'review', array(
					'review' => $__vars['review'],
					'item' => $__vars['review']['Item'],
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
				<div class="block-row">' . 'There are no reviews matching your filters. ' . '</div>				
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No reviews have been left recently.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'showcase/latest-reviews',
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