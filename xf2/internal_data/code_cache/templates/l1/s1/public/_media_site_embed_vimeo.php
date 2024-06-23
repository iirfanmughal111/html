<?php
// FROM HASH: be2473fa539c4112b6f12af2be826563
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="bbMediaWrapper">
	<div class="bbMediaWrapper-inner">
		<iframe src="https://player.vimeo.com/video/' . $__templater->escape($__vars['id']) . ($__vars['key'] ? ('?h=' . $__templater->escape($__vars['key'])) : '') . ($__vars['start'] ? ('#t=' . $__templater->escape($__vars['start'])) : '') . '"
				width="560" height="315"
				frameborder="0" allowfullscreen="true"></iframe>
	</div>
</div>';
	return $__finalCompiled;
}
);