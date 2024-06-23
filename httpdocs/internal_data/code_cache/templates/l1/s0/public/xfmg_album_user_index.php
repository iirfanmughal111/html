<?php
// FROM HASH: e440bbf361285ac00ed79846cb53fb7f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Albums created by ' . $__templater->escape($__vars['user']['username']) . '');
	$__templater->pageParams['pageNumber'] = $__vars['page'];
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['selected'] = (($__vars['xf']['visitor']['user_id'] == $__vars['user']['user_id']) ? 'yourAlbums' : '');
	$__templater->wrapTemplate('xfmg_gallery_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	if ($__templater->method($__vars['xf']['visitor'], 'canAddMedia', array())) {
		$__finalCompiled .= '
	';
		$__templater->pageParams['pageAction'] = $__templater->preEscaped('
		' . $__templater->button('
			' . 'Add Album....' . '
		', array(
			'href' => $__templater->func('link', array('media/albums/create', ), false),
			'class' => 'button--cta',
			'icon' => 'add',
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
		'link' => 'media/albums/users',
		'data' => $__vars['user'],
		'params' => $__vars['filters'],
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

		' . $__compilerTemp2 . '
	'), false) . '</div>
	<div class="block-container">
		' . $__templater->callMacro('xfmg_album_list_macros', 'list_filter_bar', array(
		'filters' => $__vars['filters'],
		'baseLinkPath' => 'media/albums/users',
		'linkData' => $__vars['user'],
	), $__vars) . '

		<div class="block-body">
			';
	if (!$__templater->test($__vars['albums'], 'empty', array())) {
		$__finalCompiled .= '
				' . $__templater->callMacro('xfmg_album_list_macros', 'album_list', array(
			'albums' => $__vars['albums'],
			'forceInlineMod' => $__vars['canInlineMod'],
		), $__vars) . '
			';
	} else if ($__vars['filters']) {
		$__finalCompiled .= '
				<div class="block-row">' . '' . $__templater->escape($__vars['user']['username']) . ' has not added any albums which match your filters.' . '</div>
			';
	} else {
		$__finalCompiled .= '
				<div class="block-row">' . '' . $__templater->escape($__vars['user']['username']) . ' has not added any albums yet.' . '</div>
			';
	}
	$__finalCompiled .= '
		</div>
	</div>

	<div class="block-outer block-outer--after">
		' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['totalItems'],
		'link' => 'media/albums/users',
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