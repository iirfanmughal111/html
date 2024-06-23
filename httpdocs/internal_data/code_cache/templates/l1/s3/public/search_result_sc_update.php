<?php
// FROM HASH: ea01ddaca92885ccf500a50e1203934b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated js-inlineModContainer" data-author="' . ($__templater->escape($__vars['update']['User']['username']) ?: $__templater->escape($__vars['item']['User']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['update'], 'isVisible', array())) ? 'is-deleted' : '') . ' scItemSearchResultRow">
		<span class="contentRow-figure">
			';
	if ($__vars['update']['Item']['CoverImage']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase/update', $__vars['update'], ), true) . '">
					' . $__templater->func('sc_item_thumbnail', array($__vars['update']['Item'], ), true) . '
				</a>				
			';
	} else if ($__vars['update']['Item']['Category']['content_image_url']) {
		$__finalCompiled .= '
				<a href="' . $__templater->func('link', array('showcase/update', $__vars['update'], ), true) . '">
					' . $__templater->func('sc_category_icon', array($__vars['update']['Item'], ), true) . '
				</a>				
			';
	} else {
		$__finalCompiled .= '
				' . $__templater->func('avatar', array($__vars['update']['User'], 's', false, array(
			'defaultname' => $__vars['update']['username'],
		))) . '
			';
	}
	$__finalCompiled .= '			
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('showcase/update', $__vars['update'], ), true) . '">' . $__templater->func('prefix', array('sc_item', $__vars['item'], ), true) . $__templater->escape($__vars['item']['title']) . ' - ' . $__templater->func('highlight', array($__vars['update']['title'], $__vars['options']['term'], ), true) . '</a>
			</h3>

			<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['update']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					<li>' . $__templater->func('username_link', array($__vars['update']['User'], false, array(
		'defaultname' => $__vars['update']['username'],
	))) . '</li>
					<li>' . 'Showcase update' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['update']['update_date'], array(
	))) . '</li>
					<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('showcase/categories', $__vars['item']['Category'], ), true) . '">' . $__templater->escape($__vars['item']['Category']['title']) . '</a></li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);