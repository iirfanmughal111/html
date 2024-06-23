<?php
// FROM HASH: d99558cd0bc6481e1f35c6fda2cffb35
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Browse albums');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

' . $__templater->callMacro('xfmg_page_macros', 'xfmg_page_options', array(), $__vars) . '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:media/albums', null, array('page' => $__vars['page'], ), ), false),
	), $__vars) . '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['selected'] = 'browseAlbums';
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

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="xfmg_album" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	<div class="block-outer">';
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
						';
	if ($__vars['canInlineMod']) {
		$__compilerTemp3 .= '
							' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
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
		'link' => 'media/albums',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp2 . '

	'), false) . '</div>

	<div class="block-container">
		' . $__templater->callMacro('xfmg_album_list_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'media/albums',
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
				<div class="block-row">' . 'There are no albums matching your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . 'No albums have been added yet.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['totalItems'],
		'link' => 'media/albums',
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__templater->func('show_ignored', array(array(
		'wrapperclass' => 'block-outer-opposite',
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);