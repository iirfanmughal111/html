<?php
// FROM HASH: 689591fdce0138839b46c311ed320f51
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-figure contentRow-figure--fixedBookmarkIcon">
	';
	if ($__vars['xf']['options']['xfrmAllowIcons']) {
		$__finalCompiled .= '
		<div class="contentRow-figureContainer">
			' . $__templater->func('resource_icon', array($__vars['content']['Resource'], 's', ), true) . '
			' . $__templater->func('avatar', array($__vars['content']['Resource']['User'], 's', false, array(
			'href' => '',
			'class' => 'avatar--separated contentRow-figureSeparated',
			'defaultname' => $__vars['content']['Resource']['username'],
		))) . '
		</div>
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->func('avatar', array($__vars['content']['Resource']['User'], 's', false, array(
			'defaultname' => $__vars['content']['Resource']['username'],
		))) . '
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);