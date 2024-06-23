<?php
// FROM HASH: e56d28a7894a236ed50d97eda0976d94
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '
<div class="embeddedScSeries  block--messages">
	<div class="block-row block-row--separated" data-author="' . ($__templater->escape($__vars['series']['User']['username']) ?: $__templater->escape($__vars['series']['username'])) . '">
		<div class="contentRow scSeriesSearchResultRow">
			';
	if ($__vars['series']['icon_date']) {
		$__finalCompiled .= '
				<span class="contentRow-figure">
					<a href="' . $__templater->func('link', array('showcase/series', $__vars['series'], ), true) . '">
						' . $__templater->func('sc_series_icon', array($__vars['series'], 's', $__templater->func('link', array('showcase/series', $__vars['series'], ), false), ), true) . '
					</a>				
				</span>
			';
	}
	$__finalCompiled .= '
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
						<li>' . $__templater->func('username_link', array($__vars['series']['User'], false, array(
		'defaultname' => $__vars['series']['User']['username'],
	))) . '</li>
						<li>' . $__templater->func('date_dynamic', array($__vars['series']['create_date'], array(
	))) . '</li>
						';
	if ($__vars['series']['last_part_date'] AND ($__vars['series']['last_part_date'] > $__vars['series']['create_date'])) {
		$__finalCompiled .= '
							<li>' . 'Updated' . ' ' . $__templater->func('date_dynamic', array($__vars['series']['last_part_date'], array(
		))) . '</li>
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
						';
	if ($__vars['series']['LastItem']) {
		$__finalCompiled .= '
							<li>
								' . 'Latest item' . ': <a href="' . $__templater->func('link', array('showcase', $__vars['series']['LastItem'], ), true) . '" class="">' . $__templater->escape($__vars['series']['LastItem']['title']) . '</a>
							</li>
						';
	}
	$__finalCompiled .= '
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);