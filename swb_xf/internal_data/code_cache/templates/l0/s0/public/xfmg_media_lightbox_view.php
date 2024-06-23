<?php
// FROM HASH: 401f5228309cdfd384649829d9920d0f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->setPageParam('template', '');
	$__finalCompiled .= '

<div>';
	if ($__vars['mediaItem']['media_type'] == 'video') {
		$__finalCompiled .= '
	<video preload="metadata" controls poster="' . $__templater->escape($__vars['mediaItem']['poster_url']) . '" class="fancybox-video">
		<source src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getVideoUrl', array())) . '" type="video/mp4" />
		<div class="bbMediaWrapper-fallback">' . 'Your browser is not able to display this video.' . '</div>
	</video>
';
	} else if ($__vars['mediaItem']['media_type'] == 'audio') {
		$__finalCompiled .= '
	<video preload="metadata" controls poster="' . $__templater->escape($__vars['mediaItem']['poster_url']) . '" class="fancybox-video">
		<source src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getAudioUrl', array())) . '" type="audio/mpeg" />
		<div class="bbMediaWrapper-fallback">' . 'Your browser is not able to play this audio.' . '</div>
	</video>
';
	} else if ($__vars['mediaItem']['media_type'] == 'embed') {
		$__finalCompiled .= '
	<div class="js-embedContent"
		data-media-site-id="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getMediaSiteId', array())) . '"
		data-site-media-id="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getSiteMediaId', array())) . '">
		' . $__templater->func('bb_code', array($__vars['mediaItem']['media_tag'], 'xfmg_media', $__vars['mediaItem'], ), true) . '
	</div>
';
	}
	$__finalCompiled .= '</div>';
	return $__finalCompiled;
}
);