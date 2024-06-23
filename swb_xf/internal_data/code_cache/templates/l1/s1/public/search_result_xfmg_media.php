<?php
// FROM HASH: 748434007220c28c1e16cfd337a502c8
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated' . ($__templater->method($__vars['mediaItem'], 'isIgnored', array()) ? ' is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['mediaItem']['User']['username']) ?: $__templater->escape($__vars['mediaItem']['username'])) . '">
	<div class="contentRow' . ((!$__templater->method($__vars['mediaItem'], 'isVisible', array())) ? ' is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('xfmg_thumbnail', array($__vars['mediaItem'], ), true) . '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('media', $__vars['mediaItem'], ), true) . '">' . $__templater->func('highlight', array($__vars['mediaItem']['title'], $__vars['options']['term'], ), true) . '</a>
			</h3>

			<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['mediaItem']['description'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'xfmg_media') AND $__templater->method($__vars['mediaItem'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['mediaItem']['media_id'],
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['mediaItem']['User'], false, array(
		'defaultname' => $__vars['mediaItem']['username'],
	))) . '</li>
					<li>' . 'Media item' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['mediaItem']['media_date'], array(
	))) . '</li>
					';
	if ($__vars['xf']['options']['enableTagging'] AND $__vars['mediaItem']['tags']) {
		$__finalCompiled .= '
						<li>
							' . $__templater->callMacro('tag_macros', 'simple_list', array(
			'tags' => $__vars['mediaItem']['tags'],
			'containerClass' => 'contentRow-minor',
			'highlightTerm' => ($__vars['options']['tag'] ?: $__vars['options']['term']),
		), $__vars) . '
						</li>
					';
	}
	$__finalCompiled .= '
					<li>' . 'Comments' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['mediaItem']['comment_count'], array(array('number', array()),), true) . '</li>
					';
	if ($__vars['mediaItem']['album_id']) {
		$__finalCompiled .= '
						<li>' . 'Album' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['mediaItem']['Album']['title']) . '</li>
					';
	}
	$__finalCompiled .= '
					';
	if ($__vars['mediaItem']['category_id']) {
		$__finalCompiled .= '
						<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->escape($__vars['mediaItem']['Category']['title']) . '</li>
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