<?php
// FROM HASH: d036f51e805dbd108df9487a5cb16b1b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated' . ($__templater->method($__vars['album'], 'isIgnored', array()) ? ' is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['album']['User']['username']) ?: $__templater->escape($__vars['album']['username'])) . '">
	<div class="contentRow' . ((!$__templater->method($__vars['album'], 'isVisible', array())) ? ' is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('xfmg_thumbnail', array($__vars['album'], ), true) . '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('media/albums', $__vars['album'], ), true) . '">' . $__templater->func('highlight', array($__vars['album']['title'], $__vars['options']['term'], ), true) . '</a>
			</h3>

			<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['album']['description'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'xfmg_album') AND $__templater->method($__vars['album'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['album']['album_id'],
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['album']['User'], false, array(
		'defaultname' => $__vars['album']['username'],
	))) . '</li>
					<li>' . 'Album' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['album']['create_date'], array(
	))) . '</li>
					<li>' . 'Comments' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['album']['comment_count'], array(array('number', array()),), true) . '</li>
					';
	if ($__vars['album']['category_id']) {
		$__finalCompiled .= '
						<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['album']['Category']['title']) . '</li>
					';
	}
	$__finalCompiled .= '
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);