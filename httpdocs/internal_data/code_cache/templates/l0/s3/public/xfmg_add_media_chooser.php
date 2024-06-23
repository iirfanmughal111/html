<?php
// FROM HASH: 9e5641da2c4ec20dda84995b324db3e8
return array(
'macros' => array('category_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'children' => '!',
		'extras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['id'] => $__vars['child']) {
			$__finalCompiled .= '
		' . $__templater->callMacro(null, 'category_list_entry', array(
				'category' => $__vars['child']['record'],
				'extras' => $__vars['extras'][$__vars['id']],
				'children' => $__vars['child']['children'],
				'childExtras' => $__vars['extras'],
				'depth' => $__vars['depth'],
			), $__vars) . '
	';
		}
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'category_list_entry' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'children' => '!',
		'childExtras' => '!',
		'extras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	if ($__vars['category']['category_type'] == 'album') {
		$__finalCompiled .= '
		' . $__templater->callMacro(null, 'category_list_entry_album_category', array(
			'category' => $__vars['category'],
			'extras' => $__vars['extras'],
			'children' => $__vars['children'],
			'childExtras' => $__vars['childExtras'],
			'depth' => $__vars['depth'],
		), $__vars) . '
	';
	} else if ($__templater->method($__vars['category'], 'canAddMedia', array())) {
		$__finalCompiled .= '
		' . $__templater->callMacro(null, 'category_list_entry_category', array(
			'category' => $__vars['category'],
			'extras' => $__vars['extras'],
			'children' => $__vars['children'],
			'childExtras' => $__vars['childExtras'],
			'depth' => $__vars['depth'],
		), $__vars) . '
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->callMacro(null, 'category_list_entry_no_adding', array(
			'category' => $__vars['category'],
			'extras' => $__vars['extras'],
			'children' => $__vars['children'],
			'childExtras' => $__vars['childExtras'],
			'depth' => $__vars['depth'],
		), $__vars) . '
	';
	}
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
),
'category_list_entry_category' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="block-row block-row--clickable block-row--separated fauxBlockLink">
		<div class="contentRow contentRow--alignMiddle' . (($__vars['depth'] > 0) ? (' u-depth' . $__templater->escape($__vars['depth'])) : '') . '">
			<div class="contentRow-main">
				<h2 class="contentRow-title">
					<a href="' . $__templater->func('link', array('media/categories/add', $__vars['category'], ), true) . '" class="fauxBlockLink-blockLink">
						' . $__templater->escape($__vars['category']['title']) . '
					</a>
				</h2>
				';
	if ($__vars['category']['description']) {
		$__finalCompiled .= '
					<div class="contentRow-minor contentRow-minor--singleLine">
						' . $__templater->filter($__vars['category']['description'], array(array('raw', array()),), true) . '
					</div>
				';
	}
	$__finalCompiled .= '
			</div>
			<div class="contentRow-suffix">
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Albums' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['album_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Media items' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['media_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Comments' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['comment_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
			</div>
		</div>
	</div>
	' . $__templater->callMacro(null, 'category_list', array(
		'children' => $__vars['children'],
		'extras' => $__vars['childExtras'],
		'depth' => ($__vars['depth'] + 1),
	), $__vars) . '
';
	return $__finalCompiled;
}
),
'category_list_entry_album_category' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="block-row block-row--clickable block-row--separated fauxBlockLink">
		<div class="contentRow contentRow--alignMiddle' . (($__vars['depth'] > 0) ? (' u-depth' . $__templater->escape($__vars['depth'])) : '') . '">
			<div class="contentRow-main">
				<h2 class="contentRow-title">
					<a href="' . $__templater->func('link', array('media/categories/add', $__vars['category'], ), true) . '" class="fauxBlockLink-blockLink">
						' . $__templater->escape($__vars['category']['title']) . '
					</a>
				</h2>
				';
	if ($__vars['category']['description']) {
		$__finalCompiled .= '
					<div class="contentRow-minor contentRow-minor--singleLine">
						' . $__templater->filter($__vars['category']['description'], array(array('raw', array()),), true) . '
					</div>
				';
	}
	$__finalCompiled .= '
			</div>
			<div class="contentRow-suffix">
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Albums' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['album_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Media items' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['media_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Comments' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['comment_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
			</div>
		</div>
	</div>
	' . $__templater->callMacro(null, 'category_list', array(
		'children' => $__vars['children'],
		'extras' => $__vars['childExtras'],
		'depth' => ($__vars['depth'] + 1),
	), $__vars) . '
';
	return $__finalCompiled;
}
),
'category_list_entry_no_adding' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
		'depth' => '0',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<div class="block-row block-row--separated">
		<div class="contentRow contentRow--alignMiddle' . (($__vars['depth'] > 0) ? (' u-depth' . $__templater->escape($__vars['depth'])) : '') . ' is-disabled">
			<div class="contentRow-main">
				<h2 class="contentRow-title">
					' . $__templater->escape($__vars['category']['title']) . '
				</h2>
				<div class="contentRow-minor contentRow-minor--singleLine">
					';
	if ($__vars['category']['category_type']) {
		$__finalCompiled .= '
						' . 'You can not add media to container categories.' . '
					';
	} else {
		$__finalCompiled .= '
						' . 'You do not have permission to add media to this category.' . '
					';
	}
	$__finalCompiled .= '
				</div>
			</div>
			<div class="contentRow-suffix">
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Albums' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['album_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Media items' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['media_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
				<dl class="pairs pairs--rows pairs--rows--centered">
					<dt>' . 'Comments' . '</dt>
					<dd>' . $__templater->filter($__vars['extras']['comment_count'], array(array('number_short', array()),), true) . '</dd>
				</dl>
			</div>
		</div>
	</div>

	' . $__templater->callMacro(null, 'category_list', array(
		'children' => $__vars['children'],
		'extras' => $__vars['childExtras'],
		'depth' => ($__vars['depth'] + 1),
	), $__vars) . '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Add media to' . $__vars['xf']['language']['ellipsis']);
	$__finalCompiled .= '

';
	$__templater->wrapTemplate('xfmg_gallery_wrapper', $__vars);
	$__finalCompiled .= '

<div class="blocks">
	';
	if (!$__templater->test($__vars['categoryAddTree'], 'empty', array())) {
		$__finalCompiled .= '
		<div class="block block--treeEntryChooser">
			<div class="block-container">
				<div class="block-body">
					' . $__templater->callMacro(null, 'category_list', array(
			'children' => $__vars['categoryAddTree'],
			'extras' => $__vars['categoryExtras'],
			'depth' => '0',
		), $__vars) . '
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '

	';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
						';
	if ($__vars['canCreateAlbums']) {
		$__compilerTemp1 .= '
							<div class="block-row block-row--clickable block-row--separated fauxBlockLink">
								<div class="contentRow contentRow--alignMiddle">
									<div class="contentRow-main">
										<h2 class="contentRow-title">
											<a href="' . $__templater->func('link', array('media/albums/create', ), true) . '" class="fauxBlockLink-blockLink">
												' . 'Create personal album' . $__vars['xf']['language']['ellipsis'] . '
											</a>
										</h2>
										<div class="contentRow-minor">
											' . 'A personal album can use advanced privacy settings and will not appear in any category.' . '
										</div>
									</div>
								</div>
							</div>
						';
	}
	$__compilerTemp1 .= '
						';
	if ($__vars['canAddToAlbums']) {
		$__compilerTemp1 .= '
							<div class="block-row block-row--clickable block-row--separated fauxBlockLink">
								<div class="contentRow contentRow--alignMiddle">
									<div class="contentRow-main">
										<h2 class="contentRow-title">
											<a href="' . $__templater->func('link', array('media/albums/add', ), true) . '" class="fauxBlockLink-blockLink">
												' . 'Add media to existing album' . $__vars['xf']['language']['ellipsis'] . '
											</a>
										</h2>
										<div class="contentRow-minor">
											' . 'You will be shown a list of all albums that you can add media to.' . '
										</div>
									</div>
								</div>
							</div>
						';
	}
	$__compilerTemp1 .= '
					';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
		';
		if (!$__templater->test($__vars['categoryAddTree'], 'empty', array())) {
			$__finalCompiled .= '
			<div class="blocks-textJoiner"><span></span><em>' . 'or' . '</em><span></span></div>
		';
		}
		$__finalCompiled .= '
		<div class="block block--treeEntryChooser">
			<div class="block-container">
				<div class="block-body">
					' . $__compilerTemp1 . '
				</div>
			</div>
		</div>
	';
	}
	$__finalCompiled .= '
</div>

' . '

' . '

' . '

' . '

';
	return $__finalCompiled;
}
);