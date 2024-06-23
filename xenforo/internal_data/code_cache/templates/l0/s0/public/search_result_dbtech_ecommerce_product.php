<?php
// FROM HASH: f7bc3af70985b94d1243d74a4cd6c634
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<li class="block-row block-row--separated ' . ($__templater->method($__vars['product'], 'isIgnored', array()) ? 'is-ignored' : '') . ' js-inlineModContainer" data-author="' . ($__templater->escape($__vars['product']['User']['username']) ?: $__templater->escape($__vars['product']['username'])) . '">
	<div class="contentRow ' . ((!$__templater->method($__vars['product'], 'isVisible', array())) ? 'is-deleted' : '') . '">
		<span class="contentRow-figure">
			' . $__templater->func('avatar', array($__vars['product']['User'], 's', false, array(
		'defaultname' => $__vars['product']['username'],
	))) . '
		</span>
		<div class="contentRow-main">
			<h3 class="contentRow-title">
				<a href="' . $__templater->func('link', array('dbtech-ecommerce', $__vars['product'], ), true) . '">' . $__templater->func('prefix', array('dbtechEcommerceProduct', $__vars['product'], ), true) . $__templater->func('highlight', array($__vars['product']['title'], $__vars['options']['term'], ), true) . '</a>
				';
	if ($__templater->method($__vars['product'], 'hasDownloadFunctionality', array()) AND !$__templater->test($__vars['product']['LatestVersion'], 'empty', array())) {
		$__finalCompiled .= '
					<span class="u-muted">' . $__templater->escape($__vars['product']['LatestVersion']['version_string']) . '</span>
				';
	}
	$__finalCompiled .= '
			</h3>

			<div class="contentRow-snippet">' . $__templater->func('snippet', array($__vars['product']['description'], 300, array('term' => $__vars['options']['term'], 'stripQuote' => true, ), ), true) . '</div>

			<div class="contentRow-minor contentRow-minor--hideLinks">
				<ul class="listInline listInline--bullet">
					';
	if (($__vars['options']['mod'] == 'dbtech_ecommerce_product') AND $__templater->method($__vars['product'], 'canUseInlineModeration', array())) {
		$__finalCompiled .= '
						<li>' . $__templater->formCheckBox(array(
			'standalone' => 'true',
		), array(array(
			'value' => $__vars['product']['product_id'],
			'class' => 'js-inlineModToggle',
			'_type' => 'option',
		))) . '</li>
					';
	}
	$__finalCompiled .= '
					<li>' . $__templater->func('username_link', array($__vars['product']['User'], false, array(
		'defaultname' => $__vars['product']['username'],
	))) . '</li>
					<li>' . 'Product' . '</li>
					<li>' . $__templater->func('date_dynamic', array($__vars['product']['creation_date'], array(
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