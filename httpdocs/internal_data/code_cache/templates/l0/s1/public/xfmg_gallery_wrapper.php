<?php
// FROM HASH: ab8512cb15afa14bfcd6b6c2728a90ac
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->filter($__vars['innerContent'], array(array('raw', array()),), true) . '

';
	$__templater->setPageParam('sideNavTitle', 'Navigation');
	$__finalCompiled .= '

';
	$__compilerTemp1 = '';
	if ($__vars['categoryTree'] AND $__templater->method($__vars['categoryTree'], 'count', array())) {
		$__compilerTemp1 .= '
					' . $__templater->callMacro('xfmg_category_list_macros', 'simple_category_list', array(
			'selected' => $__vars['selected'],
			'pathToSelected' => $__templater->method($__vars['categoryTree'], 'getPathTo', array($__vars['selected'], )),
			'children' => $__vars['categoryTree'],
			'extras' => $__vars['categoryExtras'],
			'isActive' => true,
		), $__vars) . '

					<hr class="block-separator" />
				';
	}
	$__compilerTemp2 = '';
	$__compilerTemp3 = '';
	$__compilerTemp3 .= '
						';
	if ($__templater->method($__vars['xf']['visitor'], 'canViewAlbums', array()) OR $__vars['hasAlbumCategories']) {
		$__compilerTemp3 .= '
							<a href="' . $__templater->func('link', array('media/albums', ), true) . '" class="blockLink' . (($__vars['selected'] == 'browseAlbums') ? ' is-selected' : '') . '">' . 'Browse albums' . '</a>
						';
	}
	$__compilerTemp3 .= '

						';
	if ($__vars['xf']['visitor']['user_id']) {
		$__compilerTemp3 .= '
							

							';
		if ($__templater->method($__vars['xf']['visitor'], 'canViewAlbums', array()) OR $__vars['hasAlbumCategories']) {
			$__compilerTemp3 .= '
								<a href="' . $__templater->func('link', array('media/albums/users', $__vars['xf']['visitor'], ), true) . '" class="blockLink' . (($__vars['selected'] == 'yourAlbums') ? ' is-selected' : '') . '">' . 'Your albums' . '</a>
							';
		}
		$__compilerTemp3 .= '
						';
	}
	$__compilerTemp3 .= '
					';
	if (strlen(trim($__compilerTemp3)) > 0) {
		$__compilerTemp2 .= '
					' . $__compilerTemp3 . '
				';
	}
	$__templater->modifySideNavHtml('xfmgSideNav', '
	<div class="block">
		<div class="block-container">
			<h3 class="block-header">' . 'Navigation' . '</h3>
			<div class="block-body">
				' . $__compilerTemp1 . '
				' . $__compilerTemp2 . '
			</div>
		</div>
	</div>
', 'replace');
	$__finalCompiled .= '

';
	$__templater->modifySideNavHtml('_xfWidgetPositionSideNav85ba2dee6cfdf13b01531199fe5f282e', $__templater->widgetPosition('xfmg_gallery_wrapper_sidenav', array()), 'replace');
	return $__finalCompiled;
}
);