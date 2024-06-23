<?php
// FROM HASH: 5e876ff301dc9bc72532de38b0f8d1ea
return array(
'macros' => array('media_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'page' => '!',
		'mediaItems' => '!',
		'listClass' => '!',
		'link' => '!',
		'hasMore' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__vars['page'] == 1) {
		$__finalCompiled .= '
		';
		$__templater->wrapTemplate('xfmg_dialog_wrapper', $__vars);
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['mediaItems'], 'empty', array())) {
		$__finalCompiled .= '
		<div class="' . $__templater->escape($__vars['listClass']) . ' itemList itemList--picker">
			';
		if ($__templater->isTraversable($__vars['mediaItems'])) {
			foreach ($__vars['mediaItems'] AS $__vars['mediaItem']) {
				$__finalCompiled .= '
				<div data-type="media" data-id="' . $__templater->escape($__vars['mediaItem']['media_id']) . '" class="itemList-item">
					' . $__templater->callMacro(null, 'item_display', array(
					'item' => $__vars['mediaItem'],
				), $__vars) . '
				</div>
			';
			}
		}
		$__finalCompiled .= '

			' . $__templater->callMacro('xfmg_media_list_macros', 'media_list_placeholders', array(), $__vars) . '

			' . $__templater->callMacro('xfmg_editor_dialog_gallery', 'footer', array(
			'link' => $__vars['link'],
			'append' => '.' . $__vars['listClass'],
			'page' => $__vars['page'],
			'hasMore' => $__vars['hasMore'],
		), $__vars) . '
		</div>
	';
	} else {
		$__finalCompiled .= '
		<div class="blockMessage">' . 'No items have been added yet.' . '</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'album_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'page' => '!',
		'albums' => '!',
		'listClass' => '!',
		'link' => '!',
		'hasMore' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	if ($__vars['page'] == 1) {
		$__finalCompiled .= '
		';
		$__templater->wrapTemplate('xfmg_dialog_wrapper', $__vars);
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	if (!$__templater->test($__vars['albums'], 'empty', array())) {
		$__finalCompiled .= '
		<div class="' . $__templater->escape($__vars['listClass']) . ' itemList itemList--picker">
			';
		if ($__templater->isTraversable($__vars['albums'])) {
			foreach ($__vars['albums'] AS $__vars['album']) {
				$__finalCompiled .= '
				<div data-type="album" data-id="' . $__templater->escape($__vars['album']['album_id']) . '" class="itemList-item' . ((!$__vars['album']['thumbnail_date']) ? ' itemList-item--noThumb' : '') . '">
					' . $__templater->callMacro('xfmg_editor_dialog_gallery', 'item_display', array(
					'item' => $__vars['album'],
				), $__vars) . '
				</div>
			';
			}
		}
		$__finalCompiled .= '

			' . $__templater->callMacro('xfmg_media_list_macros', 'media_list_placeholders', array(), $__vars) . '

			' . $__templater->callMacro('xfmg_editor_dialog_gallery', 'footer', array(
			'link' => $__vars['link'],
			'append' => '.' . $__vars['listClass'],
			'page' => $__vars['page'],
			'hasMore' => $__vars['hasMore'],
		), $__vars) . '
		</div>
	';
	} else {
		$__finalCompiled .= '
		<div class="blockMessage">' . 'No items have been added yet.' . '</div>
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'item_display' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'item' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['id'] = $__templater->func('unique_id', array(), false);
	$__finalCompiled .= '
	<input type="checkbox" class="itemList-checkbox js-mediaPicker" id="' . $__templater->escape($__vars['id']) . '" value="1" />
	<label for="' . $__templater->escape($__vars['id']) . '">' . $__templater->func('xfmg_thumbnail', array($__vars['item'], 'xfmgThumbnail--fluid', ), true) . '</label>
	<div class="itemList-itemOverlay">
		<div class="itemInfoRow">
			<div class="itemInfoRow-main">
				<h3 class="itemInfoRow-title">' . $__templater->escape($__vars['item']['title']) . '</h3>
			</div>
		</div>
	</div>
';
	return $__finalCompiled;
}
),
'footer' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'link' => '!',
		'append' => '!',
		'page' => '1',
		'hasMore' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['hasMore']) {
		$__finalCompiled .= '
		<div class="itemList-footer js-paneLoadMore">
			' . $__templater->button('
				' . 'More' . $__vars['xf']['language']['ellipsis'] . '
			', array(
			'data-xf-click' => 'inserter',
			'data-inserter-href' => $__templater->func('link', array($__vars['link'], null, array('page' => $__vars['page'] + 1, ), ), false),
			'data-append' => $__vars['append'],
			'data-replace' => '.js-paneLoadMore',
			'data-animate-replace' => '0',
		), '', array(
		)) . '
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
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Embed media');
	$__finalCompiled .= '

';
	$__templater->includeJs(array(
		'src' => 'xfmg/editor.js',
		'min' => '1',
	));
	$__finalCompiled .= '
';
	$__templater->includeCss('xfmg_editor_dialog.less');
	$__finalCompiled .= '

<form class="block" id="xfmg_editor_dialog_form">
	<div class="block-container">
		<h2 class="block-minorTabHeader tabs embedTabs hScroller"
			data-xf-init="tabs h-scroller"
			data-panes=".js-embedMediaPanes"
			role="tablist">

			<span class="hScroller-scroll">
				<a class="tabs-tab embedTabs-tab is-active js-yourMediaTab" role="tab" id="yourMedia">
					<span class="embedTabs-tabLabel">' . 'Your media' . '</span>
					<span class="badge badge--highlighted js-tabCounter">0</span>
				</a>
				';
	if ($__vars['xf']['visitor']['xfmg_album_count']) {
		$__finalCompiled .= '
					<a class="tabs-tab embedTabs-tab js-yourAlbumsTab" role="tab" id="yourAlbums">
						<span class="embedTabs-tabLabel">' . 'Your albums' . '</span>
						<span class="badge badge--highlighted js-tabCounter">0</span>
					</a>
				';
	}
	$__finalCompiled .= '
				<a class="tabs-tab embedTabs-tab js-browseMediaTab" role="tab" id="browseMedia">
					<span class="embedTabs-tabLabel">' . 'Other\'s media' . '</span>
					<span class="badge badge--highlighted js-tabCounter">0</span>
				</a>
				<a class="tabs-tab embedTabs-tab js-browseAlbumsTab" role="tab" id="browseAlbums">
					<span class="embedTabs-tabLabel">' . 'Other\'s albums' . '</span>
					<span class="badge badge--highlighted js-tabCounter">0</span>
				</a>
			</span>
		</h2>

		<ul class="tabPanes js-embedMediaPanes">
			<li data-href="' . $__templater->func('link', array('media/dialog/yours', ), true) . '" role="tabpanel" aria-labelledby="yourMedia" data-tab=".js-yourMediaTab">
				<div class="blockMessage">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
			</li>

			';
	if ($__vars['xf']['visitor']['xfmg_album_count']) {
		$__finalCompiled .= '
				<li data-href="' . $__templater->func('link', array('media/albums/dialog-yours', ), true) . '" role="tabpanel" aria-labelledby="yourAlbums" data-tab=".js-yourAlbumsTab">
					<div class="blockMessage">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
				</li>
			';
	}
	$__finalCompiled .= '

			<li data-href="' . $__templater->func('link', array('media/dialog-browse', ), true) . '" role="tabpanel" aria-labelledby="browseMedia" data-tab=".js-browseMediaTab">
				<div class="blockMessage">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
			</li>

			<li data-href="' . $__templater->func('link', array('media/albums/dialog-browse', ), true) . '" role="tabpanel" aria-labelledby="browseAlbums" data-tab=".js-browseAlbumsTab">
				<div class="blockMessage">' . 'Loading' . $__vars['xf']['language']['ellipsis'] . '</div>
			</li>
		</ul>
		' . $__templater->formHiddenVal('', '{}', array(
		'class' => 'js-embedValue',
	)) . '
		' . $__templater->formSubmitRow(array(
		'submit' => 'Continue',
		'sticky' => 'true',
		'id' => 'xfmg_editor_dialog_gallery',
	), array(
		'rowtype' => 'simple',
	)) . '
	</div>
</form>

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);