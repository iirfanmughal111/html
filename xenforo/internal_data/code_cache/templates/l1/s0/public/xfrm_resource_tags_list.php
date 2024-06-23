<?php
// FROM HASH: 196f9c8461d8927fa227a2bd7ff537fe
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tag_macros', 'list', array(
		'tags' => $__vars['resource']['tags'],
		'tagList' => 'tagList--resource-' . $__vars['resource']['resource_id'],
		'editLink' => ($__templater->method($__vars['resource'], 'canEditTags', array()) ? $__templater->func('link', array('resources/tags', $__vars['resource'], ), false) : ''),
	), $__vars);
	return $__finalCompiled;
}
);