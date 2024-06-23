<?php
// FROM HASH: ec3541dd1e7a79d0d75d846586fc1d9f
return array(
'macros' => array('item_page_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'item' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->setPageParam('scCategory', $__vars['category']);
	$__finalCompiled .= '

	';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), 'This category' => array('search_type' => 'sc_item', 'c' => array('categories' => array($__vars['category']['category_id'], ), 'child_categories' => 1, ), ), ));
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