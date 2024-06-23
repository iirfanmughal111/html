<?php
// FROM HASH: 12b5dbea1662c94f575abc8ca2fddf24
return array(
'macros' => array('list_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'baseLinkPath' => '!',
		'creatorFilter' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['dateLimits'] = array('-1' => 'Any time', '7' => '' . '7' . ' days', '14' => '' . '14' . ' days', '30' => '' . '30' . ' days', '60' => '' . '2' . ' months', '90' => '' . '3' . ' months', '182' => '' . '6' . ' months', '365' => '1 year', );
	$__finalCompiled .= '
	';
	$__vars['sortOrders'] = array('last_update' => 'Last update', 'create_date' => 'Create date', 'title' => 'Title', );
	$__finalCompiled .= '

	<div class="block-filterBar">
		<div class="filterBar">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['state']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array('state', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'State' . $__vars['xf']['language']['label_separator'] . '</span>
								';
		if ($__vars['filters']['state'] == 'draft') {
			$__compilerTemp1 .= '
									' . 'Draft' . '
								';
		} else if ($__vars['filters']['state'] == 'awaiting') {
			$__compilerTemp1 .= '
									' . 'Awaiting' . '
								';
		}
		$__compilerTemp1 .= '
							</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['title']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array('title', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Title' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['filters']['title']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['prefix_id']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array('prefix_id', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Prefix' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->func('prefix_title', array('sc_item', $__vars['filters']['prefix_id'], ), true) . '</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['creator_id'] AND $__vars['creatorFilter']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array('creator_id', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Created by' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['creatorFilter']['username']) . '</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['last_days'] AND $__vars['dateLimits'][$__vars['filters']['last_days']]) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array('last_days', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Last updated' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['dateLimits'][$__vars['filters']['last_days']]) . '</a></li>
						';
	}
	$__compilerTemp1 .= '

						';
	if ($__vars['filters']['order'] AND $__vars['sortOrders'][$__vars['filters']['order']]) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], null, $__templater->filter($__vars['filters'], array(array('replace', array(array('order' => null, 'direction' => null, ), )),), false), ), true) . '"
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
				data-href="' . $__templater->func('link', array('showcase/item-queue-filters', null, $__vars['filters'], ), true) . '"
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
'item_queue_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
		'category' => '!',
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

	<div class="structItem structItem--item ' . $__templater->escape($__vars['item']['item_state']) . ' js-itemQueueItem-' . $__templater->escape($__vars['item']['item_id']) . '" data-author="' . ($__templater->escape($__vars['item']['User']['username']) ?: $__templater->escape($__vars['item']['username'])) . '">
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconExpanded structItem-cell--iconScCoverImage">
			<div class="structItem-iconContainer">
				';
	if ($__vars['item']['CoverImage']) {
		$__finalCompiled .= '
					<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '">
						' . $__templater->func('sc_item_thumbnail', array($__vars['item'], ), true) . '
					</a>
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
	if ($__vars['item']['item_state'] == 'awaiting') {
		$__compilerTemp1 .= '
						<li><span class="label label--smallest label--orange">' . 'Awaiting' . '</span></li>
					';
	}
	$__compilerTemp1 .= '
					';
	if ($__vars['item']['item_state'] == 'draft') {
		$__compilerTemp1 .= '
						<li><span class="label label--smallest label--red">' . 'Draft' . '</span></li>
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
				<a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '" class="" data-tp-primary="on">' . $__templater->escape($__vars['item']['title']) . '</a>
			</div>

			<div class="structItem-itemDescription">
				' . $__templater->func('snippet', array($__vars['item']['message'], 300, array('stripQuote' => true, ), ), true) . '
			</div>

			<div class="structItem-listViewMeta">
				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--author">
					<dt></dt>
					<dd>' . $__templater->func('username_link', array($__vars['item']['User'], false, array(
		'defaultname' => ($__vars['item']['username'] ?: 'Deleted member'),
	))) . '</dd>
				</dl>

				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--createdate">
					<dt>' . 'Create date' . '</dt>
					<dd><a href="' . $__templater->func('link', array('showcase', $__vars['item'], ), true) . '" rel="nofollow" class="u-concealed">' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
	))) . '</a></dd>
				</dl>

				<dl class="pairs pairs--justified structItem-minor structItem-metaItem structItem-metaItem--lastUpdate">
					<dt>' . 'Last edited' . '</dt>
					<dd>' . $__templater->func('date_dynamic', array($__vars['item']['edit_date'], array(
	))) . '</dd>
				</dl>
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

';
	return $__finalCompiled;
}
);