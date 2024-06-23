<?php
// FROM HASH: 79fd580bdda50824ca9ce8e522287a90
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-figure contentRow-figure--fixedBookmarkIcon">
	';
	if ($__vars['xf']['options']['xfrmAllowIcons']) {
		$__finalCompiled .= '
		<div class="contentRow-figureContainer">
			' . $__templater->func('resource_icon', array($__vars['content'], 's', ), true) . '
			' . $__templater->func('avatar', array($__vars['content']['User'], 's', false, array(
			'href' => '',
			'class' => 'avatar--separated contentRow-figureSeparated',
			'defaultname' => $__vars['content']['username'],
		))) . '
		</div>
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->func('avatar', array($__vars['content']['User'], 's', false, array(
			'defaultname' => $__vars['content']['username'],
		))) . '
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);