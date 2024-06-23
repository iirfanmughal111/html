<?php
// FROM HASH: 9e94b0b7fde1ceb7a2386712eeb15830
return array(
'macros' => array('media_status' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'block' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
					';
	if ($__vars['mediaItem']['album_id'] AND ($__vars['mediaItem']['Album'] AND ($__vars['mediaItem']['Album']['album_state'] == 'deleted'))) {
		$__compilerTemp2 .= '
						<dd class="blockStatus-message blockStatus-message--deleted">
							' . 'Media item is contained within a deleted album.' . '
							' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['mediaItem']['Album']['DeletionLog'],
		), $__vars) . '
						</dd>
					';
	}
	$__compilerTemp2 .= '
					';
	if ($__vars['mediaItem']['media_state'] == 'deleted') {
		$__compilerTemp2 .= '
						<dd class="blockStatus-message blockStatus-message--deleted">
							' . 'Media item is deleted.' . '
							' . $__templater->callMacro('deletion_macros', 'notice', array(
			'log' => $__vars['mediaItem']['DeletionLog'],
		), $__vars) . '
						</dd>
						';
	} else if ($__vars['mediaItem']['media_state'] == 'moderated') {
		$__compilerTemp2 .= '
						<dd class="blockStatus-message blockStatus-message--moderated">
							' . 'Awaiting approval before being displayed publicly.' . '
						</dd>
					';
	}
	$__compilerTemp2 .= '
					';
	if ($__vars['mediaItem']['warning_message']) {
		$__compilerTemp2 .= '
						<dd class="blockStatus-message blockStatus-message--warning">
							' . $__templater->escape($__vars['mediaItem']['warning_message']) . '
						</dd>
					';
	}
	$__compilerTemp2 .= '
					';
	if ($__templater->method($__vars['mediaItem'], 'isIgnored', array())) {
		$__compilerTemp2 .= '
						<dd class="blockStatus-message blockStatus-message--ignored">
							' . 'You are ignoring content by this member.' . '
						</dd>
					';
	}
	$__compilerTemp2 .= '
				';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
			<dl class="blockStatus">
				<dt>' . 'Status' . '</dt>
				' . $__compilerTemp2 . '
			</dl>
		';
	}
	$__vars['inner'] = $__templater->preEscaped('
		' . $__compilerTemp1 . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		<div class="block">
			<div class="block-outer">
				' . $__templater->filter($__vars['inner'], array(array('raw', array()),), true) . '
			</div>
		</div>
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['inner'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'media_content' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'mediaNotes' => array(),
		'linkMedia' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['mediaItem']['media_type'] == 'image') {
		$__finalCompiled .= '
		<div class="media-container-image js-mediaContainerImage">
			';
		if ($__templater->isTraversable($__vars['mediaNotes'])) {
			foreach ($__vars['mediaNotes'] AS $__vars['note']) {
				$__finalCompiled .= '
				' . $__templater->callMacro(null, 'note_view', array(
					'mediaItem' => $__vars['mediaItem'],
					'note' => $__vars['note'],
				), $__vars) . '
			';
			}
		}
		$__finalCompiled .= '
			';
		$__vars['imageHtml'] = $__templater->preEscaped('
				<img src="' . $__templater->func('link', array('media/full', $__vars['mediaItem'], array('d' => ($__vars['mediaItem']['last_edit_date'] ?: null), ), ), true) . '"
					 width="' . $__templater->escape($__vars['mediaItem']['Attachment']['width']) . '" height="' . $__templater->escape($__vars['mediaItem']['Attachment']['height']) . '"
					 alt="' . $__templater->escape($__vars['mediaItem']['title']) . '" class="js-mediaImage" />
			');
		$__finalCompiled .= '
			';
		if ($__vars['linkMedia']) {
			$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('media', $__vars['mediaItem'], ), true) . '">' . $__templater->filter($__vars['imageHtml'], array(array('raw', array()),), true) . '</a>
			';
		} else {
			$__finalCompiled .= '
				' . $__templater->filter($__vars['imageHtml'], array(array('raw', array()),), true) . '
			';
		}
		$__finalCompiled .= '
		</div>
	';
	} else if ($__vars['mediaItem']['media_type'] == 'video') {
		$__finalCompiled .= '
		<div class="bbMediaWrapper">
			<div class="bbMediaWrapper-inner">
				<video preload="metadata" controls poster="' . $__templater->escape($__vars['mediaItem']['poster_url']) . '" data-video-type="video">
					<source src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getVideoUrl', array())) . '" type="video/mp4" />
					<div class="bbMediaWrapper-fallback">' . 'Your browser is not able to display this video.' . '</div>
				</video>
			</div>
		</div>
	';
	} else if ($__vars['mediaItem']['media_type'] == 'audio') {
		$__finalCompiled .= '
		';
		$__vars['audioItem'] = $__templater->preEscaped('
			<video preload="metadata" controls poster="' . $__templater->escape($__vars['mediaItem']['poster_url']) . '" data-video-type="audio">
				<source src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getAudioUrl', array())) . '" type="audio/mpeg" />
				<div class="bbMediaWrapper-fallback">' . 'Your browser is not able to play this audio.' . '</div>
			</video>
		');
		$__finalCompiled .= '

		';
		if ($__templater->method($__vars['mediaItem'], 'hasPoster', array()) OR $__templater->method($__vars['mediaItem'], 'hasThumbnail', array())) {
			$__finalCompiled .= '
			<div class="bbMediaWrapper">
				<div class="bbMediaWrapper-inner">
					' . $__templater->filter($__vars['audioItem'], array(array('raw', array()),), true) . '
				</div>
			</div>
		';
		} else {
			$__finalCompiled .= '
			' . $__templater->filter($__vars['audioItem'], array(array('raw', array()),), true) . '
		';
		}
		$__finalCompiled .= '
	';
	} else if ($__vars['mediaItem']['media_type'] == 'embed') {
		$__finalCompiled .= '
		' . $__templater->func('bb_code', array($__vars['mediaItem']['media_tag'], 'xfmg_media', $__vars['mediaItem'], ), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'media_film_strip' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'filmStripParams' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if (!$__templater->test($__vars['filmStripParams']['mediaItems'], 'empty', array())) {
		$__finalCompiled .= '
		';
		$__templater->includeJs(array(
			'src' => 'xfmg/film_strip.js',
			'min' => '1',
		));
		$__finalCompiled .= '
		';
		$__templater->includeCss('xfmg_media_list.less');
		$__finalCompiled .= '

		<div class="block-outer">
			<div class="block-outer-middle">
				<div class="itemList itemList--strip js-filmStrip">
					<a data-xf-click="inserter" data-replace=".js-filmStrip"
						tabindex="0" role="button"
						data-inserter-href="' . $__templater->func('link', array('media/film-strip-jump', $__vars['mediaItem'], array('direction' => 'prev', 'jump_from_id' => $__vars['filmStripParams']['firstItem']['media_id'], ), ), true) . '"
						rel="nofollow"
						class="js-filmStrip-button itemList-button itemList-button--prev' . ((!$__vars['filmStripParams']['hasPrev']) ? ' is-disabled' : '') . '">

						<i class="itemList-button-icon"></i>
					</a>

					';
		if ($__templater->isTraversable($__vars['filmStripParams']['mediaItems'])) {
			foreach ($__vars['filmStripParams']['mediaItems'] AS $__vars['item']) {
				$__finalCompiled .= '
						<div class="js-filmStrip-item itemList-item">
							<a href="' . $__templater->func('link', array('media', $__vars['item'], ), true) . '">
								' . $__templater->func('xfmg_thumbnail', array($__vars['item'], 'xfmgThumbnail--fluid xfmgThumbnail--iconSmallest' . (($__vars['item']['media_id'] == $__vars['mediaItem']['media_id']) ? ' is-selected' : ''), true, ), true) . '
							</a>
						</div>
					';
			}
		}
		$__finalCompiled .= '

					<a data-xf-click="inserter" data-replace=".js-filmStrip"
						tabindex="0" role="button"
						data-inserter-href="' . $__templater->func('link', array('media/film-strip-jump', $__vars['mediaItem'], array('direction' => 'next', 'jump_from_id' => $__vars['filmStripParams']['lastItem']['media_id'], ), ), true) . '"
						rel="nofollow"
						class="js-filmStrip-button itemList-button itemList-button--next' . ((!$__vars['filmStripParams']['hasNext']) ? ' is-disabled' : '') . '">

						<i class="itemList-button-icon"></i>
					</a>
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'media_film_strip_placeholders' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'count' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['count']) {
		$__finalCompiled .= '
		';
		$__compilerTemp1 = $__templater->func('range', array(1, $__vars['count'], ), false);
		if ($__templater->isTraversable($__compilerTemp1)) {
			foreach ($__compilerTemp1 AS $__vars['null']) {
				$__finalCompiled .= '
			<div class="js-filmStrip-item itemList-item itemList-item--placeholder">
				<img src="' . $__templater->func('transparent_img', array(), true) . '" />
			</div>
		';
			}
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'note_view' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'note' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="mediaNote js-mediaNote"
		data-note-data="' . $__templater->filter($__vars['note']['note_data'], array(array('json', array()),), true) . '"
		data-note-id="' . $__templater->escape($__vars['note']['note_id']) . '"></div>
	<div class="mediaNote-tooltip js-mediaNoteTooltip' . $__templater->escape($__vars['note']['note_id']) . '">
		<div class="noteTooltip-row">
			';
	if ($__vars['note']['note_type'] == 'note') {
		$__finalCompiled .= '
				' . $__templater->func('structured_text', array($__vars['note']['note_text'], ), true) . '
			';
	} else if ($__vars['note']['note_type'] == 'user_tag') {
		$__finalCompiled .= '
				<div class="contentRow contentRow--alignMiddle">
					<div class="contentRow-figure">
						' . $__templater->func('avatar', array($__vars['note']['TaggedUser'], 'xs', false, array(
			'defaultname' => $__vars['note']['username'],
		))) . '
					</div>
					<div class="contentRow-main contentRow-main--close">
						' . $__templater->func('username_link', array($__vars['note']['TaggedUser'], true, array(
			'defaultname' => $__vars['note']['username'],
		))) . '
						';
		if ($__vars['note']['TaggedUser']) {
			$__finalCompiled .= '
							<div class="contentRow-minor">
								' . $__templater->func('user_title', array($__vars['note']['TaggedUser'], false, array(
			))) . '
							</div>
						';
		}
		$__finalCompiled .= '
					</div>
				</div>
			';
	}
	$__finalCompiled .= '
		</div>
		<div class="noteTooltip-footer noteTooltip-footer--smallest">
			<ul class="listInline listInline--bullet">
				<li>' . $__templater->fontAwesome('fa-user', array(
		'title' => $__templater->filter('Note by', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('username_link', array($__vars['note']['User'], false, array(
		'defaultname' => $__vars['note']['username'],
		'class' => 'u-concealed',
	))) . '</li>
				';
	if ($__templater->method($__vars['note'], 'canApproveReject', array())) {
		$__finalCompiled .= '
					<li>
						' . $__templater->fontAwesome('fa-check', array(
			'title' => $__templater->filter('Approve', array(array('for_attr', array()),), false),
		)) . '
						<a href="' . $__templater->func('link', array('media/note-approve', $__vars['mediaItem'], array('note_id' => $__vars['note']['note_id'], 't' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
							class="u-concealed">
							' . 'Approve' . '
						</a>
					</li>
					<li>
						' . $__templater->fontAwesome('fa-times', array(
			'title' => $__templater->filter('Reject', array(array('for_attr', array()),), false),
		)) . '
						<a href="' . $__templater->func('link', array('media/note-reject', $__vars['mediaItem'], array('note_id' => $__vars['note']['note_id'], 't' => $__templater->func('csrf_token', array(), false), ), ), true) . '"
							class="u-concealed">
							' . 'Reject' . '
						</a>
					</li>
				';
	} else if ($__templater->method($__vars['note'], 'canEdit', array())) {
		$__finalCompiled .= '
					<li>' . $__templater->fontAwesome('fa-pencil', array(
			'title' => $__templater->filter('Edit', array(array('for_attr', array()),), false),
		)) . ' <a href="javascript:" class="u-concealed js-mediaNoteTooltipEdit">' . 'Edit' . '</a></li>
				';
	}
	$__finalCompiled .= '
			</ul>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'info_sidebar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'row' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	if ($__vars['mediaItem']['Category']) {
		$__compilerTemp1 .= '
			<dl class="pairs pairs--justified">
				<dt>' . 'Category' . '</dt>
				<dd><a href="' . $__templater->func('link', array('media/categories', $__vars['mediaItem']['Category'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['Category']['title']) . '</a></dd>
			</dl>
		';
	}
	$__compilerTemp2 = '';
	if ($__vars['mediaItem']['Album']) {
		$__compilerTemp2 .= '
			<dl class="pairs pairs--justified">
				<dt>' . 'Album' . '</dt>
				<dd><a href="' . $__templater->func('link', array('media/albums', $__vars['mediaItem']['Album'], ), true) . '">' . $__templater->escape($__vars['mediaItem']['Album']['title']) . '</a></dd>
			</dl>
		';
	}
	$__vars['info'] = $__templater->preEscaped('
		' . $__compilerTemp1 . '
		' . $__compilerTemp2 . '

		<dl class="pairs pairs--justified">
			<dt>' . 'Added by' . '</dt>
			<dd>' . $__templater->func('username_link', array($__vars['mediaItem']['User'], false, array(
		'defaultname' => $__vars['mediaItem']['username'],
	))) . '</dd>
		</dl>

		<dl class="pairs pairs--justified">
			<dt>' . 'Date added' . '</dt>
			<dd>' . $__templater->func('date_dynamic', array($__vars['mediaItem']['media_date'], array(
	))) . '</dd>
		</dl>

		<dl class="pairs pairs--justified">
			<dt>' . 'View count' . '</dt>
			<dd>' . $__templater->filter($__vars['mediaItem']['view_count'], array(array('number', array()),), true) . '</dd>
		</dl>

		<dl class="pairs pairs--justified">
			<dt>' . 'Comment count' . '</dt>
			<dd>' . $__templater->filter($__vars['mediaItem']['comment_count'], array(array('number', array()),), true) . '</dd>
		</dl>

		<dl class="pairs pairs--justified">
			<dt>' . 'Rating' . '</dt>
			<dd>
				' . $__templater->callMacro('rating_macros', 'stars_text', array(
		'rating' => $__vars['mediaItem']['rating_avg'],
		'count' => $__vars['mediaItem']['rating_count'],
		'rowClass' => 'ratingStarsRow--textBlock',
	), $__vars) . '
			</dd>
		</dl>

		' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'xfmgMediaFields',
		'group' => 'below_info',
		'onlyInclude' => ($__vars['mediaItem']['category_id'] ? $__vars['mediaItem']['Category']['field_cache'] : $__vars['mediaItem']['Album']['field_cache']),
		'set' => $__vars['mediaItem']['custom_fields'],
		'valueClass' => 'pairs pairs--justified',
	), $__vars) . '
	');
	$__finalCompiled .= '

	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader">' . 'Media information' . '</h3>
				<div class="block-body block-row">
					' . $__templater->filter($__vars['info'], array(array('raw', array()),), true) . '
				</div>
			</div>
		</div>
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['info'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'extra_info_sidebar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'row' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['info'] = $__templater->preEscaped('
		' . $__templater->callMacro('custom_fields_macros', 'custom_fields_view', array(
		'type' => 'xfmgMediaFields',
		'group' => 'extra_info_sidebar_block',
		'onlyInclude' => ($__vars['mediaItem']['category_id'] ? $__vars['mediaItem']['Category']['field_cache'] : $__vars['mediaItem']['Album']['field_cache']),
		'set' => $__vars['mediaItem']['custom_fields'],
		'valueClass' => 'pairs pairs--justified',
	), $__vars) . '
	');
	$__finalCompiled .= '
	';
	if ($__vars['row']) {
		$__finalCompiled .= '
		';
		$__compilerTemp1 = '';
		$__compilerTemp1 .= '
							' . $__templater->filter($__vars['info'], array(array('raw', array()),), true) . '
						';
		if (strlen(trim($__compilerTemp1)) > 0) {
			$__finalCompiled .= '
			<div class="block">
				<div class="block-container">
					<h3 class="block-minorHeader">' . 'Extra information' . '</h3>
					<div class="block-body block-row">
						' . $__compilerTemp1 . '
					</div>
				</div>
			</div>
		';
		}
		$__finalCompiled .= '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->filter($__vars['info'], array(array('raw', array()),), true) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'additional_sidebar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
			';
	$__compilerTemp2 = $__templater->method($__vars['mediaItem'], 'getExtraFieldBlocks', array());
	if ($__templater->isTraversable($__compilerTemp2)) {
		foreach ($__compilerTemp2 AS $__vars['fieldId'] => $__vars['definition']) {
			$__compilerTemp1 .= '
				';
			if ($__templater->method($__vars['definition'], 'hasValue', array($__vars['mediaItem']['custom_fields'][$__vars['fieldId']], ))) {
				$__compilerTemp1 .= '
					<div class="block">
						<div class="block-container">
							<h3 class="block-minorHeader">' . $__templater->escape($__vars['definition']['title']) . '</h3>
							<div class="block-body block-row">
								' . $__templater->callMacro('custom_fields_macros', 'custom_field_value', array(
					'definition' => $__vars['definition'],
					'value' => $__vars['mediaItem']['custom_fields'][$__vars['fieldId']],
				), $__vars) . '
							</div>
						</div>
					</div>
				';
			}
			$__compilerTemp1 .= '
			';
		}
	}
	$__compilerTemp1 .= '
		';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		' . $__compilerTemp1 . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'exif_sidebar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'row' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['mediaItem']['exif_data']) {
		$__finalCompiled .= '
		';
		$__compilerTemp1 = '';
		if ($__vars['mediaItem']['exif']['device']) {
			$__compilerTemp1 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'Device' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['device']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp2 = '';
		if ($__vars['mediaItem']['exif']['aperture']) {
			$__compilerTemp2 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'Aperture' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['aperture']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp3 = '';
		if ($__vars['mediaItem']['exif']['focal']) {
			$__compilerTemp3 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'Focal length' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['focal']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp4 = '';
		if ($__vars['mediaItem']['exif']['exposure']) {
			$__compilerTemp4 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'Exposure time' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['exposure']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp5 = '';
		if ($__vars['mediaItem']['exif']['iso']) {
			$__compilerTemp5 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'ISO' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['iso']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp6 = '';
		if ($__vars['mediaItem']['exif']['flash']) {
			$__compilerTemp6 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'Flash' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['flash']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp7 = '';
		if ($__vars['mediaItem']['exif']['FileName']) {
			$__compilerTemp7 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'Filename' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['Attachment']['Data']['filename']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp8 = '';
		if ($__vars['mediaItem']['exif']['file_size']) {
			$__compilerTemp8 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'File size' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['file_size']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp9 = '';
		if ($__vars['mediaItem']['exif']['date_taken']) {
			$__compilerTemp9 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'Date taken' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['date_taken']) . '
					</dd>
				</dl>
			';
		}
		$__compilerTemp10 = '';
		if ($__vars['mediaItem']['exif']['dimensions']) {
			$__compilerTemp10 .= '
				<dl class="pairs pairs--justified">
					<dt>' . 'Dimensions' . '</dt>
					<dd>
						' . $__templater->escape($__vars['mediaItem']['exif']['dimensions']) . '
					</dd>
				</dl>
			';
		}
		$__vars['info'] = $__templater->preEscaped('
			' . $__compilerTemp1 . '
			' . $__compilerTemp2 . '
			' . $__compilerTemp3 . '
			' . $__compilerTemp4 . '
			' . $__compilerTemp5 . '
			' . $__compilerTemp6 . '
			' . $__compilerTemp7 . '
			' . $__compilerTemp8 . '
			' . $__compilerTemp9 . '
			' . $__compilerTemp10 . '
		');
		$__finalCompiled .= '
		';
		if ($__vars['row']) {
			$__finalCompiled .= '
			';
			$__compilerTemp11 = '';
			$__compilerTemp11 .= '
								' . $__templater->filter($__vars['info'], array(array('raw', array()),), true) . '
							';
			if (strlen(trim($__compilerTemp11)) > 0) {
				$__finalCompiled .= '
				<div class="block">
					<div class="block-container">
						<h3 class="block-minorHeader">' . 'Image metadata' . '</h3>
						<div class="block-body block-row">
							' . $__compilerTemp11 . '
						</div>
					</div>
				</div>
			';
			}
			$__finalCompiled .= '
		';
		} else {
			$__finalCompiled .= '
			' . $__templater->filter($__vars['info'], array(array('raw', array()),), true) . '
		';
		}
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'user_tags_sidebar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaNotes' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__templater->isTraversable($__vars['mediaNotes'])) {
		foreach ($__vars['mediaNotes'] AS $__vars['note']) {
			$__compilerTemp1 .= '
							';
			if (($__vars['note']['note_type'] == 'user_tag') AND ($__vars['note']['tag_state'] == 'approved')) {
				$__compilerTemp1 .= '
								<li class="block-row">
									<div class="contentRow">
										<div class="contentRow-figure">
											' . $__templater->func('avatar', array($__vars['note']['TaggedUser'], 'xs', false, array(
					'defaultname' => $__vars['note']['username'],
				))) . '
										</div>
										<div class="contentRow-main contentRow-main--close">
											' . $__templater->func('username_link', array($__vars['note']['TaggedUser'], true, array(
					'defaultname' => $__vars['note']['username'],
				))) . '
											<div class="contentRow-minor">
												' . $__templater->func('user_title', array($__vars['note']['TaggedUser'], false, array(
				))) . '
											</div>
										</div>
									</div>
								</li>
							';
			}
			$__compilerTemp1 .= '
						';
		}
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="block">
			<div class="block-container">
				<h3 class="block-minorHeader">' . 'Tagged in this image' . $__vars['xf']['language']['ellipsis'] . '</h3>
				<div class="block-body">
					' . $__compilerTemp1 . '
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'share_sidebar' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'mediaItem' => '!',
		'row' => true,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
					';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
								' . $__templater->callMacro('share_page_macros', 'buttons', array(
		'iconic' => true,
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__compilerTemp1 .= '
						<h3 class="block-minorHeader">' . 'Share this media' . '</h3>
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp2 . '
						</div>
					';
	}
	$__compilerTemp1 .= '
					';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
								';
	if ($__vars['mediaItem']['media_type'] == 'image') {
		$__compilerTemp3 .= '
									' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
			'label' => 'Copy image link',
			'text' => $__templater->func('link', array('canonical:media/full', $__vars['mediaItem'], ), false),
			'successText' => 'Link copied to clipboard.',
		), $__vars) . '

									' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
			'label' => 'Copy image BB code',
			'text' => '[IMG width="' . $__vars['mediaItem']['Attachment']['Data']['width'] . 'px" height="' . $__vars['mediaItem']['Attachment']['Data']['height'] . 'px"]' . $__templater->func('link', array('canonical:media/full', $__vars['mediaItem'], ), false) . '[/IMG]',
		), $__vars) . '
								';
	} else if ($__vars['mediaItem']['media_type'] == 'embed') {
		$__compilerTemp3 .= '
									' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
			'label' => 'Copy MEDIA BB code',
			'text' => $__vars['mediaItem']['media_tag'],
		), $__vars) . '
								';
	}
	$__compilerTemp3 .= '

								';
	if ($__vars['mediaItem']['thumbnail_date']) {
		$__compilerTemp3 .= '
									' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
			'label' => 'Copy URL BB code with thumbnail',
			'text' => '[URL="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), false) . '"][IMG width="' . $__vars['xf']['options']['xfmgThumbnailDimensions']['width'] . 'px" height="' . $__vars['xf']['options']['xfmgThumbnailDimensions']['height'] . 'px"]' . $__templater->method($__vars['mediaItem'], 'getCurrentThumbnailUrl', array(true, )) . '[/IMG][/URL]',
		), $__vars) . '
								';
	}
	$__compilerTemp3 .= '

								' . $__templater->callMacro('share_page_macros', 'share_clipboard_input', array(
		'label' => 'Copy GALLERY BB code',
		'text' => '[GALLERY=media, ' . $__vars['mediaItem']['media_id'] . '][/GALLERY]',
	), $__vars) . '
							';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp1 .= '
						<div class="block-body block-row block-row--separated">
							' . $__compilerTemp3 . '
						</div>
					';
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		<div class="block">
			<div class="block-container">
				' . $__compilerTemp1 . '
			</div>
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