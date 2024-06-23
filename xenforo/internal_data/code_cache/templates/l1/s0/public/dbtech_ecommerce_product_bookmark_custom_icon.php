<?php
// FROM HASH: e1990543bfd5befe97ace80984db2b9e
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '<div class="contentRow-figure contentRow-figure--fixedBookmarkIcon">
	<div class="contentRow-figureContainer">
		' . $__templater->func('dbtech_ecommerce_product_icon', array($__vars['content'], 's', ), true) . '
		' . $__templater->func('avatar', array($__vars['content']['User'], 's', false, array(
		'href' => '',
		'class' => 'avatar--separated contentRow-figureSeparated',
		'defaultname' => $__vars['content']['username'],
	))) . '
	</div>
</div>';
	return $__finalCompiled;
}
);