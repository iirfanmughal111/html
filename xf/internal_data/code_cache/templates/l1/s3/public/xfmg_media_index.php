<?php
// FROM HASH: 535d71163034c1954a208de2398d3795
return array(
'macros' => array('date_limit_disabler' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'page' => '!',
		'filters' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	<div class="blockMessage blockMessage--highlight blockMessage--small blockMessage--center">
		<a href="' . $__templater->func('link', array('media', null, $__vars['filters'] + array('page' => $__vars['page'], 'no_date_limit' => 1, ), ), true) . '">
			' . 'Show older items' . '
		</a>
	</div>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Media');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_media_list_macros', 'media_create_message', array(
		'transcoding' => $__vars['transcoding'],
		'pendingApproval' => $__vars['pendingApproval'],
	), $__vars) . '

' . $__templater->callMacro('xfmg_page_macros', 'xfmg_page_options', array(), $__vars) . '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:media', null, array('page' => $__vars['page'], ), ), false),
	), $__vars) . '

';
	$__templater->wrapTemplate('xfmg_gallery_wrapper', $__vars);
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
	if ($__vars['categoryTree'] AND (($__templater->method($__vars['categoryTree'], 'count', array()) > 1) AND ($__templater->func('property', array('xfmgCategoryList', ), false) == 'always'))) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			<div class="block-body">
				' . $__templater->callMacro('xfmg_category_list_macros', 'category_list', array(
			'children' => $__vars['categoryTree'],
			'extras' => $__vars['categoryExtras'],
		), $__vars) . '
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	if ($__vars['canInlineMod']) {
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

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="xfmg_media" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	<div class="block-outer">';
	$__compilerTemp1 = '';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
						';
	if ($__vars['canInlineMod']) {
		$__compilerTemp2 .= '
							' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
						';
	}
	$__compilerTemp2 .= '
						';
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp2 .= '
							' . $__templater->button('
								' . 'Mark media viewed' . '
							', array(
			'href' => $__templater->func('link', array('media/mark-viewed', null, array('date' => $__vars['xf']['time'], ), ), false),
			'class' => 'button--link',
			'overlay' => 'true',
		), '', array(
		)) . '
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
		'total' => $__vars['totalItems'],
		'link' => 'media',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp1 . '

	'), false) . '</div>

	<div class="block-container">
		' . $__templater->callMacro('xfmg_media_list_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'media',
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
					' . $__templater->callMacro(null, 'date_limit_disabler', array(
				'page' => $__vars['page'],
				'filters' => $__vars['filters'],
			), $__vars) . '
				';
		}
		$__finalCompiled .= '
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . 'There is no media matching your filters.' . '</div>
				';
		if ($__vars['showDateLimitDisabler']) {
			$__finalCompiled .= '
					' . $__templater->callMacro(null, 'date_limit_disabler', array(
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
					' . $__templater->callMacro(null, 'date_limit_disabler', array(
				'page' => $__vars['page'],
				'filters' => $__vars['filters'],
			), $__vars) . '
				';
		} else {
			$__finalCompiled .= '
					<div class="block-row">' . 'No media has been added yet.' . '</div>
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
		'link' => 'media',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>

';
	return $__finalCompiled;
}
);