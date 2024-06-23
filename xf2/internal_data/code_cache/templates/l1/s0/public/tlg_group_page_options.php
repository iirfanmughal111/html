<?php
// FROM HASH: dddd55a6b9ebb39916dd37be2fc8a73d
return array(
'macros' => array('page_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'category' => '!',
		'group' => null,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
    ';
	$__templater->setPageParam('groupCategory', $__vars['category']);
	$__finalCompiled .= '

    ';
	$__templater->setPageParam('searchConstraints', array('Groups' => array('search_type' => 'tl_groups', ), 'This category' => array('search_type' => 'tl_group', 'c' => array('categories' => array($__vars['category']['category_id'], ), 'child_categories' => 1, ), ), ));
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