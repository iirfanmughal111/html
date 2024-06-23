<?php
// FROM HASH: 090bfa5d694805b8ff1b24967f9dd5ac
return array(
'macros' => array('media_create_message' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'transcoding' => $__vars['transcoding'],
		'pendingApproval' => $__vars['pendingApproval'],
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['transcoding']) {
		$__finalCompiled .= '
		<div class="blockMessage blockMessage--important">
			' . 'One or more of your uploaded items needs to be processed before they can be added to the gallery. You will receive an alert once processing is finished for each item.' . '
		</div>
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['pendingApproval']) {
		$__finalCompiled .= '
		<div class="blockMessage blockMessage--important">
			' . 'Your content has been submitted and will be displayed pending approval by a moderator.' . '
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'media_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItems' => '!',
		'allowInlineMod' => true,
		'forceInlineMod' => false,
		'prevPage' => null,
		'nextPage' => null,
		'setupLightbox' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('xfmg_media_list.less');
	$__finalCompiled .= '

	';
	if ($__vars['setupLightbox']) {
		$__finalCompiled .= '
		' . $__templater->callMacro(null, 'lightbox_macros::setup', array(
			'canViewAttachments' => true,
		), $__vars) . '
		';
		$__templater->includeJs(array(
			'src' => 'xf/embed.js',
			'min' => '1',
		));
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	<div class="itemList js-lbContainer"
		data-xf-init="' . ($__vars['setupLightbox'] ? 'lightbox' : '') . '"
		data-lb-infobar="0"
		data-lb-slide-show="0"
		data-lb-thumbs-auto="0"
		data-lb-universal="1"
		data-lb-id="xfmg-media"
		data-lb-history="1"
		data-lb-prev="' . $__templater->escape($__vars['prevPage']) . '"
		data-lb-next="' . $__templater->escape($__vars['nextPage']) . '">
		';
	if ($__templater->isTraversable($__vars['mediaItems'])) {
		foreach ($__vars['mediaItems'] AS $__vars['mediaItem']) {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'media_list_item', array(
				'mediaItem' => $__vars['mediaItem'],
				'allowInlineMod' => $__vars['allowInlineMod'],
				'forceInlineMod' => $__vars['forceInlineMod'],
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '
		' . $__templater->callMacro(null, 'media_list_placeholders', array(), $__vars) . '
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
	$__vars['sortOrders'] = array('media_date' => 'Date', 'comment_count' => 'Comments', 'rating_weighted' => 'Rating', 'reaction_score' => 'Reaction score', 'view_count' => 'Views', );
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
'media_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'allowInlineMod' => true,
		'forceInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->includeCss('xfmg_media_view.less');
	$__finalCompiled .= '
	<div class="itemList-item js-inlineModContainer' . ($__templater->method($__vars['mediaItem'], 'isIgnored', array()) ? ' is-ignored' : '') . '" data-author="' . ($__templater->escape($__vars['mediaItem']['User']['username']) ?: $__templater->escape($__vars['mediaItem']['username'])) . '">
		';
	if ($__vars['allowInlineMod']) {
		$__finalCompiled .= '
			' . $__templater->callMacro(null, 'media_list_item_inline_mod', array(
			'mediaItem' => $__vars['mediaItem'],
			'forceInlineMod' => $__vars['forceInlineMod'],
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
		' . $__templater->callMacro(null, 'media_list_item_type_icon', array(
		'mediaItem' => $__vars['mediaItem'],
	), $__vars) . '
		<a href="' . $__templater->func('link', array('media', $__vars['mediaItem'], ), true) . '" class="js-lbImage"
			data-src="' . $__templater->escape($__vars['mediaItem']['lightbox_src']) . '"
			data-type="' . $__templater->escape($__vars['mediaItem']['lightbox_type']) . '"
			data-lb-type-override="' . ((($__vars['mediaItem']['lightbox_type'] == 'ajax')) ? 'video' : '') . '"
			data-lb-sidebar="1"
			data-lb-caption-desc="' . $__templater->func('snippet', array($__vars['mediaItem']['description'], 100, array('stripBbCode' => true, ), ), true) . '"
			data-lb-caption-href="' . $__templater->func('link', array('media', $__vars['mediaItem'], ), true) . '">
			' . $__templater->callMacro(null, 'media_list_item_thumb', array(
		'mediaItem' => $__vars['mediaItem'],
	), $__vars) . '
		</a>
		' . $__templater->callMacro(null, 'media_list_item_overlay', array(
		'mediaItem' => $__vars['mediaItem'],
	), $__vars) . '
	</div>
';
	return $__finalCompiled;
}
),
'media_list_item_struct_item' => array(
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
		$__vars['link'] = $__templater->preEscaped($__templater->func('link', array((($__vars['item']['content_type'] == 'xfmg_media') ? 'media/media-comments/unread' : 'media/album-comments/unread'), $__vars['item'], ), true));
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
		';
		$__vars['link'] = $__templater->preEscaped($__templater->func('link', array((($__vars['item']['content_type'] == 'xfmg_media') ? 'media' : 'media/albums'), $__vars['item'], ), true));
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
	<div class="structItem structItem--middle' . ((($__vars['item']['LastComment'] AND $__templater->method($__vars['item']['LastComment'], 'isUnread', array()))) ? ' is-unread' : '') . '" data-author="' . ($__templater->escape($__vars['item']['User']['username']) ?: $__templater->escape($__vars['item']['username'])) . '">
		<div class="structItem-cell structItem-cell--icon structItem-cell--iconFixedSmall">
			<div class="structItem-iconContainer">
				<a href="' . $__templater->escape($__vars['link']) . '">
					' . $__templater->func('xfmg_thumbnail', array($__vars['item'], 'xfmgThumbnail--small', true, ), true) . '
				</a>
				' . $__templater->func('avatar', array($__vars['item']['User'], 's', false, array(
		'href' => '',
		'class' => 'avatar--separated structItem-secondaryIcon',
	))) . '
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
								<li>';
		$__compilerTemp2 = array();
		if ($__vars['item']['content_type'] == 'xfmg_media') {
			$__compilerTemp2[] = array(
				'name' => $__vars['chooseName'] . '[]',
				'value' => $__vars['item']['media_id'],
				'class' => 'js-chooseItem',
				'_type' => 'option',
			);
		} else {
			$__compilerTemp2[] = array(
				'name' => $__vars['chooseName'] . '[]',
				'value' => $__vars['item']['album_id'],
				'class' => 'js-chooseItem',
				'_type' => 'option',
			);
		}
		$__compilerTemp1 .= $__templater->formCheckBox(array(
			'standalone' => 'true',
		), $__compilerTemp2) . '</li>
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
					';
	if ($__vars['item']['content_type'] == 'xfmg_media') {
		$__finalCompiled .= '
						<li>' . 'Media item' . '</li>
						<li>' . $__templater->func('date_dynamic', array($__vars['item']['media_date'], array(
		))) . '</li>
					';
	} else {
		$__finalCompiled .= '
						<li>' . 'Album' . '</li>
						<li>' . $__templater->func('date_dynamic', array($__vars['item']['create_date'], array(
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					';
	if ($__vars['item']['category_id'] AND $__vars['item']['Category']) {
		$__finalCompiled .= '
						<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['item']['Category']['title']) . '</li>
					';
	}
	$__finalCompiled .= '
					';
	if (($__vars['item']['content_type'] == 'xfmg_media') AND $__vars['item']['Album']) {
		$__finalCompiled .= '
						<li>' . 'Album' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['item']['Album']['title']) . '</li>
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
				<a href="' . $__templater->func('link', array('media/comments', $__vars['item']['LastComment'], ), true) . '" rel="nofollow">' . $__templater->func('date_dynamic', array($__vars['item']['last_comment_date'], array(
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
'media_list_item_slider' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="itemList-item itemList-item--slider">
		<a href="' . $__templater->func('link', array('media', $__vars['mediaItem'], ), true) . '">
			' . $__templater->callMacro(null, 'media_list_item_thumb', array(
		'mediaItem' => $__vars['mediaItem'],
	), $__vars) . '
		</a>
	</div>
';
	return $__finalCompiled;
}
),
'media_list_item_inline_mod' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'forceInlineMod' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->method($__vars['mediaItem'], 'canUseInlineModeration', array()) OR $__vars['forceInlineMod']) {
		$__finalCompiled .= '
		' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['mediaItem']['media_id'],
			'labelclass' => 'itemList-itemOverlayTop',
			'class' => 'js-inlineModToggle',
			'data-xf-init' => ($__templater->method($__vars['mediaItem'], 'canUseInlineModeration', array()) ? 'tooltip' : ''),
			'title' => ($__templater->method($__vars['mediaItem'], 'canUseInlineModeration', array()) ? 'Select for moderation' : ''),
			'_type' => 'option',
		))) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'media_list_item_type_icon' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['mediaItem']['media_type'] == 'embed') {
		$__finalCompiled .= '
		<div class="itemList-itemTypeIcon itemList-itemTypeIcon--embed itemList-itemTypeIcon--embed--' . $__templater->escape($__vars['mediaItem']['media_site_id']) . '"></div>
	';
	} else {
		$__finalCompiled .= '
		<div class="itemList-itemTypeIcon itemList-itemTypeIcon--' . $__templater->escape($__vars['mediaItem']['media_type']) . '"></div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'media_list_item_thumb' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	' . $__templater->func('xfmg_thumbnail', array($__vars['mediaItem'], 'xfmgThumbnail--fluid', true, ), true) . '
';
	return $__finalCompiled;
}
),
'media_list_item_overlay' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="itemList-itemOverlay">
		<div class="itemInfoRow">
			<div class="itemInfoRow-main">
				<h3 class="itemInfoRow-title">
					<a href="' . $__templater->func('link', array('media', $__vars['mediaItem'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['title']) . '</a>
				</h3>
				<div class="itemInfoRow-status">
					<ul class="listInline listInline--bullet">
						<li>' . $__templater->fontAwesome('fa-user', array(
		'title' => $__templater->filter('Media owner', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('username_link', array($__vars['mediaItem']['User'], false, array(
		'defaultname' => $__vars['mediaItem']['username'],
	))) . '</li>
						<li>' . $__templater->fontAwesome('fa-clock', array(
		'title' => $__templater->filter('Date added', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('date', array($__vars['mediaItem']['media_date'], 'absolute', ), true) . '</li>
					</ul>
				</div>
				<div class="itemInfoRow-status">
					<ul class="listInline listInline--bullet">
						<li>' . $__templater->fontAwesome('fa-thumbs-up', array(
		'title' => $__templater->filter('Reaction score', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->filter($__vars['mediaItem']['reaction_score'], array(array('number_short', array()),), true) . '</li>
						<li>' . $__templater->fontAwesome('fa-comments', array(
		'title' => $__templater->filter('Comments', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->filter($__vars['mediaItem']['comment_count'], array(array('number_short', array()),), true) . '</li>
						';
	if ($__vars['mediaItem']['media_state'] == 'deleted') {
		$__finalCompiled .= '
							<li>' . 'Deleted' . '</li>
						';
	} else if ($__vars['mediaItem']['media_state'] == 'moderated') {
		$__finalCompiled .= '
							<li>' . 'Moderated' . '</li>
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
'media_list_placeholders' => array(
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

' . '

' . '

';
	return $__finalCompiled;
}
);