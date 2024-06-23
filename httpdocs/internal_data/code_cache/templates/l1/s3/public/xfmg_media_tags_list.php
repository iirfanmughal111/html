<?php
// FROM HASH: 8cfc63ac2294ae42158e17f972457d5f
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->callMacro('tag_macros', 'list', array(
		'tags' => $__vars['mediaItem']['tags'],
		'tagList' => 'tagList--mediaItem-' . $__vars['mediaItem']['media_id'],
		'editLink' => ($__templater->method($__vars['mediaItem'], 'canEditTags', array()) ? $__templater->func('link', array('media/tags', $__vars['mediaItem'], ), false) : ''),
	), $__vars);
	return $__finalCompiled;
}
);