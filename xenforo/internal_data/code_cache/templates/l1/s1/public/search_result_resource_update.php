<?php
// FROM HASH: 58e0e4ca7d5ddec2c00ebca2b2bc2450
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['resource'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['resource']['User']['username']) ?: $__templater->escape($__vars['resource']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['update'], 'isVisible', array())) ? 'is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['resource']['User'], 's', false, array(
		'defaultname' => $__vars['resource']['username'],
	))) . '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('resources/update', $__vars['update'], ), true) . '">' . $__templater->func('prefix', array('resource', $__vars['resource'], ), true) . $__templater->escape($__vars['resource']['title']) . ' - ' . $__templater->func('highlight', array($__vars['update']['title'], $__vars['options']['term'], ), true) . '</a>
			</h3>

			<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['update']['message'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					<li>' . $__templater->func('username_link', array($__vars['resource']['User'], false, array(
		'defaultname' => $__vars['resource']['username'],
	))) . '</li>
					<li>' . 'Resource update' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['update']['post_date'], array(
	))) . '</li>
					<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('resources/categories', $__vars['resource']['Category'], ), true) . '">' . $__templater->escape($__vars['resource']['Category']['title']) . '</a></li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);