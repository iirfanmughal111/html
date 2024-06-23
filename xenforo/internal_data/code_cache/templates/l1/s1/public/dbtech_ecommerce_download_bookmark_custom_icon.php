<?php
// FROM HASH: 92805901cdf030a25a6f2cdb9f46b4f1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-figure contentRow-figure--fixedBookmarkIcon">
	<div class="contentRow-figureContainer">
		' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['content']['Product'], 's', ), true) . '
		' . $__templater->func('avatar', array($__vars['content']['Product']['User'], 's', false, array(
		'href' => '',
		'class' => 'avatar--separated contentRow-figureSeparated',
		'defaultname' => $__vars['content']['Product']['username'],
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);