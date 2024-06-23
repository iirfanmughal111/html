<?php
// FROM HASH: 0260d7e77505dd6e8fc4b041e7ead262
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit tags');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['resource'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro('tag_macros', 'edit_form', array(
		'action' => $__templater->func('link', array('resources/tags', $__vars['resource'], ), false),
		'uneditableTags' => $__vars['uneditableTags'],
		'editableTags' => $__vars['editableTags'],
		'minTags' => $__vars['category']['min_tags'],
		'tagList' => 'tagList--resource-' . $__vars['resource']['resource_id'],
	), $__vars) . '
';
	return $__finalCompiled;
}
);