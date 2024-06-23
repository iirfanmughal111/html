<?php
// FROM HASH: 17ce6d8604a34ee25cc88da18f49de48
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="bbMediaWrapper">
	<div class="bbMediaWrapper-inner">
		<iframe src="https://iframe.mediadelivery.net/embed/' . $__templater->escape($__vars['libraryId']) . '/' . $__templater->escape($__vars['videoId']) . '?autoplay=false" loading="lazy" style="border: none; position: absolute; top: 0; height: 315; width: 560;" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" allowfullscreen="true"></iframe>
	</div>
</div>';
	return $__finalCompiled;
}
);