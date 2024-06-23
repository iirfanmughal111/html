<?php
// FROM HASH: 6d78aad9cb947aeb340bd7975245f80f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['series'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['series']['User']['username']) ?: $__templater->escape($__vars['series']['username'])) . '">
	<div class="contentRow scSeriesSearchResultRow">
		<span class="contentRow-figure">
			';
	if ($__vars['series']['icon_date']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '">
					' . $__templater->func('sc_series_icon', array($__vars['series'], ), true) . '
				</a>				
			';
	} else {
		$__finalCompiled .= '
				' . $__templater->func('avatar', array($__vars['series']['User'], 's', false, array(
			'defaultname' => $__vars['series']['User']['username'],
		))) . '
			';
	}
	$__finalCompiled .= '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '">' . $__templater->func('highlight', array($__vars['series']['title'], $__vars['options']['term'], ), true) . '</a>
			</h3>

			<div class="contentRow-snippet">
				';
	if ($__vars['series']['description'] != '') {
		$__finalCompiled .= '				
						' . $__templater->func('snippet', array($__vars['series']['description'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '
					';
	} else {
		$__finalCompiled .= '
						' . $__templater->func('snippet', array($__vars['series']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '
				';
	}
	$__finalCompiled .= '					
			</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'sc_series') AND $__templater->method($__vars['series'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['series']['series_id'],
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['series']['User'], false, array(
		'defaultname' => $__vars['series']['User']['username'],
	))) . '</li>
					<li>' . 'Series' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['series']['create_date'], array(
	))) . '</li>
					';
	if ($__vars['xf']['options']['enableTagging'] AND $__vars['series']['tags']) {
		$__finalCompiled .= '
						<li>
							' . $__templater->callMacro('tag_macros', 'simple_list', array(
			'tags' => $__vars['series']['tags'],
			'containerClass' => 'contentRow-minor',
			'highlightTerm' => ($__vars['options']['tag'] ?: $__vars['options']['term']),
		), $__vars) . '
						</li>
					';
	}
	$__finalCompiled .= '					
					';
	if ($__vars['series']['item_count']) {
		$__finalCompiled .= '<li>' . 'Items' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['series']['item_count'], array(array('number', array()),), true) . '</li>';
	}
	$__finalCompiled .= '
					';
	if ($__vars['series']['view_count']) {
		$__finalCompiled .= '<li>' . 'Views' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['series']['view_count'], array(array('number', array()),), true) . '</li>';
	}
	$__finalCompiled .= '
					';
	if ($__vars['series']['watch_count']) {
		$__finalCompiled .= '<li>' . 'Watching' . $__vars['xf']['language']['label_separator'] . ' ' . $__templater->filter($__vars['series']['watch_count'], array(array('number', array()),), true) . '</li>';
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