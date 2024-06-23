<?php
// FROM HASH: ebca039507b96a7dacb1ce50448abefd
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tag_macros', 'list', array(
		'tags' => $__vars['series']['tags'],
		'tagList' => 'tagList--series-' . $__vars['series']['series_id'],
		'editLink' => ($__templater->method($__vars['series'], 'canEditTags', array()) ? $__templater->func('link', array('showcase/series/tags', $__vars['series'], ), false) : ''),
	), $__vars);
	return $__finalCompiled;
}
);