<?php
// FROM HASH: ae84dc10a594b2ecd966f9d1a7b44bbf
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['product'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['product']['User']['username']) ?: $__templater->escape($__vars['product']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['download'], 'isVisible', array())) ? 'is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['product']['User'], 's', false, array(
		'defaultname' => $__vars['product']['username'],
	))) . '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('dbtech-ecommerce/release', $__vars['download'], ), true) . '">' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->func('highlight', array($__vars['download']['title'], $__vars['options']['term'], ), true) . '</a>
			</h3>

			<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['download']['change_log'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					<li>' . $__templater->func('username_link', array($__vars['product']['User'], false, array(
		'defaultname' => $__vars['product']['username'],
	))) . '</li>
					<li>' . 'Download' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['download']['release_date'], array(
	))) . '</li>
					<li>' . 'Category' . $__vars['xf']['language']['label_separator'] . ' <a href="' . $__templater->func('link', array('dbtech-ecommerce/categories', $__vars['product']['Category'], ), true) . '">' . $__templater->escape($__vars['product']['Category']['title']) . '</a></li>
				</ul>
			</div>
		</div>
	</div>
</li>';
	return $__finalCompiled;
}
);