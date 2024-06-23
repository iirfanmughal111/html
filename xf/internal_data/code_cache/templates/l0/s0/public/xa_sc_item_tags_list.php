<?php
// FROM HASH: 64f0d96936ebd7abdf2684fd2ceb0692
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tag_macros', 'list', array(
		'tags' => $__vars['item']['tags'],
		'tagList' => 'tagList--item-' . $__vars['item']['item_id'],
		'editLink' => ($__templater->method($__vars['item'], 'canEditTags', array()) ? $__templater->func('link', array('showcase/tags', $__vars['item'], ), false) : ''),
	), $__vars);
	return $__finalCompiled;
}
);