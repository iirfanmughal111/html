<?php
// FROM HASH: 17dd022c41f84ffa39bfaa67b6e8b510
return array(
'macros' => array('resource_page_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'resource' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->setPageParam('resourceCategory', $__vars['category']);
	$__finalCompiled .= '

	';
	$__templater->setPageParam('searchConstraints', array('Resources' => array('search_type' => 'resource', ), 'This category' => array('search_type' => 'resource', 'c' => array('categories' => array($__vars['category']['resource_category_id'], ), 'child_categories' => 1, ), ), ));
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