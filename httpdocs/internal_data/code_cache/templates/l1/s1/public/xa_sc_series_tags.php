<?php
// FROM HASH: bdbc292d2f4edd5d54159200a971d343
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped('Edit tags');
	$__finalCompiled .= '

';
	$__templater->breadcrumbs($__templater->method($__vars['series'], 'getBreadcrumbs', array()));
	$__finalCompiled .= '

' . $__templater->callMacro('tag_macros', 'edit_form', array(
		'action' => $__templater->func('link', array('showcase/series/tags', $__vars['series'], ), false),
		'uneditableTags' => $__vars['uneditableTags'],
		'editableTags' => $__vars['editableTags'],
		'minTags' => $__vars['xf']['options']['xaScSeriesMinTags'],
		'tagList' => 'tagList--series-' . $__vars['series']['series_id'],
	), $__vars);
	return $__finalCompiled;
}
);