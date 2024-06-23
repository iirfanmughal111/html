<?php
// FROM HASH: ff053c5a4df381880a1a1aec60dff2e3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Media added by ' . $__templater->escape($__vars['user']['username']) . '');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['selected'] = (($__vars['xf']['visitor']['user_id'] == $__vars['user']['user_id']) ? 'yourMedia' : '');
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

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '" data-type="xfmg_media" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
	<div class="block-outer">';
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
						';
	if ($__vars['canInlineMod']) {
		$__compilerTemp3 .= '
							' . $__templater->callMacro('inline_mod_macros', 'button', array(
			'variant' => 'inlineModButton--withLabel',
			'label' => 'Selected',
			'tooltip' => false,
		), $__vars) . '
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
		'link' => 'media/users',
		'data' => $__vars['user'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp2 . '

	'), false) . '</div>

	<div class="block-container">
		' . $__templater->callMacro('xfmg_media_list_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'media/users',
		'linkData' => $__vars['user'],
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['mediaItems'], 'empty', array())) {
		$__finalCompiled .= '
				' . $__templater->callMacro('xfmg_media_list_macros', 'media_list', array(
			'mediaItems' => $__vars['mediaItems'],
			'forceInlineMod' => $__vars['canInlineMod'],
			'prevPage' => $__vars['prevPage'],
			'nextPage' => $__vars['nextPage'],
			'setupLightbox' => $__vars['xf']['options']['xfmgLightboxNavigation'],
		), $__vars) . '
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="blockMessage">' . '' . $__templater->escape($__vars['user']['username']) . ' has not added any media which matches your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="blockMessage">' . '' . $__templater->escape($__vars['user']['username']) . ' has not added any media yet.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['totalItems'],
		'link' => 'media/users',
		'data' => $__vars['user'],
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