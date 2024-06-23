<?php
// FROM HASH: 34b1121c9ff9b418295e639bfbca9d83
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="js-moreItems">
	';
	if (!$__templater->test($__vars['mediaItems'], 'empty', array())) {
		$__finalCompiled .= '
		' . $__templater->callMacro(null, 'xfmg_media_list_macros::media_list', array(
			'mediaItems' => $__vars['mediaItems'],
			'prevPage' => $__vars['prevPage'],
			'nextPage' => $__vars['nextPage'],
			'setupLightbox' => false,
		), $__vars) . '
	';
	}
	$__finalCompiled .= '
</div>';
	return $__finalCompiled;
}
);