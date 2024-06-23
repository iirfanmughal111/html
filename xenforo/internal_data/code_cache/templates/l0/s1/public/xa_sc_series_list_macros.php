<?php
// FROM HASH: bb2011cd3bd2eb896411054cdc4b7d5f
return array(
'macros' => array('list_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'baseLinkPath' => '!',
		'creatorFilter' => null,
		'linkData' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__vars['sortOrders'] = array('last_part_date' => 'Latest item', 'create_date' => 'Create date', 'item_count' => 'Items', 'view_count' => 'Views', 'watch_count' => 'Watching', 'title' => 'Title', );
	$__finalCompiled .= '

	<div class="block-filterBar">
		<div class="filterBar">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['featured']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array('featured', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
								' . 'Featured' . '
							</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['has_items']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array('has_items', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
								' . 'Has items' . '
							</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['community']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array('community', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Show only' . $__vars['xf']['language']['label_separator'] . '</span>
								' . 'Community series' . '
							</a></li>
						';
	}
	$__compilerTemp1 .= '
						
						';
	if ($__vars['filters']['title']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array('title', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Title' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['filters']['title']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						
						';
	if ($__vars['filters']['term']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array('term', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Mentions' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['filters']['term']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '							
						
						';
	if ($__vars['filters']['creator_id'] AND $__vars['creatorFilter']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array('creator_id', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Created by' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['creatorFilter']['username']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '
						
						';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
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
				data-href="' . $__templater->func('link', array($__vars['baseLinkPath'] . '/filters', $__vars['linkData'], $__vars['filters'], ), true) . '"
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
),
'featured_carousel' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'featuredSeries' => '!',
		'viewAllLink' => '!',
		'isWidget' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['featuredSeries'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('carousel.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('lightslider.less');
		$__finalCompiled .= '

		';
		$__templater->includeJs(array(
			'prod' => 'xf/carousel-compiled.js',
			'dev' => 'vendor/lightslider/lightslider.min.js, xf/carousel.js',
		));
		$__finalCompiled .= '

		<div class="carousel carousel--withFooter carousel--scFeaturedSeries">
			<ul class="carousel-body carousel-body--show1" data-xf-init="carousel">
				';
		if ($__templater->isTraversable($__vars['featuredSeries'])) {
			foreach ($__vars['featuredSeries'] AS $__vars['series']) {
				$__finalCompiled .= '
					<li>
						<div class="carousel-item">
							<div class="contentRow">
								<div class="contentRow-main">
									';
				if ($__vars['series']['icon_date']) {
					$__finalCompiled .= '
										<div class="contentRow-figure">
											' . $__templater->func('sc_series_icon', array($__vars['series'], 's', $__templater->func('link', array('showcase/series', $__vars['series'], ), false), ), true) . '
										</div>
									';
				}
				$__finalCompiled .= '

									';
				if ($__vars['isWidget']) {
					$__finalCompiled .= '
										<div class="contentRow-scSeries">' . 'Series' . '</div>
									';
				}
				$__finalCompiled .= '

									<h4 class="contentRow-title"><a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '">' . $__templater->escape($__vars['series']['title']) . '</a></h4>

									<div class="contentRow-lesser">
										';
				if ($__vars['series']['description'] != '') {
					$__finalCompiled .= '
											' . $__templater->func('snippet', array($__vars['series']['description'], 300, array('stripQuote' => true, ), ), true) . '
										';
				} else {
					$__finalCompiled .= '
											' . $__templater->func('snippet', array($__vars['series']['message'], 300, array('stripQuote' => true, ), ), true) . '
										';
				}
				$__finalCompiled .= '
									</div>

									<div class="contentRow-minor contentRow-minor--smaller">
										<ul class="listInline listInline--bullet">
											<li>' . ($__templater->escape($__vars['series']['User']['username']) ?: $__templater->escape($__vars['series']['username'])) . '</li>
											<li><a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['series']['create_date'], array(
				))) . '</a></li>
											';
				if ($__vars['series']['last_part_date'] AND ($__vars['series']['last_part_date'] > $__vars['series']['create_date'])) {
					$__finalCompiled .= '
												<li>' . 'Updated' . ' ' . $__templater->func('date_dynamic', array($__vars['series']['last_part_date'], array(
					))) . '</li>
											';
				}
				$__finalCompiled .= '
											<li>' . 'Items' . ': ' . $__templater->filter($__vars['series']['item_count'], array(array('number', array()),), true) . '</li>
											';
				if ($__vars['series']['view_count']) {
					$__finalCompiled .= '
												<li>' . 'Views' . ': ' . $__templater->filter($__vars['series']['view_count'], array(array('number', array()),), true) . '</li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['series']['watch_count']) {
					$__finalCompiled .= '
												<li>' . 'Watching' . ': ' . $__templater->filter($__vars['series']['watch_count'], array(array('number', array()),), true) . '</li>
											';
				}
				$__finalCompiled .= '
										</ul>
									</div>

									';
				if ($__vars['series']['LastItem']) {
					$__finalCompiled .= '
										<div class="contentRow-scLatestItem">
											' . 'Latest item' . ': <a href="' . $__templater->func('link', array('showcase', $__vars['series']['LastItem'], ), true) . '" class="">' . $__templater->escape($__vars['series']['LastItem']['title']) . '</a>
										</div>
									';
				}
				$__finalCompiled .= '
								</div>
							</div>
						</div>
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ul>
			<div class="carousel-footer">
				<a href="' . $__templater->escape($__vars['viewAllLink']) . '">' . 'View all featured series' . '</a>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'series_carousel' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
		'viewAllLink' => '!',
		'isWidget' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['series'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeCss('carousel.less');
		$__finalCompiled .= '
		';
		$__templater->includeCss('lightslider.less');
		$__finalCompiled .= '

		';
		$__templater->includeJs(array(
			'prod' => 'xf/carousel-compiled.js',
			'dev' => 'vendor/lightslider/lightslider.min.js, xf/carousel.js',
		));
		$__finalCompiled .= '

		<div class="carousel carousel--withFooter carousel--scFeaturedSeries">
			<ul class="carousel-body carousel-body--show1" data-xf-init="carousel">
				';
		if ($__templater->isTraversable($__vars['series'])) {
			foreach ($__vars['series'] AS $__vars['seriesItem']) {
				$__finalCompiled .= '
					<li>
						<div class="carousel-item">
							<div class="contentRow">
								<div class="contentRow-main">
									';
				if ($__vars['seriesItem']['icon_date']) {
					$__finalCompiled .= '
										<div class="contentRow-figure">
											' . $__templater->func('sc_series_icon', array($__vars['seriesItem'], 's', $__templater->func('link', array('showcase/series', $__vars['seriesItem'], ), false), ), true) . '
										</div>
									';
				}
				$__finalCompiled .= '

									';
				if ($__vars['isWidget']) {
					$__finalCompiled .= '
										<div class="contentRow-scSeries">' . 'Series' . '</div>
									';
				}
				$__finalCompiled .= '

									<h4 class="contentRow-title"><a href="' . $__templater->func('link', array('showcase/series', $__vars['seriesItem'], ), true) . '">' . $__templater->escape($__vars['seriesItem']['title']) . '</a></h4>

									<div class="contentRow-lesser">
										';
				if ($__vars['seriesItem']['description'] != '') {
					$__finalCompiled .= '
											' . $__templater->func('snippet', array($__vars['seriesItem']['description'], 300, array('stripQuote' => true, ), ), true) . '
										';
				} else {
					$__finalCompiled .= '
											' . $__templater->func('snippet', array($__vars['seriesItem']['message'], 300, array('stripQuote' => true, ), ), true) . '
										';
				}
				$__finalCompiled .= '
									</div>

									<div class="contentRow-minor contentRow-minor--smaller">
										<ul class="listInline listInline--bullet">
											<li>' . ($__templater->escape($__vars['seriesItem']['User']['username']) ?: $__templater->escape($__vars['seriesItem']['username'])) . '</li>
											<li><a href="' . $__templater->func('link', array('showcase/series', $__vars['seriesItem'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['seriesItem']['create_date'], array(
				))) . '</a></li>
											';
				if ($__vars['seriesItem']['last_part_date'] AND ($__vars['seriesItem']['last_part_date'] > $__vars['seriesItem']['create_date'])) {
					$__finalCompiled .= '
												<li>' . 'Updated' . ' <a href="' . $__templater->func('link', array('showcase/series', $__vars['seriesItem'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['seriesItem']['last_part_date'], array(
					))) . '</a></li>
											';
				}
				$__finalCompiled .= '
											<li>' . 'Items' . ': ' . $__templater->filter($__vars['seriesItem']['item_count'], array(array('number', array()),), true) . '</li>
											';
				if ($__vars['series']['view_count']) {
					$__finalCompiled .= '
												<li>' . 'Views' . ': ' . $__templater->filter($__vars['series']['view_count'], array(array('number', array()),), true) . '</li>
											';
				}
				$__finalCompiled .= '
											';
				if ($__vars['series']['watch_count']) {
					$__finalCompiled .= '
												<li>' . 'Watching' . ': ' . $__templater->filter($__vars['series']['watch_count'], array(array('number', array()),), true) . '</li>
											';
				}
				$__finalCompiled .= '
										</ul>
									</div>

									';
				if ($__vars['seriesItem']['LastItem']) {
					$__finalCompiled .= '
										<div class="contentRow-scLatestItem">
											' . 'Latest item' . ': <a href="' . $__templater->func('link', array('showcase', $__vars['seriesItem']['LastItem'], ), true) . '" class="">' . $__templater->escape($__vars['seriesItem']['LastItem']['title']) . '</a>
										</div>
									';
				}
				$__finalCompiled .= '
								</div>
							</div>
						</div>
					</li>
				';
			}
		}
		$__finalCompiled .= '
			</ul>
			<div class="carousel-footer">
				<a href="' . $__templater->escape($__vars['viewAllLink']) . '">' . 'View all series' . '</a>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'series' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
		'showWatched' => true,
		'allowInlineMod' => true,
		'chooseName' => '',
		'extraInfo' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '
	';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '

	<div class="structItem structItem--series ' . ($__templater->method($__vars['series'], 'isIgnored', array()) ? 'is-ignored' : '') . (($__vars['series']['series_state'] == 'moderated') ? ' is-moderated' : '') . (($__vars['series']['series_state'] == 'deleted') ? ' is-deleted' : '') . '  js-seriesListItem-' . $__templater->escape($__vars['series']['series_id']) . '" data-author="' . $__templater->escape($__vars['series']['User']['username']) . '">
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconExpanded structItem-cell--iconScSeriesCoverImage">
			<div class="structItem-iconContainer">
				';
	if ($__vars['series']['icon_date']) {
		$__finalCompiled .= '
					' . $__templater->func('sc_series_icon', array($__vars['series'], 's', $__templater->func('link', array('showcase/series', $__vars['series'], ), false), ), true) . '
				';
	} else {
		$__finalCompiled .= '
					' . $__templater->func('avatar', array($__vars['series']['User'], 'm', false, array(
			'defaultname' => $__vars['series']['User']['username'],
		))) . '
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
		<div class="structItem-cell structItem-cell--main structItem-cell--listViewLayout" data-xf-init="touch-proxy">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	if ($__vars['series']['Featured']) {
		$__compilerTemp1 .= '
						<li>
							<i class="structItem-status structItem-status--attention" aria-hidden="true" title="' . $__templater->filter('Featured', array(array('for_attr', array()),), true) . '"></i>
							<span class="u-srOnly">' . 'Featured' . '</span>
						</li>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__vars['series']['has_poll']) {
		$__compilerTemp1 .= '
						<li>
							<i class="structItem-status structItem-status--poll" aria-hidden="true" title="' . $__templater->filter('Poll', array(array('for_attr', array()),), true) . '"></i>
							<span class="u-srOnly">' . 'Poll' . '</span>
						</li>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__vars['series']['series_state'] == 'moderated') {
		$__compilerTemp1 .= '
						<li>
							<i class="structItem-status structItem-status--moderated" aria-hidden="true" title="' . $__templater->filter('Awaiting approval', array(array('for_attr', array()),), true) . '"></i>
							<span class="u-srOnly">' . 'Awaiting approval' . '</span>
						</li>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__vars['series']['series_state'] == 'deleted') {
		$__compilerTemp1 .= '
						<li>
							<i class="structItem-status structItem-status--deleted" aria-hidden="true" title="' . $__templater->filter('Deleted', array(array('for_attr', array()),), true) . '"></i>
							<span class="u-srOnly">' . 'Deleted' . '</span>
						</li>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__vars['showWatched'] AND $__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1 .= '
						';
		if ($__vars['series']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp1 .= '
							<li>
								<i class="structItem-status structItem-status--watched" aria-hidden="true" title="' . $__templater->filter('Series watched', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Series watched' . '</span>
							</li>
						';
		}
		$__compilerTemp1 .= '
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__vars['series']['community_series']) {
		$__compilerTemp1 .= '
						<li>
							<i class="structItem-status structItem-status--community" aria-hidden="true" title="' . $__templater->filter('Community series', array(array('for_attr', array()),), true) . '"></i>
							<span class="u-srOnly">' . 'Community series' . '</span>
						</li>
					';
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
				<ul class="structItem-statuses">
				' . $__compilerTemp1 . '
				</ul>
			';
	}
	$__finalCompiled .= '

			<div class="structItem-title">
				<a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '" class="" data-tp-primary="on">' . $__templater->escape($__vars['series']['title']) . '</a>
			</div>

			<div class="structItem-minor">
				';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['extraInfo']) {
		$__compilerTemp2 .= '
							<li>' . $__templater->escape($__vars['extraInfo']) . '</li>
						';
	}
	$__compilerTemp2 .= '
						';
	if ($__vars['chooseName']) {
		$__compilerTemp2 .= '
							<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'name' => $__vars['chooseName'] . '[]',
			'value' => $__vars['series']['item_id'],
			'class' => 'js-chooseItem',
			'_type' => 'option',
		))) . '</li>
						';
	} else if ($__vars['allowInlineMod'] AND $__templater->method($__vars['series'], 'canUseInlineModeration', array())) {
		$__compilerTemp2 .= '
							<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['series']['series_id'],
			'class' => 'js-inlineModToggle',
			'data-xf-init' => 'tooltip',
			'title' => $__templater->filter('Select for moderation', array(array('for_attr', array()),), false),
			'_type' => 'option',
		))) . '</li>
						';
	}
	$__compilerTemp2 .= '
					';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
					<ul class="structItem-extraInfo">
					' . $__compilerTemp2 . '
					</ul>
				';
	}
	$__finalCompiled .= '
			</div>

			<div class="structItem-itemDescription">
				';
	if ($__vars['series']['description'] != '') {
		$__finalCompiled .= '
					' . $__templater->func('snippet', array($__vars['series']['description'], 300, array('stripQuote' => true, ), ), true) . '
				';
	} else {
		$__finalCompiled .= '
					' . $__templater->func('snippet', array($__vars['series']['message'], 300, array('stripQuote' => true, ), ), true) . '
				';
	}
	$__finalCompiled .= '
			</div>

			<div class="structItem-listViewMeta">
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--author">
					<dt></dt>
					<dd>' . $__templater->func('username_link', array($__vars['series']['User'], false, array(
		'defaultname' => $__vars['series']['User']['username'],
	))) . '</dd>
				</dl>
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--publishdate">
					<dt></dt>
					<dd><a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['series']['create_date'], array(
	))) . '</a></dd>
				</dl>
				';
	if ($__vars['series']['last_part_date'] AND ($__vars['series']['last_part_date'] > $__vars['series']['create_date'])) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--lastUpdate">
						<dt>' . 'Updated' . '</dt>
						<dd>' . $__templater->func('date_dynamic', array($__vars['series']['last_part_date'], array(
		))) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--views">
					<dt>' . 'Items' . '</dt>
					<dd>' . $__templater->filter($__vars['series']['item_count'], array(array('number', array()),), true) . '</dd>
				</dl>
				';
	if ($__vars['series']['view_count']) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--views">
						<dt>' . 'Views' . '</dt>
						<dd>' . $__templater->filter($__vars['series']['view_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '
				';
	if ($__vars['series']['watch_count']) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--views">
						<dt>' . 'Watching' . '</dt>
						<dd>' . $__templater->filter($__vars['series']['watch_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '
			</div>

			';
	if ($__vars['series']['LastItem']) {
		$__finalCompiled .= '
				<div class="structItem-LatestItemTitleFooter">
					' . 'Latest item' . ': <a href="' . $__templater->func('link', array('showcase', $__vars['series']['LastItem'], ), true) . '" class="">' . $__templater->escape($__vars['series']['LastItem']['title']) . '</a>
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'series_simple' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
		'withMeta' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	<div class="contentRow">
		<div class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['series']['User'], 'xxs', false, array(
	))) . '
		</div>
		
		<div class="contentRow-main contentRow-main--close">
			<a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '">' . $__templater->escape($__vars['series']['title']) . '</a>
			
			<div class="contentRow-snippet">
				';
	if ($__vars['series']['description'] != '') {
		$__finalCompiled .= '
					' . $__templater->func('snippet', array($__vars['series']['description'], 100, array('stripBbCode' => true, 'stripQuote' => true, ), ), true) . '
				';
	} else {
		$__finalCompiled .= '
					' . $__templater->func('smilie', array($__templater->func('snippet', array($__vars['series']['message'], 100, array('stripBbCode' => true, 'stripQuote' => true, ), ), false), ), true) . '
				';
	}
	$__finalCompiled .= '
			</div>
			
			';
	if ($__vars['withMeta']) {
		$__finalCompiled .= '
				<div class="contentRow-minor contentRow-minor--smaller">
					<ul class="listInline listInline--bullet">
						<li>' . ($__templater->escape($__vars['series']['User']['username']) ?: $__templater->escape($__vars['series']['username'])) . '</li>
						<li>' . $__templater->func('date_dynamic', array($__vars['series']['create_date'], array(
		))) . '</li>
						';
		if ($__vars['series']['LastItem']) {
			$__finalCompiled .= '
							<li>' . 'Updated' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->func('date_dynamic', array($__vars['series']['last_part_date'], array(
			))) . '</li>
							<li>
								' . 'Latest item' . ': <a href="' . $__templater->func('link', array('showcase', $__vars['series']['LastItem'], ), true) . '" class="">' . $__templater->escape($__vars['series']['LastItem']['title']) . '</a>
							</li>
						';
		}
		$__finalCompiled .= '
					</ul>
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'series_list_item_struct_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'chooseName' => '',
		'extraInfo' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('structured_list.less');
	$__finalCompiled .= '

	';
	if ($__vars['item']['LastComment'] AND $__templater->method($__vars['item']['LastComment'], 'isUnread', array())) {
		$__finalCompiled .= '
		';
		$__vars['link'] = $__templater->preEscaped($__templater->func('link', array('showcase/item-comments/unread', $__vars['item'], ), true));
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
		';
		$__vars['link'] = $__templater->preEscaped($__templater->func('link', array('showcase', $__vars['item'], ), true));
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
	<div class="structItem structItem--middle' . (($__vars['item']['LastComment'] AND $__templater->method($__vars['item']['LastComment'], 'isUnread', array())) ? ' is-unread' : '') . '" data-author="' . ($__templater->escape($__vars['item']['User']['username']) ?: $__templater->escape($__vars['item']['username'])) . '">
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconFixedSmall">
			<div class="structItem-iconContainer">
				';
	if ($__vars['item']['cover_image_id']) {
		$__finalCompiled .= '
					<a href="' . $__templater->escape($__vars['link']) . '">
						' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '
					</a>
					' . $__templater->func('avatar', array($__vars['item']['User'], 's', false, array(
			'href' => '',
			'class' => 'avatar--separated structItem-secondaryIcon',
		))) . '
				';
	} else if ($__vars['item']['series_part_id'] AND ($__vars['item']['SeriesPart']['Series'] AND $__vars['item']['SeriesPart']['Series']['icon_date'])) {
		$__finalCompiled .= '
					' . $__templater->func('sc_series_icon', array($__vars['item']['SeriesPart']['Series'], 's', $__templater->func('link', array('showcase', $__vars['item'], ), false), ), true) . '
					' . $__templater->func('avatar', array($__vars['item']['User'], 's', false, array(
			'href' => '',
			'class' => 'avatar--separated structItem-secondaryIcon',
		))) . '
				';
	} else if ($__vars['item']['Category']['content_image_url']) {
		$__finalCompiled .= '
					<a href="' . $__templater->escape($__vars['link']) . '">
						' . $__templater->func('sc_category_icon', array($__vars['item'], ), true) . '
					</a>
					' . $__templater->func('avatar', array($__vars['item']['User'], 's', false, array(
			'href' => '',
			'class' => 'avatar--separated structItem-secondaryIcon',
		))) . '
				';
	} else {
		$__finalCompiled .= '
					' . $__templater->func('avatar', array($__vars['item']['User'], 'm', false, array(
			'defaultname' => ($__vars['item']['username'] ?: 'Deleted member'),
		))) . '
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
		<div class="structItem-cell structItem-cell--main" data-xf-init="touch-proxy">
			<div class="structItem-title">
				<a href="' . $__templater->escape($__vars['link']) . '" class="" data-tp-primary="on">' . $__templater->escape($__vars['item']['title']) . '</a>
			</div>

			<div class="structItem-minor">
				';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
							';
	if ($__vars['extraInfo']) {
		$__compilerTemp1 .= '
								<li>' . $__templater->escape($__vars['extraInfo']) . '</li>
							';
	}
	$__compilerTemp1 .= '
							';
	if ($__vars['chooseName']) {
		$__compilerTemp1 .= '
								<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'name' => $__vars['chooseName'] . '[]',
			'value' => $__vars['item']['item_id'],
			'class' => 'js-chooseItem',
			'_type' => 'option',
		))) . '</li>
							';
	}
	$__compilerTemp1 .= '
						';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
					<ul class="structItem-extraInfo">
						' . $__compilerTemp1 . '
					</ul>
				';
	}
	$__finalCompiled .= '
				<ul class="structItem-parts">
					<li>' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
		'defaultname' => $__vars['item']['username'],
	))) . '</li>
					<li>' . 'Item' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
	))) . '</li>
					';
	if ($__vars['item']['category_id'] AND $__vars['item']['Category']) {
		$__finalCompiled .= '
						<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['item']['Category']['title']) . '</li>
					';
	}
	$__finalCompiled .= '
				</ul>
			</div>
		</div>
		<div class="structItem-cell structItem-cell--meta">
			<dl class="pairs pairs--justified">
				<dt>' . 'Comments' . '</dt>
				<dd>' . $__templater->filter($__vars['item']['comment_count'], array(array('number', array()),), true) . '</dd>
			</dl>
		</div>
		<div class="structItem-cell structItem-cell--latest">
			';
	if ($__vars['item']['LastComment']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase/comments', $__vars['item']['LastComment'], ), true) . '" rel="nofollow">' . $__templater->func('date_dynamic', array($__vars['item']['last_comment_date'], array(
			'class' => 'structItem-latestDate',
		))) . '</a>
				<div class="structItem-minor">
					' . $__templater->func('username_link', array($__vars['item']['LastCommenter'], false, array(
		))) . '
				</div>
			';
	} else {
		$__finalCompiled .= '
				-
			';
	}
	$__finalCompiled .= '
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'series_watch_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
		'chooseName' => '',
		'bonusInfo' => '',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	
	<div class="node node--depth2 node--scSeries node--scSeries' . $__templater->escape($__vars['series']['series_id']) . '">
		<div class="node-body">
			<div class="node-main js-nodeMain">
				';
	if ($__vars['chooseName']) {
		$__finalCompiled .= '
					' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'labelclass' => 'u-pullRight',
			'class' => 'js-chooseItem',
			'name' => $__vars['chooseName'] . '[]',
			'value' => $__vars['series']['series_id'],
			'_type' => 'option',
		))) . '
				';
	}
	$__finalCompiled .= '

				';
	$__vars['descriptionDisplay'] = $__templater->func('property', array('nodeListDescriptionDisplay', ), false);
	$__finalCompiled .= '
				<h3 class="node-title">
					<a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '" data-xf-init="' . (($__vars['descriptionDisplay'] == 'tooltip') ? 'element-tooltip' : '') . '" data-shortcut="node-description">' . $__templater->escape($__vars['series']['title']) . '</a>
				</h3>
				';
	if (($__vars['descriptionDisplay'] != 'none') AND $__vars['series']['description']) {
		$__finalCompiled .= '
					<div class="node-description ' . (($__vars['descriptionDisplay'] == 'tooltip') ? 'node-description--tooltip js-nodeDescTooltip' : '') . '">' . $__templater->filter($__vars['series']['description'], array(array('raw', array()),), true) . '</div>
				';
	}
	$__finalCompiled .= '

				<div class="node-meta">
					<div class="node-statsMeta">
						<dl class="pairs pairs--inline">
							<dt>' . 'Items' . '</dt>
							<dd>' . $__templater->filter($__vars['series']['item_count'], array(array('number', array()),), true) . '</dd>
						</dl>
						';
	if ($__vars['series']['view_count']) {
		$__finalCompiled .= '
							<dl class="pairs pairs--inline">
								<dt>' . 'Views' . '</dt>
								<dd>' . $__templater->filter($__vars['series']['view_count'], array(array('number', array()),), true) . '</dd>
							</dl>
						';
	}
	$__finalCompiled .= '
						';
	if ($__vars['series']['watch_count']) {
		$__finalCompiled .= '
							<dl class="pairs pairs--inline">
								<dt>' . 'Watching' . '</dt>
								<dd>' . $__templater->filter($__vars['series']['watch_count'], array(array('number', array()),), true) . '</dd>
							</dl>
						';
	}
	$__finalCompiled .= '
					</div>
				</div>

				';
	if (!$__templater->test($__vars['bonusInfo'], 'empty', array())) {
		$__finalCompiled .= '
					<div class="node-bonus">' . $__templater->escape($__vars['bonusInfo']) . '</div>
				';
	}
	$__finalCompiled .= '
			</div>

			<div class="node-stats node-stats--single">
				<dl class="pairs pairs--rows">
					<dt>' . 'Items' . '</dt>
					<dd>' . $__templater->filter($__vars['series']['item_count'], array(array('number', array()),), true) . '</dd>
				</dl>
			</div>

			<div class="node-extra">
				';
	if ($__vars['series']['last_part_date']) {
		$__finalCompiled .= '
					<div class="node-extra-row"><a href="' . $__templater->func('link', array('showcase', $__vars['series']['LastItem'], ), true) . '" class="node-extra-title" title="' . $__templater->escape($__vars['series']['LastItem']['title']) . '">' . $__templater->escape($__vars['series']['LastItem']['title']) . '</a></div>
					<div class="node-extra-row">
						' . $__templater->func('date_dynamic', array($__vars['series']['LastItem']['create_date'], array(
			'class' => 'node-extra-date',
		))) . '
					</div>
				';
	} else {
		$__finalCompiled .= '
					<span class="node-extra-placeholder">' . 'None' . '</span>
				';
	}
	$__finalCompiled .= '
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
	$__finalCompiled .= '

' . '

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);