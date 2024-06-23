<?php
// FROM HASH: 9ee4134eaf8f470ee8d8dd9cd16c3b52
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeCss('xa_sc.less');
	$__finalCompiled .= '
';
	$__templater->includeCss('message.less');
	$__finalCompiled .= '
<div class="embeddedScItem block--messages">
	<div class="block-row block-row--separated" data-author="' . ($__templater->escape($__vars['itemPage']['User']['username']) ?: $__templater->escape($__vars['itemPage']['username'])) . '">
		<div class="contentRow scItemSearchResultRow">
			';
	if ((($__vars['itemPage']['CoverImage'] OR $__vars['itemPage']['Item']['CoverImage']) OR $__vars['itemPage']['Item']['Category']['content_image_url']) OR ($__vars['itemPage']['Item']['SeriesPart']['Series'] AND $__vars['itemPage']['Item']['SeriesPart']['Series']['icon_date'])) {
		$__finalCompiled .= '
				<span class="contentRow-figure">
					';
		if ($__vars['itemPage']['CoverImage']) {
			$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('showcase/page', $__vars['itemPage'], ), true) . '">
							' . $__templater->func('sc_item_page_thumbnail', array($__vars['itemPage'], $__vars['itemPage']['Item'], ), true) . '
						</a>	
					';
		} else if ($__vars['itemPage']['Item']['CoverImage']) {
			$__finalCompiled .= '					
						<a href="' . $__templater->func('link', array('showcase/page', $__vars['itemPage'], ), true) . '">
							' . $__templater->func('sc_item_thumbnail', array($__vars['itemPage']['Item'], ), true) . '
						</a>	
					';
		} else if ($__vars['itemPage']['Item']['SeriesPart']['Series'] AND $__vars['itemPage']['Item']['SeriesPart']['Series']['icon_date']) {
			$__finalCompiled .= '
						' . $__templater->func('sc_series_icon', array($__vars['itemPage']['Item']['SeriesPart']['Series'], 's', $__templater->func('link', array('showcase/page', $__vars['itemPage'], ), false), ), true) . '			
					';
		} else if ($__vars['itemPage']['Item']['Category']['content_image_url']) {
			$__finalCompiled .= '
						<a href="' . $__templater->func('link', array('showcase/page', $__vars['itemPage'], ), true) . '">
							' . $__templater->func('sc_category_icon', array($__vars['itemPage']['Item'], ), true) . '
						</a>				
					';
		}
		$__finalCompiled .= '
				</span>
			';
	}
	$__finalCompiled .= '
			<div class="contentRow-main">
				<h3 class="contentRow-title">
					<a href="' . $__templater->func('link', array('showcase/page', $__vars['itemPage'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['itemPage']['Item'], ), true) . ' ' . $__templater->escape($__vars['itemPage']['Item']['title']) . ' - ' . $__templater->escape($__vars['itemPage']['title']) . '</a>
				</h3>

				<div class="contentRow-snippet">
					' . $__templater->func('snippet', array($__vars['itemPage']['message'], 300, array('stripQuote' => true, ), ), true) . '
				</div>

				<div class="contentRow-minor contentRow-minor--hideLinks">
					<ul class="listInline listInline--bullet">
						<li>' . $__templater->func('username_link', array($__vars['itemPage']['User'], false, array(
		'defaultname' => $__vars['itemPage']['username'],
	))) . '</li>
						<li>' . $__templater->func('date_dynamic', array($__vars['itemPage']['create_date'], array(
	))) . '</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>';
	return $__finalCompiled;
}
);