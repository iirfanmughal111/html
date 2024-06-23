<?php
// FROM HASH: 3a449b2a89d0ebcc8b005259c6b4051b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('xfmg_media');
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('media');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
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

<div class="p-body-header">
    <div class="p-title"><h2 class="p-title-value">' . 'xfmg_media' . '</h2></div>
</div>

<div class="block" data-xf-init="' . ($__vars['canInlineMod'] ? 'inline-mod' : '') . '"
     data-type="xfmg_album" data-href="' . $__templater->func('link', array('inline-mod', ), true) . '">
    <div class="block-outer">
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'groups/media',
		'data' => $__vars['group'],
		'params' => $__templater->filter($__vars['filters'], array(array('replace', array('group_id', null, )),), false),
		'wrapperclass' => 'block-outer-main',
		'perPage' => $__vars['perPage'],
	))) . '

        <div class="block-outer-opposite">
            ';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
                    ';
	if ($__vars['canAddAlbum'] OR $__vars['canLinkAlbums']) {
		$__compilerTemp2 .= '
                        ' . $__templater->button('Add album', array(
			'href' => $__templater->func('link', array('groups/media/add', $__vars['group'], ), false),
			'class' => 'button--cta',
			'icon' => 'add',
			'overlay' => 'true',
		), '', array(
		)) . '
                    ';
	}
	$__compilerTemp2 .= '

                    ';
	if ($__vars['canInlineMod']) {
		$__compilerTemp2 .= '
                        ' . $__templater->callMacro('inline_mod_macros', 'button', array(), $__vars) . '
                    ';
	}
	$__compilerTemp2 .= '
                ';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
            <div class="buttonGroup">
                ' . $__compilerTemp2 . '
            </div>
            ';
	}
	$__finalCompiled .= '

            ';
	if ($__vars['canAddAlbum']) {
		$__finalCompiled .= '
                ' . $__templater->button('Add album', array(
			'href' => $__templater->func('link', array('media/albums/create', null, array('group_id' => $__vars['group']['group_id'], ), ), false),
			'class' => 'button--cta',
			'icon' => 'add',
		), '', array(
		)) . '
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>
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
	} else if (!$__templater->test($__vars['filters'], 'empty', array())) {
		$__finalCompiled .= '
                <div class="block-row">' . 'xfmg_there_no_albums_matching_your_filters' . '</div>
            ';
	} else {
		$__finalCompiled .= '
                <div class="block-row">' . 'xfmg_no_albums_have_been_added_yet' . '</div>
            ';
	}
	$__finalCompiled .= '
        </div>
    </div>

    <div class="block-outer block-outer--after">
        ' . $__templater->func('page_nav', array(array(
		'page' => $__vars['page'],
		'total' => $__vars['total'],
		'link' => 'groups/media',
		'data' => $__vars['group'],
		'params' => $__templater->filter($__vars['filters'], array(array('replace', array('group_id', null, )),), false),
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