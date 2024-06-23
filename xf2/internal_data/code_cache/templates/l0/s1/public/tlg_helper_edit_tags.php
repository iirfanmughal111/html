<?php
// FROM HASH: dd3724705fa6792791b68da8ef7c08ac
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit tags');
	$__finalCompiled .= '

';
	if ($__vars['group']) {
		$__finalCompiled .= '
    ';
		$__templater->breadcrumbs($__templater->method($__vars['group']['Category'], 'getBreadcrumbs', array()));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('tag_macros', 'edit_form', array(
		'action' => $__vars['formAction'],
		'uneditableTags' => $__vars['uneditableTags'],
		'editableTags' => $__vars['editableTags'],
		'minTags' => '0',
	), $__vars) . '
';
	return $__finalCompiled;
}
);