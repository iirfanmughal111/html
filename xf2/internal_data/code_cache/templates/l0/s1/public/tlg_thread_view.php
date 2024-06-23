<?php
// FROM HASH: f7684b03b24f11288c74845cecca407b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__compilerTemp1 = $__vars;
	$__compilerTemp1['pageSelected'] = $__templater->preEscaped('discussions');
	$__compilerTemp1['noBreadcrumbs'] = $__templater->preEscaped('1');
	$__compilerTemp1['noPageOptions'] = $__templater->preEscaped('1');
	$__templater->wrapTemplate('tlg_group_wrapper', $__compilerTemp1);
	$__finalCompiled .= '

<div class="p-body-header">
    <div class="p-title"><h2 class="p-title-value">' . $__templater->func('prefix', array('thread', $__vars['thread'], ), true) . $__templater->escape($__vars['thread']['title']) . '</h2></div>
</div>

';
	if ($__vars['thread']['discussion_type'] == 'poll') {
		$__finalCompiled .= '
    ' . $__templater->includeTemplate('thread_view_type_poll', $__vars) . '
';
	} else if ($__vars['thread']['discussion_type'] == 'article') {
		$__finalCompiled .= '
    ' . $__templater->includeTemplate('thread_view_type_article', $__vars) . '
';
	} else if ($__vars['thread']['discussion_type'] == 'question') {
		$__finalCompiled .= '
    ' . $__templater->includeTemplate('thread_view_type_question', $__vars) . '
';
	} else if ($__vars['thread']['discussion_type'] == 'suggestion') {
		$__finalCompiled .= '
    ' . $__templater->includeTemplate('thread_view_type_suggestion', $__vars) . '
';
	} else {
		$__finalCompiled .= '
    ' . $__templater->includeTemplate('thread_view', $__vars) . '
';
	}
	return $__finalCompiled;
}
);