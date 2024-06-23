<?php
// FROM HASH: 57248ebfddd9f22d2f0bb9210dcaf27d
return array(
'macros' => array('list_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'baseLinkPath' => '!',
		'category' => null,
		'creatorFilter' => null,
		'linkData' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	';
	$__vars['dateLimits'] = array('-1' => 'Any time', '7' => '' . '7' . ' days', '14' => '' . '14' . ' days', '30' => '' . '30' . ' days', '60' => '' . '2' . ' months', '90' => '' . '3' . ' months', '182' => '' . '6' . ' months', '365' => '1 year', );
	$__finalCompiled .= '
	';
	$__vars['sortOrders'] = array('create_date' => 'Create date', 'last_update' => 'Last update', 'rating_weighted' => 'Rating', 'reaction_score' => 'Reaction score', 'view_count' => 'Views', 'title' => 'Title', );
	$__finalCompiled .= '

	<div class="block-filterBar">
		<div class="filterBar">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['featured']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('featured', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
								' . 'Featured' . '
							</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['is_rated']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('is_rated', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
								' . 'Is rated' . '
							</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['has_reviews']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('has_reviews', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
								' . 'Has reviews' . '
							</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['has_comments']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('has_comments', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
								' . 'Has comments' . '
							</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['title']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('title', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Title' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['filters']['title']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						
						';
	if ($__vars['filters']['term']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('term', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Mentions' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['filters']['term']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						
						';
	if ($__vars['filters']['location']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('location', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Location' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['filters']['location']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '						
						
						';
	if ($__vars['filters']['rating_avg']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('rating_avg', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Avg rating' . $__vars['xf']['language']['label_separator'] . '</span>
								';
		if ($__vars['filters']['rating_avg'] == 5) {
			$__compilerTemp1 .= '
									' . '5 Stars' . '
								';
		} else if ($__vars['filters']['rating_avg'] == 4) {
			$__compilerTemp1 .= '
									' . '4 Stars &amp; up' . '
								';
		} else if ($__vars['filters']['rating_avg'] == 3) {
			$__compilerTemp1 .= '
									' . '3 Stars &amp; up' . '
								';
		} else if ($__vars['filters']['rating_avg'] == 2) {
			$__compilerTemp1 .= '
									' . '2 Stars &amp; up' . '
								';
		}
		$__compilerTemp1 .= '
							</a></li>
						';
	}
	$__compilerTemp1 .= '
						
						';
	if ($__vars['filters']['prefix_id']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('prefix_id', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Prefix' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->func('prefix_title', array('sc_item', $__vars['filters']['prefix_id'], ), true) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						
						';
	if ($__vars['filters']['creator_id'] AND $__vars['creatorFilter']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('creator_id', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Created by' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['creatorFilter']['username']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						
						';
	if ($__vars['filters']['last_days'] AND $__vars['dateLimits'][$__vars['filters']['last_days']]) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('last_days', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Last updated' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['dateLimits'][$__vars['filters']['last_days']]) . '</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['state']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array('state', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'State' . $__vars['xf']['language']['label_separator'] . '</span>
								';
		if ($__vars['filters']['state'] == 'visible') {
			$__compilerTemp1 .= '
									' . 'Visible' . '
								';
		} else if ($__vars['filters']['state'] == 'moderated') {
			$__compilerTemp1 .= '
									' . 'Moderated' . '
								';
		} else if ($__vars['filters']['state'] == 'deleted') {
			$__compilerTemp1 .= '
									' . 'Deleted' . '
								';
		}
		$__compilerTemp1 .= '
							</a></li>
						';
	}
	$__compilerTemp1 .= '						
												
						';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['category'], $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Return to the default order', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Sort by' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['sortOrders'][$__vars['filters']['order']]) . '
								' . $__templater->fontAwesome((($__vars['filters']['direction'] == 'asc') ? 'fa-angle-up' : 'fa-angle-down'), array(
		)) . '
								<span class="u-srOnly">';
		if ($__vars['filters']['direction'] == 'asc') {
			$__compilerTemp1 .= 'Ascending';
		} else {
			$__compilerTemp1 .= 'Descending';
		}
		$__compilerTemp1 .= '</span>
							</a></li>
						';
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
				<ul class="filterBar-filters">
					' . $__compilerTemp1 . '
				</ul>
			';
	}
	$__finalCompiled .= '

			<a class="filterBar-menuTrigger" data-xf-click="menu" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">' . 'Filters' . '</a>
			<div class="menu menu--wide" data-menu="menu" aria-hidden="true"
				data-href="' . $__templater->func('link', array($__vars['baseLinkPath'] . '/filters', $__vars['category'], $__vars['filters'], ), true) . '"
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
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);