<?php
// FROM HASH: e70baefb640d3f84914378eda0f9608c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit post');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['group'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro('tlg_comment_edit', 'edit_form', array(
		'formUrl' => $__templater->func('link', array('group-posts/edit', $__vars['post'], ), false),
		'attachmentData' => $__vars['attachmentData'],
		'message' => $__vars['post']['FirstComment']['message'],
		'quickEdit' => $__vars['quickEdit'],
	), $__vars);
	return $__finalCompiled;
}
);