<?php
// FROM HASH: b21a350e6e0385d9057ea74d7643ea91
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-figure contentRow-figure--fixedBookmarkIcon">
	';
	if ($__vars['content']['CoverImage']) {
		$__finalCompiled .= '
		<div class="contentRow-figureContainer">
			<a href="' . $__templater->func('link', array('showcase', $__vars['content'], ), true) . '">
				' . $__templater->func('sc_item_thumbnail', array($__vars['content'], ), true) . '
			</a>			
		</div>
	';
	} else if ($__vars['content']['Category']['content_image_url']) {
		$__finalCompiled .= '
		<div class="contentRow-figureContainer">
			<a href="' . $__templater->func('link', array('showcase', $__vars['content'], ), true) . '">
				' . $__templater->func('sc_category_icon', array($__vars['content'], ), true) . '
			</a>			
		</div>
	';
	} else {
		$__finalCompiled .= '
		' . $__templater->func('avatar', array($__vars['content']['User'], 's', false, array(
			'defaultname' => $__vars['content']['User']['username'],
		))) . '
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);