<?php
// FROM HASH: 9bf19d097dd47d699afae65517426a55
return array(
'macros' => array('xfmg_page_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'album' => null,
		'category' => null,
		'mediaItem' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__vars['constraints'] = array('Media' => array('search_type' => 'xfmg_media', ), 'Comments' => array('search_type' => 'xfmg_comment', ), );
	$__finalCompiled .= '

	';
	if ($__vars['category']) {
		$__finalCompiled .= '
		';
		$__vars['constraints'] = ($__vars['constraints'] + array('Media (this category)' => array('search_type' => 'xfmg_media', 'c' => array('categories' => array($__vars['category']['category_id'], ), 'child_categories' => 1, ), ), ));
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['album']) {
		$__finalCompiled .= '
		';
		$__vars['constraints'] = ($__vars['constraints'] + array('Media (this album)' => array('search_type' => 'xfmg_media', 'c' => array('albums' => array($__vars['album']['album_id'], ), ), ), 'Comments (this album)' => array('search_type' => 'xfmg_comment', 'c' => array('types' => array('xfmg_album', ), 'ids' => array($__vars['album']['album_id'], ), ), ), ));
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	if ($__vars['mediaItem']) {
		$__finalCompiled .= '
		';
		$__vars['constraints'] = ($__vars['constraints'] + array('Comments (this media item)' => array('search_type' => 'xfmg_comment', 'c' => array('types' => array('xfmg_media', ), 'ids' => array($__vars['mediaItem']['media_id'], ), ), ), ));
		$__finalCompiled .= '
	';
	}
	$__finalCompiled .= '

	';
	$__templater->setPageParam('searchConstraints', $__vars['constraints']);
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);