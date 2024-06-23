<?php
// FROM HASH: b1c5cc3bd8bfe7724367b09d3704cf54
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xfmg_gallery_bb_code.less');
	$__finalCompiled .= '

<div class="embeddedMedia">
	<div class="embeddedMedia-container">
		<div class="embeddedMedia-thumbList">
			';
	if ($__templater->isTraversable($__vars['mediaItems'])) {
		foreach ($__vars['mediaItems'] AS $__vars['mediaItem']) {
			$__finalCompiled .= '
				<div class="embeddedMedia-thumbList-item">
					<a href="' . $__templater->func('link', array('media', $__vars['mediaItem'], ), true) . '">
						' . $__templater->func('xfmg_thumbnail', array($__vars['mediaItem'], 'xfmgThumbnail--fluid', true, ), true) . '
					</a>
				</div>
			';
		}
	}
	$__finalCompiled .= '

			';
	if ($__vars['showXMore']) {
		$__finalCompiled .= '
				<div class="embeddedMedia-thumbList-item embeddedMedia-thumbList-item--showMore">
					<a href="' . $__templater->func('link', array('media/albums', $__vars['album'], ), true) . '">
						<img src="' . $__templater->func('transparent_img', array(), true) . '" alt="" />
						<span>+' . $__templater->filter(($__vars['album']['media_count'] - $__templater->method($__vars['mediaItems'], 'count', array())), array(array('number_short', array()),), true) . '</span>
					</a>
				</div>
			';
	}
	$__finalCompiled .= '

			';
	$__compilerTemp1 = $__templater->func('range', array(1, 10, ), false);
	if ($__templater->isTraversable($__compilerTemp1)) {
		foreach ($__compilerTemp1 AS $__vars['placeholder']) {
			$__finalCompiled .= '
				<div class="embeddedMedia-thumbList-item embeddedMedia-thumbList-item--placeholder"></div>
			';
		}
	}
	$__finalCompiled .= '
		</div>
	</div>
	<div class="embeddedMedia-info fauxBlockLink">
		<div class="contentRow">
			<div class="contentRow-main">
				<h4 class="contentRow-title">
					<a href="' . $__templater->func('link', array('media/albums', $__vars['album'], ), true) . '" class="fauxBlockLink-blockLink u-cloaked">' . $__templater->escape($__vars['album']['title']) . '</a>
				</h4>
				<div class="contentRow-lesser p-description">
					<ul class="listInline listInline--bullet is-structureList">
						<li>' . $__templater->fontAwesome('fa-user', array(
		'title' => $__templater->filter('Album owner', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('username_link', array($__vars['album']['User'], false, array(
		'defaultname' => $__vars['album']['username'],
		'class' => 'u-concealed',
	))) . '</li>
						<li>' . $__templater->fontAwesome('fa-clock', array(
		'title' => $__templater->filter('Date added', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->func('date_dynamic', array($__vars['album']['create_date'], array(
	))) . '</li>
						<li>' . $__templater->fontAwesome('fa-th', array(
		'title' => $__templater->filter('Items', array(array('for_attr', array()),), false),
	)) . ' ' . $__templater->filter($__vars['album']['media_count'], array(array('number_short', array()),), true) . '</li>
						';
	if ($__vars['album']['comment_count']) {
		$__finalCompiled .= '
							<li>' . $__templater->fontAwesome('fa-comments', array(
			'title' => $__templater->filter('Comments', array(array('for_attr', array()),), false),
		)) . ' ' . $__templater->filter($__vars['album']['comment_count'], array(array('number_short', array()),), true) . '</li>
						';
	}
	$__finalCompiled .= '
					</ul>
				</div>
				';
	if ($__vars['album']['description']) {
		$__finalCompiled .= '
					<div class="contentRow-snippet">
						' . $__templater->func('structured_text', array($__templater->func('snippet', array($__vars['album']['description'], 100, ), false), ), true) . '
					</div>
				';
	}
	$__finalCompiled .= '
			</div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);