<?php
// FROM HASH: fee612e6355e85e4dca065f9fae71ae5
return array(
'macros' => array('series_part_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'seriesPart' => '!',
		'series' => '!',
		'item' => '!',
		'category' => null,
		'showWatched' => true,
		'allowInlineMod' => false,
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

	<div class="structItem structItem--item ' . ($__vars['item']['prefix_id'] ? ('is-prefix' . $__templater->escape($__vars['item']['prefix_id'])) : '') . ' ' . ($__templater->method($__vars['item'], 'isIgnored', array()) ? 'is-ignored' : '') . (($__templater->method($__vars['item'], 'isUnread', array()) AND (!$__vars['forceRead'])) ? ' is-unread' : '') . (($__vars['item']['item_state'] == 'moderated') ? ' is-moderated' : '') . (($__vars['item']['item_state'] == 'deleted') ? ' is-deleted' : '') . ' js-inlineModContainer js-itemListItem-' . $__templater->escape($__vars['item']['item_id']) . '" data-author="' . ($__templater->escape($__vars['item']['User']['username']) ?: $__templater->escape($__vars['item']['username'])) . '">
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconExpanded structItem-cell--iconAmsCoverImage">
			<div class="structItem-iconContainer">
				';
	if ($__vars['item']['cover_image_id']) {
		$__finalCompiled .= '
					<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
						' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '
					</a>
				';
	} else if ($__vars['series']['icon_date']) {
		$__finalCompiled .= '
					' . $__templater->func('sc_series_icon', array($__vars['series'], 's', $__templater->func('link', array('showcase', $__vars['item'], ), false), ), true) . '
				';
	} else if ($__vars['item']['Category']['content_image_url']) {
		$__finalCompiled .= '
					<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
						' . $__templater->func('sc_category_icon', array($__vars['item'], ), true) . '
					</a>
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
		<div class="structItem-cell structItem-cell--main structItem-cell--listViewLayout" data-xf-init="touch-proxy">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	if ($__vars['item']['Featured']) {
		$__compilerTemp1 .= '
						<li>
							<i class="structItem-status structItem-status--attention" aria-hidden="true" title="' . $__templater->filter('Featured', array(array('for_attr', array()),), true) . '"></i>
							<span class="u-srOnly">' . 'Featured' . '</span>
						</li>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__vars['showWatched'] AND $__vars['xf']['visitor']['user_id']) {
		$__compilerTemp1 .= '
						';
		if ($__vars['item']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp1 .= '
							<li>
								<i class="structItem-status structItem-status--watched" aria-hidden="true" title="' . $__templater->filter('Item watched', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Item watched' . '</span>
							</li>
						';
		} else if ((!$__vars['category']) AND $__vars['item']['Category']['Watch'][$__vars['xf']['visitor']['user_id']]) {
			$__compilerTemp1 .= '
							<li>
								<i class="structItem-status structItem-status--watched" aria-hidden="true" title="' . $__templater->filter('Category watched', array(array('for_attr', array()),), true) . '"></i>
								<span class="u-srOnly">' . 'Category watched' . '</span>
							</li>
						';
		}
		$__compilerTemp1 .= '
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

			<div class="structItem-itemCategoryTitleHeader">
				<ul class="structItem-parts">
					<li><a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a></li>
				</ul>
			</div>

			<div class="structItem-title">
				';
	if ($__vars['item']['prefix_id']) {
		$__finalCompiled .= '
					';
		if ($__vars['category']) {
			$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('showcase/categories', $__vars['category'], array('prefix_id' => $__vars['item']['prefix_id'], ), ), true) . '" class="labelLink">' . $__templater->func('prefix', array('sc_item', $__vars['item'], 'html', '', ), true) . '</a>
					';
		} else {
			$__finalCompiled .= '
						' . $__templater->func('prefix', array('sc_item', $__vars['item'], 'html', '', ), true) . '
					';
		}
		$__finalCompiled .= '
				';
	}
	$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '" class="" data-tp-primary="on">' . $__templater->escape($__vars['item']['title']) . '</a>
			</div>

			<div class="structItem-itemDescription">
				' . $__templater->func('snippet', array($__vars['item']['message'], 300, array('stripQuote' => true, ), ), true) . '
			</div>

			<div class="structItem-listViewMeta">
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--author">
					<dt></dt>
					<dd>' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
		'defaultname' => $__vars['item']['username'],
	))) . '</dd>
				</dl>

				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--publishdate">
					<dt></dt>
					<dd><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
	))) . '</a></dd>
				</dl>

				';
	if ($__vars['item']['rating_avg'] AND $__vars['item']['review_count']) {
		$__finalCompiled .= '
					<div class="structItem-metaItem  structItem-metaItem--rating">
						' . $__templater->callMacro('rating_macros', 'stars_text', array(
			'rating' => $__vars['item']['rating_avg'],
			'count' => $__vars['item']['review_count'],
			'rowClass' => 'ratingStarsRow--justified',
			'starsClass' => 'ratingStars--smaller',
		), $__vars) . '
					</div>
				';
	}
	$__finalCompiled .= '
				';
	if ($__vars['item']['view_count']) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--views">
						<dt>' . 'Views' . '</dt>
						<dd>' . $__templater->filter($__vars['item']['view_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '
				';
	if ($__vars['item']['reaction_score']) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--reaction_score">
						<dt>' . 'Reaction score' . '</dt>
						<dd>' . $__templater->filter($__vars['item']['reaction_score'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__finalCompiled .= '
				';
	if ($__vars['item']['comment_count']) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--comments">
						<dt>' . 'Comments' . '</dt>
						<dd><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '#comments" class="u-concealed">' . $__templater->filter($__vars['item']['comment_count'], array(array('number', array()),), true) . '</a></dd>
					</dl>
				';
	}
	$__finalCompiled .= '
				';
	if ($__vars['item']['review_count']) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--reviews">
						<dt>' . 'Reviews' . '</dt>
						<dd><a href="' . $__templater->func('link', array('showcase/reviews', $__vars['item'], ), true) . '" class="u-concealed">' . $__templater->filter($__vars['item']['review_count'], array(array('number', array()),), true) . '</a></dd>
					</dl>
				';
	}
	$__finalCompiled .= '
				';
	if ($__vars['item']['last_update'] AND ($__vars['item']['last_update'] > $__vars['item']['create_date'])) {
		$__finalCompiled .= '
					<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--lastUpdate">
						<dt>' . 'Updated' . '</dt>
						<dd><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['item']['last_update'], array(
		))) . '</a></dd>
					</dl>
				';
	}
	$__finalCompiled .= '
			</div>

			<div class="actionBar actionBarSeries">
				<div class="actionBar-set actionBar-set--internal">
					';
	if ($__templater->method($__vars['seriesPart'], 'canEdit', array())) {
		$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('showcase/series-part/edit', $__vars['seriesPart'], ), true) . '" 
							class="actionBar-action actionBar-action--edit" 
							data-xf-click="overlay">' . 'Edit' . '</a>
					';
	}
	$__finalCompiled .= '
					';
	if ($__templater->method($__vars['seriesPart'], 'canRemove', array())) {
		$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('showcase/series-part/remove', $__vars['seriesPart'], ), true) . '" 
							class="actionBar-action actionBar-action--remove" 
							data-xf-click="overlay">' . 'Remove' . '</a>
					';
	}
	$__finalCompiled .= '
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