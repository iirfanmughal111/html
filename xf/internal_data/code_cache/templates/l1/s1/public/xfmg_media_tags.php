<?php
// FROM HASH: 8e8b968ec1eda56bde0dc744dd820f62
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit tags');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['mediaItem'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro('tag_macros', 'edit_form', array(
		'action' => $__templater->func('link', array('media/tags', $__vars['mediaItem'], ), false),
		'uneditableTags' => $__vars['uneditableTags'],
		'editableTags' => $__vars['editableTags'],
		'minTags' => $__vars['mediaItem']['min_tags'],
		'tagList' => 'tagList--mediaItem-' . $__vars['mediaItem']['media_id'],
	), $__vars) . '

';
	return $__finalCompiled;
}
);