<?php
// FROM HASH: 906c2cdd7f89166be98ff2e30101aece
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add media to' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['hasAlbumCategories'] = (($__templater->method($__vars['categoryTree'], 'count', array()) > 0));
	$__templater->wrapTemplate('xfmg_gallery_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

';
	if (!$__templater->test($__vars['albums'], 'empty', array())) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-outer">' . $__templater->func('trim', array('
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['totalItems'],
			'link' => 'media/albums/add',
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
		'), false) . '</div>

		<div class="block-container">
			<div class="block-body">
				' . $__templater->callMacro('xfmg_album_list_macros', 'album_list', array(
			'albums' => $__vars['albums'],
			'isChooser' => true,
			'allowInlineMod' => false,
		), $__vars) . '
			</div>
		</div>

		<div class="block-outer block-outer--after">
			' . $__templater->func('page_nav', array(array(
			'page' => $__vars['page'],
			'total' => $__vars['totalItems'],
			'link' => 'media/albums/add',
			'wrapperclass' => 'block-outer-main',
			'perPage' => $__vars['perPage'],
		))) . '
			' . $__templater->func('show_ignored', array(array(
			'wrapperclass' => 'block-outer-opposite',
		))) . '
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'No albums have been added yet which you can add media to.' . '</div>
';
	}
	return $__finalCompiled;
}
);