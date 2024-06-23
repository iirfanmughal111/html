<?php
// FROM HASH: f3a44f5f70f10937d409149c01f5abee
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['category']['title']));
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped($__templater->filter($__vars['category']['description'], array(array('raw', array()),), true));
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['selected'] = $__vars['category']['category_id'];
	$__templater->wrapTemplate('xfmg_gallery_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'canAddMedia', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
		' . $__templater->button('
			' . 'Add media' . $__vars['xf']['language']['ellipsis'] . '
		', array(
			'href' => $__templater->func('link', array('media/add', ), false),
			'class' => 'button--cta',
			'icon' => 'add',
			'data-xf-click' => 'overlay',
		), '', array(
		)) . '
	');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__vars['descendentTree'] AND ($__templater->method($__vars['descendentTree'], 'count', array()) AND ($__templater->func('property', array('xfmgCategoryList', ), false) != 'never'))) {
		$__finalCompiled .= '
	';
		if (!$__templater->test($__vars['mediaItems'], 'empty', array()) OR !$__templater->test($__vars['albums'], 'empty', array())) {
			$__finalCompiled .= '
		<div class="blockMessage blockMessage--small blockMessage--highlight">' . 'The items displayed on this page are being displayed from child categories.' . '</div>
	';
		}
		$__finalCompiled .= '

	<div class="block">
		<div class="block-container">
			<div class="block-body">
				' . $__templater->callMacro('xfmg_category_list_macros', 'category_list', array(
			'children' => $__vars['descendentTree'],
			'extras' => $__vars['descendentExtras'],
		), $__vars) . '
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['canInlineModAlbums'] OR $__vars['canInlineModMediaItems']) {
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
	if ($__vars['viewType'] == 'media') {
		$__finalCompiled .= '
	<div class="block" data-xf-init="' . ($__vars['canInlineModMediaItems'] ? 'inline-mod' : '') . '" data-type="xfmg_media" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
		<div class="block-outer">';
		$__compilerTemp2 = '';
		$__compilerTemp3 = '';
		$__compilerTemp3 .= '
							';
		if ($__vars['canInlineModMediaItems']) {
			$__compilerTemp3 .= '
								' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
							';
		}
		$__compilerTemp3 .= '
							';
		if ($__vars['xf']['visitor']['user_id']) {
			$__compilerTemp3 .= '
								' . $__templater->button('
									' . 'Mark viewed' . '
								', array(
				'href' => $__templater->func('link', array('media/categories/mark-viewed', $__vars['category'], array('date' => $__vars['xf']['time'], ), ), false),
				'class' => 'button--link',
				'overlay' => 'true',
			), '', array(
			)) . '
							';
		}
		$__compilerTemp3 .= '
						';
		if (strlen(trim($__compilerTemp3)) > 0) {
			$__compilerTemp2 .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp3 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= $__templater->func('trim', array('

			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['totalItems'],
			'link' => 'media/categories',
			'data' => $__vars['category'],
			'params' => $__vars['filters'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			' . $__compilerTemp2 . '

		'), false) . '</div>

		<div class="block-container">
			' . $__templater->callMacro('xfmg_media_list_macros', 'list_filter_bar', array(
			'filters' => $__vars['filters'],
			'baseLinkPath' => 'media/categories',
			'linkData' => $__vars['category'],
			'ownerFilter' => $__vars['ownerFilter'],
		), $__vars) . '

			<div class="block-body">
				';
		if (!$__templater->test($__vars['mediaItems'], 'empty', array())) {
			$__finalCompiled .= '
					' . $__templater->callMacro('xfmg_media_list_macros', 'media_list', array(
				'mediaItems' => $__vars['mediaItems'],
				'prevPage' => $__vars['prevPage'],
				'nextPage' => $__vars['nextPage'],
				'setupLightbox' => $__vars['xf']['options']['xfmgLightboxNavigation'],
			), $__vars) . '
					';
			if ($__vars['showDateLimitDisabler']) {
				$__finalCompiled .= '
						' . $__templater->callMacro(null, 'xfmg_category_view::date_limit_disabler', array(
					'category' => $__vars['category'],
					'page' => $__vars['page'],
					'filters' => $__vars['filters'],
				), $__vars) . '
					';
			}
			$__finalCompiled .= '
				';
		} else if ($__vars['filters']) {
			$__finalCompiled .= '
					<div class="block-row">' . 'There is no media or albums matching your filters.' . '</div>
					';
			if ($__vars['showDateLimitDisabler']) {
				$__finalCompiled .= '
						' . $__templater->callMacro(null, 'xfmg_category_view::date_limit_disabler', array(
					'category' => $__vars['category'],
					'page' => $__vars['page'],
					'filters' => $__vars['filters'],
				), $__vars) . '
					';
			}
			$__finalCompiled .= '
				';
		} else {
			$__finalCompiled .= '
					';
			if ($__vars['showDateLimitDisabler']) {
				$__finalCompiled .= '
						<div class="block-row">' . 'There are no media items to display.' . '</div>
						' . $__templater->callMacro(null, 'xfmg_category_view::date_limit_disabler', array(
					'category' => $__vars['category'],
					'page' => $__vars['page'],
					'filters' => $__vars['filters'],
				), $__vars) . '
					';
			} else {
				$__finalCompiled .= '
						<div class="block-row">' . 'No media or albums have been added to this category yet.' . '</div>
					';
			}
			$__finalCompiled .= '
				';
		}
		$__finalCompiled .= '
			</div>
		</div>

		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['totalItems'],
			'link' => 'media/categories',
			'data' => $__vars['category'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>
	</div>
';
	} else if ($__vars['viewType'] == 'album') {
		$__finalCompiled .= '
	<div class="block" data-xf-init="' . ($__vars['canInlineModAlbums'] ? 'inline-mod' : '') . '" data-type="xfmg_album" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
		<div class="block-outer">';
		$__compilerTemp4 = '';
		$__compilerTemp5 = '';
		$__compilerTemp5 .= '
							';
		if ($__vars['canInlineModAlbums']) {
			$__compilerTemp5 .= '
								' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
							';
		}
		$__compilerTemp5 .= '
						';
		if (strlen(trim($__compilerTemp5)) > 0) {
			$__compilerTemp4 .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
						' . $__compilerTemp5 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= $__templater->func('trim', array('

			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['totalItems'],
			'link' => 'media/categories',
			'data' => $__vars['category'],
			'params' => $__vars['filters'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '

			' . $__compilerTemp4 . '

		'), false) . '</div>

		<div class="block-container">
			' . $__templater->callMacro('xfmg_album_list_macros', 'list_filter_bar', array(
			'filters' => $__vars['filters'],
			'baseLinkPath' => 'media/categories',
			'linkData' => $__vars['category'],
			'ownerFilter' => $__vars['ownerFilter'],
		), $__vars) . '

			<div class="block-body">
				';
		if (!$__templater->test($__vars['albums'], 'empty', array())) {
			$__finalCompiled .= '
					' . $__templater->callMacro('xfmg_album_list_macros', 'album_list', array(
				'albums' => $__vars['albums'],
			), $__vars) . '
				';
		} else if ($__vars['filters']) {
			$__finalCompiled .= '
					<div class="block-row">' . 'There is no media or albums matching your filters.' . '</div>
				';
		} else {
			$__finalCompiled .= '
					<div class="block-row">' . 'No media or albums have been added to this category yet.' . '</div>
				';
		}
		$__finalCompiled .= '
			</div>
		</div>

		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['totalItems'],
			'link' => 'media/categories',
			'data' => $__vars['category'],
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);