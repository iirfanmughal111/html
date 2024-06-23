<?php
// FROM HASH: 26bf34b782a99c6f64c8e0da5cdbc2e3
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = '';
	$__compilerTemp1 .= '
				';
	if ($__vars['galleryStatistics']['category_count']) {
		$__compilerTemp1 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Categories' . '</dt>
						<dd>' . $__templater->filter($__vars['galleryStatistics']['category_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp1 .= '

				';
	if ($__vars['galleryStatistics']['album_count']) {
		$__compilerTemp1 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Albums' . '</dt>
						<dd>' . $__templater->filter($__vars['galleryStatistics']['album_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp1 .= '

				';
	if ($__vars['galleryStatistics']['upload_count']) {
		$__compilerTemp1 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Uploaded media' . '</dt>
						<dd>' . $__templater->filter($__vars['galleryStatistics']['upload_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp1 .= '

				';
	if ($__vars['galleryStatistics']['embed_count']) {
		$__compilerTemp1 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Embedded media' . '</dt>
						<dd>' . $__templater->filter($__vars['galleryStatistics']['embed_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp1 .= '

				';
	if ($__vars['galleryStatistics']['comment_count']) {
		$__compilerTemp1 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Comments' . '</dt>
						<dd>' . $__templater->filter($__vars['galleryStatistics']['comment_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp1 .= '

				';
	if ($__vars['galleryStatistics']['disk_usage']) {
		$__compilerTemp1 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Disk usage' . '</dt>
						<dd>' . $__templater->filter($__vars['galleryStatistics']['disk_usage'], array(array('file_size', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp1 .= '
				';
	if (strlen(trim($__compilerTemp1)) > 0) {
		$__finalCompiled .= '
	<div class="block" data-widget-section="galleryStats"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
			<div class="block-body block-row">
				' . $__compilerTemp1 . '
			</div>
		</div>
	</div>
';
	}
	$__finalCompiled .= '

';
	$__compilerTemp2 = '';
	$__compilerTemp2 .= '
				';
	if ($__vars['xf']['visitor']['xfmg_album_count']) {
		$__compilerTemp2 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Your albums' . '</dt>
						<dd>' . $__templater->filter($__vars['xf']['visitor']['xfmg_album_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp2 .= '

				';
	if ($__vars['xf']['visitor']['xfmg_media_count']) {
		$__compilerTemp2 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Your media' . '</dt>
						<dd>' . $__templater->filter($__vars['xf']['visitor']['xfmg_media_count'], array(array('number', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp2 .= '

				';
	if ($__vars['xf']['visitor']['xfmg_media_quota']) {
		$__compilerTemp2 .= '
					<dl class="pairs pairs--justified">
						<dt>' . 'Your usage' . '</dt>
						<dd>' . $__templater->filter($__vars['xf']['visitor']['xfmg_media_quota'], array(array('file_size', array()),), true) . '</dd>
					</dl>
				';
	}
	$__compilerTemp2 .= '
				';
	if (strlen(trim($__compilerTemp2)) > 0) {
		$__finalCompiled .= '
	<div class="block" data-widget-section="yourStats"' . $__templater->func('widget_data', array($__vars['widget'], ), true) . '>
		<div class="block-container">
			<h3 class="block-minorHeader">' . 'Your statistics' . '</h3>
			<div class="block-body block-row">
				' . $__compilerTemp2 . '
			</div>
		</div>
	</div>
';
	}
	return $__finalCompiled;
}
);