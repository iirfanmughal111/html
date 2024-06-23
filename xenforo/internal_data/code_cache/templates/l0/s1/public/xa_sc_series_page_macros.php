<?php
// FROM HASH: 1da552bed781479180727bdfd18f76bd
return array(
'macros' => array('series_page_options' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'series' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '
	';
	$__templater->setPageParam('searchConstraints', array('Items' => array('search_type' => 'sc_item', ), 'This series' => array('search_type' => 'sc_item', 'c' => array('series' => array($__vars['series']['series_id'], ), ), ), ));
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