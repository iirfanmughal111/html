<?php
// FROM HASH: b254ac3419ed51fffd8bb13d9a1783d5
return array(
'macros' => array('product_page_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'product' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->setPageParam('productCategory', $__vars['category']);
	$__finalCompiled .= '

	';
	$__templater->setPageParam('searchConstraints', array('Products' => array('search_type' => 'dbtech_ecommerce_product', ), 'This category' => array('search_type' => 'dbtech_ecommerce_product', 'c' => array('categories' => array($__vars['category']['category_id'], ), 'child_categories' => 1, ), ), ));
	$__finalCompiled .= '
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';

	return $__finalCompiled;
}
);