<?php
// FROM HASH: 78b95cb50e792286b08d7b4430a4ccf4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('dbtech_ecommerce_product_review_macros', 'author_reply_row', array(
		'review' => $__vars['review'],
		'product' => $__vars['product'],
	), $__vars);
	return $__finalCompiled;
}
);