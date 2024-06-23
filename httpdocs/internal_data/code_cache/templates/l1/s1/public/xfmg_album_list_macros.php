<?php
// FROM HASH: e933e7ec665ee90b3bbcb31527fa833b
return array(
'macros' => array('album_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'albums' => '!',
		'isChooser' => false,
		'allowInlineMod' => true,
		'forceInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('xfmg_album_list.less');
	$__finalCompiled .= '

	<div class="itemList">
		';
	if ($__templater->isTraversable($__vars['albums'])) {
		foreach ($__vars['albums'] AS $__vars['album']) {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'album_list_item', array(
				'album' => $__vars['album'],
				'isChooser' => $__vars['isChooser'],
				'allowInlineMod' => $__vars['allowInlineMod'],
				'forceInlineMod' => $__vars['forceInlineMod'],
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '
		' . $__templater->callMacro(null, 'album_list_placeholders', array(), $__vars) . '
	</div>
';
	return $__finalCompiled;
}
),
'list_filter_bar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'filters' => '!',
		'baseLinkPath' => '!',
		'linkData' => null,
		'ownerFilter' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['sortOrders'] = array('create_date' => 'Create date', 'media_count' => 'Media count', 'comment_count' => 'Comments', 'rating_weighted' => 'Rating', 'reaction_score' => 'Reaction score', 'view_count' => 'Views', );
	$__finalCompiled .= '

	<div class="block-filterBar">
		<div class="filterBar">
			';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['type']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array('type', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Type' . $__vars['xf']['language']['label_separator'] . '</span>
								';
		if ($__vars['filters']['type'] == 'image') {
			$__compilerTemp1 .= '
									' . 'Images' . '
									';
		} else if ($__vars['filters']['type'] == 'audio') {
			$__compilerTemp1 .= '
									' . 'Audio' . '
									';
		} else if ($__vars['filters']['type'] == 'video') {
			$__compilerTemp1 .= '
									' . 'Videos' . '
									';
		} else if ($__vars['filters']['type'] == 'embed') {
			$__compilerTemp1 .= '
									' . 'Embeds' . '
								';
		}
		$__compilerTemp1 .= '
							</a></li>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['filters']['owner_id'] AND $__vars['ownerFilter']) {
		$__compilerTemp1 .= '
							<li><a href="' . $__templater->func('link', array($__vars['baseLinkPath'], $__vars['linkData'], $__templater->filter($__vars['filters'], array(array('replace', array('owner_id', null, )),), false), ), true) . '"
								class="filterBar-filterToggle" data-xf-init="tooltip" title="' . $__templater->filter('Remove this filter', array(array('for_attr', array()),), true) . '">
								<span class="filterBar-filterToggle-label">' . 'Media owner' . $__vars['xf']['language']['label_separator'] . '</span>
								' . $__templater->escape($__vars['ownerFilter']['username']) . '</a></li>
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
'album_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
		'isChooser' => false,
		'allowInlineMod' => true,
		'forceInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="itemList-item js-inlineModContainer' . ($__templater->method($__vars['album'], 'isIgnored', array()) ? ' is-ignored' : '') . '" data-author="' . ($__templater->escape($__vars['album']['User']['username']) ?: $__templater->escape($__vars['album']['username'])) . '">
		<a href="' . $__templater->func('link', array('media/albums' . ($__vars['isChooser'] ? '/add' : ''), $__vars['album'], ), true) . '">
			';
	if ($__vars['allowInlineMod']) {
		$__finalCompiled .= '
				' . $__templater->callMacro(null, 'album_list_item_inline_mod', array(
			'album' => $__vars['album'],
			'forceInlineMod' => $__vars['forceInlineMod'],
		), $__vars) . '
			';
	}
	$__finalCompiled .= '
			' . $__templater->callMacro(null, 'album_list_item_type_icon', array(
		'album' => $__vars['album'],
	), $__vars) . '
			' . $__templater->callMacro(null, 'album_list_item_thumb', array(
		'album' => $__vars['album'],
	), $__vars) . '
		</a>
		' . $__templater->callMacro(null, 'album_list_item_overlay', array(
		'album' => $__vars['album'],
		'isChooser' => $__vars['isChooser'],
	), $__vars) . '
	</div>
';
	return $__finalCompiled;
}
),
'album_list_item_slider' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="itemList-item itemList-item--slider">
		<a href="' . $__templater->func('link', array('media/albums', $__vars['album'], ), true) . '">
			' . $__templater->callMacro(null, 'album_list_item_thumb', array(
		'album' => $__vars['album'],
	), $__vars) . '
		</a>
	</div>
';
	return $__finalCompiled;
}
),
'album_list_item_inline_mod' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
		'forceInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->method($__vars['album'], 'canUseInlineModeration', array()) OR $__vars['forceInlineMod']) {
		$__finalCompiled .= '
		' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['album']['album_id'],
			'labelclass' => 'itemList-itemOverlayTop',
			'class' => 'js-inlineModToggle',
			'data-xf-init' => ($__templater->method($__vars['album'], 'canUseInlineModeration', array()) ? 'tooltip' : ''),
			'title' => ($__templater->method($__vars['album'], 'canUseInlineModeration', array()) ? 'Select for moderation' : ''),
			'_type' => 'option',
		))) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'album_list_item_type_icon' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="itemList-itemTypeIcon itemList-itemTypeIcon--album"></div>
';
	return $__finalCompiled;
}
),
'album_list_item_thumb' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->func('xfmg_thumbnail', array($__vars['album'], 'xfmgThumbnail--fluid', true, ), true) . '
';
	return $__finalCompiled;
}
),
'album_list_item_overlay' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => '!',
		'isChooser' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="itemList-itemOverlay">
		<div class="itemInfoRow">
			<div class="itemInfoRow-main">
				<h3 class="itemInfoRow-title">
					<a href="' . $__templater->func('link', array('media/albums' . ($__vars['isChooser'] ? '/add' : ''), $__vars['album'], ), true) . '">' . $__templater->escape($__vars['album']['title']) . '</a>
				</h3>
				<div class="itemInfoRow-status">
					<ul class="listInline listInline--bullet">
						<li>' . $__templater->fontAwesome('fa-user', array(
		'title' => $__templater->filter('Album owner', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('username_link', array($__vars['album']['User'], false, array(
		'defaultname' => $__vars['album']['username'],
	))) . '</li>
						<li>' . $__templater->fontAwesome('fa-clock', array(
		'title' => $__templater->filter('Date created', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('date', array($__vars['album']['create_date'], 'absolute', ), true) . '</li>
					</ul>
				</div>
				<div class="itemInfoRow-status">
					<ul class="listInline listInline--bullet">
						<li>' . $__templater->fontAwesome('fa-th', array(
		'title' => $__templater->filter('Items', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->filter($__vars['album']['media_count'], array(array('number_short', array()),), true) . '</li>
						<li>' . $__templater->fontAwesome('fa-thumbs-up', array(
		'title' => $__templater->filter('Reaction score', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->filter($__vars['album']['reaction_score'], array(array('number_short', array()),), true) . '</li>
						<li>' . $__templater->fontAwesome('fa-comments', array(
		'title' => $__templater->filter('Comments', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->filter($__vars['album']['comment_count'], array(array('number_short', array()),), true) . '</li>
						';
	if ($__vars['album']['album_state'] == 'deleted') {
		$__finalCompiled .= '
							<li>' . 'Deleted' . '</li>
						';
	}
	$__finalCompiled .= '
					</ul>
				</div>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'album_list_placeholders' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'start' => '1',
		'end' => '10',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = $__templater->func('range', array($__vars['start'], $__vars['end'], ), false);
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['placeholder']) {
			$__finalCompiled .= '
		<div class="itemList-item itemList-item--placeholder"></div>
	';
		}
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

' . '

' . '

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);