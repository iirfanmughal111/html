<?php
// FROM HASH: 4ec422192be87d61c9ea5568c94eb072
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['mediaItem']) {
		$__finalCompiled .= '
	<p>
		';
		if ($__vars['mediaItem']['media_type'] == 'image') {
			$__finalCompiled .= '
			<img src="' . $__templater->func('link', array('canonical:media/full', $__vars['mediaItem'], array('d' => ($__vars['mediaItem']['last_edit_date'] ?: null), ), ), true) . '" alt="' . $__templater->escape($__vars['mediaItem']['title']) . '" width="500" />
		';
		} else if ($__vars['mediaItem']['media_type'] == 'video') {
			$__finalCompiled .= '
			<video controls poster="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getPosterUrl', array())) . '" width="500">
				<source src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getVideoUrl', array(true, ))) . '" type="video/mp4" />
			</video>
		';
		} else if ($__vars['mediaItem']['media_type'] == 'audio') {
			$__finalCompiled .= '
			<video controls poster="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getPosterUrl', array())) . '" width="500">
				<source src="' . $__templater->escape($__templater->method($__vars['mediaItem'], 'getAudioUrl', array(true, ))) . '" type="audio/mpeg" />
			</video>
		';
		} else if ($__vars['mediaItem']['media_type'] == 'embed') {
			$__finalCompiled .= '
			' . $__templater->func('bb_code', array($__vars['mediaItem']['media_tag'], 'xfmg_media', $__vars['mediaItem'], ), true) . '
		';
		}
		$__finalCompiled .= '
	</p>
	';
		if ($__vars['mediaItem']['description']) {
			$__finalCompiled .= '
		<p>' . $__templater->func('structured_text', array($__vars['mediaItem']['description'], ), true) . '</p>
	';
		}
		$__finalCompiled .= '
	<p>
		<a href="' . $__templater->func('link', array('canonical:media', $__vars['mediaItem'], ), true) . '" target="_blank">' . 'View this media item' . '</a>
	</p>
';
	}
	return $__finalCompiled;
}
);